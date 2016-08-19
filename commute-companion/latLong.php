<!DOCTYPE html>
<?php
error_reporting(E_ERROR);
session_start();
?>

<html>
    <head>
    </head>
    <body>

        <?php
        
        $sf = $_GET['sf'];
        str_replace("\"", "", $sf);
        
        $st = $_GET['st'];
        str_replace("\"", "", $st);

        date_default_timezone_set('America/Phoenix');
        $day = date('l');

        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");

        $query3 = "select stop_lat, stop_lon from stops where stop_id=" . $sf . " ";
        
        $result3 = mysqli_query($connection, $query3);

        $row3 = mysqli_fetch_array($result3, MYSQLI_BOTH);
        
        $query4 = "select stop_lat, stop_lon from stops where stop_id=" . $st . " ";
        
        $result4 = mysqli_query($connection, $query4);
        
        $row4 = mysqli_fetch_array($result4, MYSQLI_BOTH);
        
        //echo "initMap('51.060931', '-114.065158', '51.028872', '-114.187317')";

        //echo " <script> stop_from_lat = '51.060931'; stop_from_long = '-114.065158'; stop_to_lat = '51.028872'; stop_to_long = '-114.187317'; </script>";
        
        echo "<textarea id = \"s1\" style='display : none'>" .$row3[0] . "</textarea>
            <textarea id = \"s2\" style='display : none'>" .$row3[1] . "</textarea>
            <textarea id = \"s3\" style='display : none'>" .$row4[0] . "</textarea>
            <textarea id = \"s4\" style='display : none'>" .$row4[1] . "</textarea>";
        
        
        mysqli_close($connection);
        ?>
    </body>
</html>