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
        $e = $_SESSION['email'];
        str_replace("\"", "", $e);

        $connection = mysqli_connect("localhost", "root", "", "calgary_com_com") or die("Whoops!");


        $sql1 = "SELECT *
          FROM planned_trips
          WHERE (user_id = '$e');";

        $result1 = mysqli_query($connection, $sql1);
        $row1 = mysqli_fetch_array($result1, MYSQLI_BOTH);

        $sql2 = "SELECT user_id
          FROM planned_trips
          WHERE (user_id != '" . $row1[0] . "') AND (route_no ='" . $row1[1] . "') AND (stop_from ='" . $row1[2] . "') AND"
                . "(stop_depart_time ='" . $row1[3] . "') AND (stop_to ='" . $row1[4] . "')" . " AND (submit_date ='" . $row1[5] . "');";


        $result2 = mysqli_query($connection, $sql2);

        // Number for results into $count
        $count = mysqli_num_rows($result2);

        // Look for 1 valid result
        if ($count >= 1) {
            echo "They're headed that way too!";
            echo "<h1>Fellow Commuters</h1>";
            echo "<ul id=\"matches-found-list\">";


            $i = 1;

            while ($row2 = mysqli_fetch_array($result2, MYSQLI_BOTH)) {
                $sql_t = "select * from users where email = '" . $row2[0] . "'";
                $result_t = mysqli_query($connection, $sql_t);
                $row_t = mysqli_fetch_array($result_t, MYSQLI_BOTH);
                $i += 1;
                echo "<a href = \"#viewProfile" . $i . "\" onclick=\"displayModal()\"><li id = \"match-found-item\" >" . $row_t[2] . " " . $row_t[3] . "</li></a>";
                echo "<div id=\"viewProfile" . $i . "\" class=\"modalDialog\">";
                echo "<div>";
                echo "<a href=\"#close\" title=\"Close\" class=\"close\">&times</a>";

                echo "<div class=\"profile-block\">";

                echo "<div class=\"profilep-block\">";
                echo "<div id=\"profile-picture\"><img id=\"profile_pic\" src=\"images/profile-picture-placeholder.png\" width=100 height=100></div>";
                echo "<center><div id=\"profile-username\" style=\"font-size: 25px\"><b><br>" . $row_t[2] . " " . $row_t[3] . "</b></div></center>";
                echo "</div><br>";
                echo "<div class=\"description-block\">";
                echo "    <b style=\"font-size: 18px\">About Myself:</b><br>";
                echo "    <div class=\"description-text\"><label id=\"profile_desc\" style=\"font-size: 16px; max-width: 100%; word-wrap: break-word\">" . $row_t[8] . "</label><br></div>";
                echo "</div>";
                
                echo "<div class =\"msg\">Send An Invite</div>";
                echo "</div>";

                echo "</div>";
                echo "</div>";
                
                
            }
            echo "</ul>";
        }
        else
        {
            echo "No matches found";
        }

        mysqli_close($connection);
        ?>
    </body>
</html>