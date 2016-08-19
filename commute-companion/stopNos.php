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
        
        $r = $_GET['r'];
        str_replace("\"", "", $r);
        
        date_default_timezone_set('America/Phoenix');
        $day = date('l');

        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");

        $query2 = "select stop_id, stop_name from stops where stop_id in (select distinct stop_id from stop_times where trip_id in (select distinct trip_id from trips where route_id LIKE '" . $r . "-%' AND service_id IN (select service_id from calendar WHERE " . $day . "=1 AND start_date <= CURDATE() AND CURDATE() <= end_date)))";

        $result2 = mysqli_query($connection, $query2);

        echo "<datalist id=\"stop_nos\">";

        while ($row2 = mysqli_fetch_array($result2, MYSQLI_BOTH)) {
            echo "<option value=\"" . $row2[0] . "\">" . $row2[0] . " - " . $row2[1] . "</option>";
        }
        echo "</datalist>";
        mysqli_close($connection);
        ?>
    </body>
</html>