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
        
        
        $s = $_GET['s'];
        str_replace("\"", "", $s);

        $r = $_GET['r'];
        str_replace("\"", "", $r);

        date_default_timezone_set('America/Phoenix');
        $day = date('l');

        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");

        $query3 = "select departure_time from stop_times where departure_time >= CURTIME() AND stop_id=" . $s . " AND trip_id in (select trip_id from trips where route_id LIKE '" . $r . "-%' AND service_id IN (select service_id from calendar WHERE " . $day . "=1 AND start_date <= CURDATE() AND CURDATE() <= end_date)) ORDER BY departure_time";
        
        $result3 = mysqli_query($connection, $query3);

        $row3 = mysqli_fetch_array($result3, MYSQLI_BOTH);
        
        echo "<datalist id=\"stop_times\">";

        while ($row3 = mysqli_fetch_array($result3, MYSQLI_BOTH)) {
            echo "<option value=\"" . $row3[0] . "\" >";
        }
        

        
        echo "</datalist>"; 
        mysqli_close($connection);
        ?>
    </body>
</html>