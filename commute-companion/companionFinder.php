<!DOCTYPE html>
<?php
error_reporting(E_ERROR);
session_start();
if (!isset($_POST['email']) && !isset($_SESSION['email'])) {
    echo "<script> location.href = \"index.php\"; </script>";
}

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="companionFinderStyle.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="all">
        <meta charset="UTF-8">

        <title>Commute Companion</title>

        <script type="text/javascript">
            var route;
            var stop_from;
            var depart_time;
            var stop_to;
            var stop_from_lat;
            var stop_from_long;
            var stop_to_lat;
            var stop_to_long;

            function checkSameTrip() {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        document.getElementById("matches-found-block").style.display = 'block';
                        document.getElementById("matches-found-block").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.open("GET", "checkSameTrip.php", true);
                xmlhttp.send();
            }

            function getStopTimes(s, r) {
                stop_from = s;
                if (s === "") {
                    document.getElementById("stop_times_div").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) { 
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                            document.getElementById("stop_times_div").innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("GET", "stopTimes.php?s=" + s + "&r=" + r, true);
                    xmlhttp.send();
                }
            }
            function getStopNos(value) {
                route = value;
                if (value === "") {
                    //document.getElementById("stop_nos_div").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                            document.getElementById("stop_nos_div").innerHTML = xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("GET", "stopNos.php?r=" + value, true);
                    xmlhttp.send();
                }
            }

            function getLatLong(){
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        document.getElementById("coordinates").innerHTML = xmlhttp.responseText;
                        //initMap('51.060931', '-114.065158', '51.028872', '-114.187317');
                        //initMap('51.060931', '-114.065158', '51.028872', '-114.187317');
                        
                        stop_from_lat = document.getElementById("s1").innerHTML;
                        stop_from_long = document.getElementById("s2").innerHTML;
                        stop_to_lat = document.getElementById("s3").innerHTML;
                        stop_to_long = document.getElementById("s4").innerHTML;
                        
                        initMap(stop_from_lat, stop_from_long, stop_to_lat, stop_to_long);
                        
                        
                    }
                };
                xmlhttp.open("GET", "latLong.php?sf=" + stop_from + "&st=" + stop_to, true);
                xmlhttp.send();

                
            }

            function setDepartTime(value) {
                depart_time = value;
            }

            function setStopTo(value) {
                stop_to = value;
            }

            function submitPlannedTrip() {

                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                            //DO NOTHING
                            checkSameTrip();
                            shiftPanelLeft();
                        }
                    };
                    xmlhttp.open("GET", "submitTrip.php?r=" + route + "&sf=" + stop_from + "&dt=" + depart_time + "&st=" + stop_to, true);
                    xmlhttp.send();
            }
            
            function shiftPanelLeft(){
                // Hide the map on Submission
                document.getElementById('map-block').style.display = 'none';
                matchesFound = document.getElementById('matches-found-block');
                searchFields = document.getElementById('search_fields');
                
                searchFields.style.cssFloat = "left";
                searchFields.style.marginLeft = "50px";
                matchesFound.style.display = "block"; // Makes the results show up!
                matchesFound.style.marginRight = "50px";
            }
            
            function displayModal() {
                document.getElementByID('viewProfile').style.visibility = 'visible';
             }
             
        </script>

    </head>

    <body>
<?php

        


        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");


        $query1 = "select distinct route_short_name, route_long_name from routes";

        $result1 = mysqli_query($connection, $query1) or die(mysqli_error());



        date_default_timezone_set('America/Phoenix');
        $day = date('l');
        ?>

        
<?php
// Extracting employee creds from Login page (input names)
$email = $_POST['email'];
$hashed_pass = $_POST['hashed_pass'];

// Safe input manipulation
$email = stripslashes($email);
$hashed_pass = stripslashes($hashed_pass);

$sql = "SELECT *
          FROM users 
          WHERE (email='$email') AND (hashed_pass ='$hashed_pass');";

$result = mysqli_query($connection, $sql);

// Number for results into $count
$count = mysqli_num_rows($result);

// Look for 1 valid result
if ($count == 1) {

    $_SESSION['email'] = $email;

    echo $_SESSION['email'] . " Successfully connected";

    //header("location:companion-finder.php"); // Redirect page upon successful authentication
} else {

    echo "Invalid Username or Password";
    echo "<script> location.href = \"index.php\"; </script>";
}

