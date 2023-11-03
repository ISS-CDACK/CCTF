

<div class="leaderboard">
    <h3>Leaderboard</h3>
    <table>
        <thead>
            <tr class="heading">
                <th>Rank</th>
                <th>Name</th>
                <th>Solved</th>
                <th>Score</th>
                <th>Last Submit</th>
            </tr>
        </thead>
        <tbody>

            <?php 

                $sql = "select @a:=@a+1 as rank, (select ts from scoreboard where user_id=sb.user_id order by ts DESC LIMIT 1) as ti, u.name as name, u.status as status, count(sb.c_id) as solved, sum(ch.score) as sscore from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id group by sb.user_id order by sscore desc, rank asc;";
                $result = mysqli_query($conn, $sql) or die(mysqli_error());
                $count = mysqli_num_rows($result);
                $i = 1;
                if ($count > 0) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo "<tr>";
                        // echo "<td>".$row["name"]."</td>";
                        if ($row["status"] == 'false'){
                            echo "<td><s>".$i++."</s></td>";
                            echo "<td><s>".$row["name"]." <small style='background-color:tomato;'>[blocked]</small></s></td>";
                            echo "<td><s>".$row["solved"]."</s></td>";
                            echo "<td><s>".$row["sscore"]."</s></td>";
                            echo "<td><s>".$row["ti"]."</s></td>";
                        }else{
                            echo "<td>".$i++."</td>";
                            echo "<td>".$row["name"]."</td>";
                            echo "<td>".$row["solved"]."</td>";
                            echo "<td>".$row["sscore"]."</td>";
                            echo "<td>".$row["ti"]."</td>";
                        }
                        echo "</tr>";
                    }

                }

            ?>
            
        </tbody>

    </table>
</div>


<?php
$date = date_default_timezone_set('Asia/Kolkata');
$today = date("F j, Y, g:i a");
// ,(time() - 60 * 2));
?>

<div class="last-update"></br>&emsp;&nbsp;Last Update at <span style="font-weight: bold;"> <?php echo $today ?> </span></div>

<?php
    $url1= '/dashboard.php?p=leaderboard';
    // $_SERVER['REQUEST_URI'];
    header("Refresh: 60; URL=$url1");
?>
