<div class="admin-leaderboard">
    <div class="container">
        <div class="toolbar">
            <h3>LeaderBoard</h3>
        </div>
        <table>
            <tr class="head">
                <th>Rank</th>
                <th>Name</th>
                <th>Score</th>
                <th>Solved</th>
                <th>Last Submit</th>
            </tr>

            <?php 

                $sql = "select @a:=@a+1 as rank, (select ts from scoreboard where user_id=sb.user_id order by ts DESC LIMIT 1) as ti, u.name as name, u.status as status, count(sb.c_id) as solved, sum(ch.score) as sscore from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id group by sb.user_id order by sscore desc, rank asc;";
                $result = mysqli_query($conn, $sql) or die(mysqli_error());
                $count = mysqli_num_rows($result);
                $i = 1;
                if ($count > 0) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo "<tr class='content'>";
                        // echo "<td>".$row["name"]."</td>";
                        if ($row["status"] == 'false'){
                            echo "<td><s>".$i++."</s></td>";
                            echo "<td><s>".$row["name"]." <small style='background-color:tomato;'>[blocked]</small></s></td>";
                            echo "<td><s>".$row["sscore"]."</s></td>";
                            echo "<td><s>".$row["solved"]."</s></td>";
                            echo "<td>".$row["ti"]."</td>";
                        }else{
                            echo "<td>".$i++."</td>";
                            echo "<td>".$row["name"]."</td>";
                            echo "<td>".$row["sscore"]."</td>";
                            echo "<td>".$row["solved"]."</td>";
                            echo "<td>".$row["ti"]."</td>";
                        }
                        echo "</tr>";
                    }
                }

            ?>
        </table>
    </div>

    <?php
$date = date_default_timezone_set('Asia/Kolkata');
$today = date("F j, Y, g:i a");
// ,(time() - 60 * 2));
?>

<div class="last-update" style='text-align: center; padding-top:20px;'>Last Update at <span style="font-weight: bold;"> <?php echo $today ?> </span></div>

</div>

<div id="modal-add-challenge" class="modal">
    <div class="modal-card">
        <h2>New Challenge</h2>
        <form action="admin/controllers/add_challenge.php" method="POST">
            <input type="text" name="title" placeholder="Challenge Title"/>
            <textarea name="description" placeholder="Challenge Description"></textarea>
            <div class="row">
                <select name="cat_id">
                    <option disabled selected>Choose category</option>
                    <?php 
                        $sql = "select cat.cat_id, cat.name from category cat order by cat.cat_id";
                        $result = mysqli_query($conn, $sql) or die(mysqli_error());
                        if (!$result) echo "ERROR";
                        $count = mysqli_num_rows($result);
                        if ($count > 0) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                echo "<option value=\"".$row["cat_id"]."\">".$row["name"]."</option>";
                            }
                        }
                    ?>
                </select>
                <input type="text" placeholder="Score" name="score" />
                <input type="text" placeholder="Flag" name="flag" />
            </div>
            <input type="submit" name="add_challenge" value="CREATE">
        </form>
        <button id="btn-modal-close"class="btn-close"><img src="images/close.svg"/></button>
    </div>
</div>

<?php
    $url1= '/admin.php?p=leaderboard';
    // $_SERVER['REQUEST_URI'];
    header("Refresh: 60; URL=$url1");
?>



<script src="js/modal.js"></script>
<script>
    Modal.init("modal-add-challenge", "btn-add-challenge", "btn-modal-close");
</script>