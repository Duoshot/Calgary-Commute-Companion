<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
    </head>
    <body>

        <?php
        $e = $_SESSION['email'];
        str_replace("\"", "", $e);
        
        $r = $_GET['r'];
        str_replace("\"", "", $r);

        $sf = $_GET['sf'];
        str_replace("\"", "", $sf);

        $dt = $_GET['dt'];
        str_replace("\"", "", $dt);

        $st = $_GET['st'];
        str_replace("\"", "", $st);

        date_default_timezone_set('America/Phoenix');
        $day = date('YYYY-mm-ss');
        $time = '13:61:11';

        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");

        $delete_query = "DELETE FROM planned_trips WHERE user_id='" . $e . "'";
        
        mysqli_query($connection, $delete_query);
        
        $insert_query = "INSERT INTO planned_trips(user_id,route_no,stop_from,stop_depart_time,stop_to,submit_date,submit_time) "
                . "VALUES(\"" .
                htmlspecialchars($e) . "\",\"" .
                htmlspecialchars($r) . "\",\"" .
                htmlspecialchars($sf) . "\",\"" .
                htmlspecialchars($dt) . "\",\"" .
                htmlspecialchars($st) . "\",\"" .
                htmlspecialchars($day) . "\",\"" .
                htmlspecialchars($time) . "\")";

        mysqli_query($connection, $insert_query);


        mysqli_close($connection);
        ?>
    </body>
</html>