?>

    <div class="logo"></div>
    
    <div id ="coordinates"></div>
    
    
    <div class="finder-content">
        
        <div class="transit-info-block" id = "search_fields">
            <h1>Route Information</h1>

            <input list="route_nos" placeholder="Train/Bus Route Number" id="route_no" onchange="getStopNos(this.value)">

                <datalist id="route_nos">
                    <?php
                    while ($row = mysqli_fetch_array($result1, MYSQLI_BOTH)) {
                        echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
                    }
                    ?>
                </datalist>

                <input list="stop_nos" placeholder="Train/Bus Stop Number" id="stop_from" onchange="getStopTimes(this.value, route)">
                <div id="stop_nos_div"></div>

                <input list="stop_times" placeholder="Train/Bus Departure Times" id="stop_departure_time" onchange="setDepartTime(this.value)">
                <div id="stop_times_div"></div>

                <input list="stop_nos" placeholder="Train/Bus Stop Number" id="stop_to" onchange="setStopTo(this.value)">

                
                
            <button onclick="getLatLong()">Show on Map</button>
            <button onclick="submitPlannedTrip()" onkeydown="submitPlannedTrip()">Find Commuting Companion!</button>
            
            
        </div>
        
        <div class="transit-info-block" id = 'matches-found-block'>
            <h1>Fellow Commuters</h1>
            <ul id="matches-found-list">
                <a href = "#viewProfile" onclick="displayModal()"><li id = "match-found-item" >First Commuter</li></a>
                <li id = "match-found-item">Second Commuter</li>    
                <li id = "match-found-item">Third Commuter</li>
                <li id = "match-found-item">Fourth Commuter</li>
            </ul>
        </div>
        
        
        <div class="transit-info-block" id ="map-block">
            <div id ='noMatchesFound'>No matches found</div>
            
            <script>
 
		function initMap(sf_lat, sf_long, st_lat, st_long) {
                    
                        // Hide fellow commuters panel
                        document.getElementById('matches-found-block').style.display = 'none';
                    
                        mapBlock = document.getElementById('map-block');
                        searchFields = document.getElementById('search_fields');

                        searchFields.style.cssFloat = "left";
                        mapBlock.style.display = "block"; // Makes the results show up!
                    
                        var src_lat = parseFloat(sf_lat);
			var src_lon = parseFloat(sf_long);
			var dst_lat = parseFloat(st_lat);
			var dst_lon = parseFloat(st_long);
                        
				
			//Check if value longitude and latitude is valid
			if( //Check the source longitude and latitude
				((src_lat >= parseFloat('-90.0') && src_lat <= parseFloat('90.0')) &&
				(src_lon >= parseFloat('-180.0') && src_lon <= parseFloat('180.0')))
				&&
				//Check the destination longitude and latitude
				((dst_lat >= parseFloat('-90.0') && dst_lat <= parseFloat('90.0')) &&
				(dst_lon >= parseFloat('-180.0') && dst_lon <= parseFloat('180.0')))
				) {
				//Initialize Google Map API and center it		
				var dir_display = new google.maps.DirectionsRenderer;
				var dir_service = new google.maps.DirectionsService;
				var map_div = document.getElementById('map-block');
				var map = new google.maps.Map(map_div, 
					{
						center: new google.maps.LatLng(src_lat, src_lon),
						zoom: 8
					}
				);
				dir_display.setMap(map);
					
				//Get route request
				var request = {
					origin: new google.maps.LatLng(src_lat, src_lon),
					destination: new google.maps.LatLng(dst_lat, dst_lon),
					travelMode: google.maps.TravelMode.TRANSIT
				};
				
				//Draw the route
				dir_service.route(request, function(result, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						dir_display.setDirections(result);
					} else {
						if(status == "ZERO_RESULTS")
							window.alert("No route found!");
						else
							window.alert("Direction request failed due to " + status);
						
						clear_boxes();
					}
				});				
			} else {
				//window.alert("Please provide required fields.");
				clear_boxes();
			}
		}
		
		function clear_boxes() {
			//Clear the textboxes
			document.getElementById('src_txt_lat').value = "";
			document.getElementById('dst_txt_lat').value = "";
			document.getElementById('src_txt_lon').value = "";
			document.getElementById('dst_txt_lon').value = "";
			
			//Set focus on first textbox
			document.getElementById('src_txt_lat').focus();
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer></script>
            
            
        </div>
        
    </div>
    

    <div id="viewProfile" class="modalDialog">
            <div>	
                <a href="#close" title="Close" class="close">&times</a>

                <div class="profile-block">

                    <div class="profilep-block">
                        <div id="profile-picture"><img id="profile_pic" src="images/profile-picture-placeholder.png" width=100 height=100></div>
                        <center><div id="profile-username" style="font-size: 25px"><b><br><?php echo $profile_name; ?></b></div></center>
                    </div>  
                    <br>
                    <div class="reputation-block">
                        <!-- Rating System -->
                    </div>
                    <br>
                    <div class="contact-block">
                        <b style="font-size: 18px">Email: </b><label style="font-size: 16px"><?php echo $profile_email; ?></label><br>
                        <b style="font-size: 18px">Phone Number: </b><label style="font-size: 16px"><?php echo $profile_phone; ?></label><br>
                    </div>
                    <div class="description-block">
                        <b style="font-size: 18px">About Myself:</b><br>
                        <div class="description-text"><label id="profile_desc" style="font-size: 16px; max-width: 100%; word-wrap: break-word"><?php echo $profile_description; ?></label><br></div>
                        
                    </div>
                    
                    
                    
                </div>
       
            </div>
        
        
        </div>



    </body>




</html>

