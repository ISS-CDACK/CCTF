<div class="challenge-category">


<style>
.zoom {
  transition: transform .2s;
}
.zoom:hover {
  -ms-transform: scale(1.200);
  -webkit-transform: scale(1.200);
  transform: scale(1.200); 
}
</style>

        <?php
            function sanitizeInput($val) {
                include './config.php';
                $sprey1 = mysqli_real_escape_string($conn,$val);
                $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
                $sprey3 = strip_tags($sprey2);
                $sprey4 = htmlspecialchars($sprey3);
                $sprey5 = trim($sprey4," ");
                return $sprey5;
            }
            $cookie_name = "data";
            $cookie_value = "eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJmbGFnIjoiQ0RBQ19DVEZfRkxBR3tVTUpZV05RWFpCfSJ9.dE-2V9JnE9Xec3vSJSjFLirLN1BYU_2iyr1vuSK0lbPjY9nZ3cG-4xc_Qf5kLEWgNm7-KUNySnZwF-vIfosQnQ";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 

            // $sql = "select c_id from scoreboard where user_id = ".sanitizeInput($login_user_id);
            // $result = mysqli_query($conn, $sql) or die(mysqli_error());

            try{
                $sql = "select c_id from scoreboard where user_id = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("i", $login_user_id);
                $stmt->execute();
                $result = $stmt->get_result();
            } catch(Exception $e){
                if ($debug_mode == true){
                    // echo $e;
                    die('debug: '.$e);
                }
                else{
                    echo 'error';
                    die();
                }
            }
            // $row = $result->fetch_assoc();

            $solved_id = array();
            while ($row = $result->fetch_assoc()) {
                array_push($solved_id, $row['c_id']);
            }

            $sql = "select ch.id, ch.title, ch.score, cat.name as cat_name from challenges as ch, category as cat where ch.cat_id = cat.cat_id ORDER BY ch.cat_id, ch.id";
            $result = mysqli_query($conn, $sql) or die(mysqli_error());
            $count = mysqli_num_rows($result);
            $prev_cat_name = "";
            
            $flag = 0;

            if ($count > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    if ($prev_cat_name != $row["cat_name"]) {
                        if ($flag == 1) {
                            echo "</div>";
                        }
                        $flag = 1;

                        $prev_cat_name = $row["cat_name"];

                        echo "<h3>".$row["cat_name"]."</h3>";
                        echo "<div class='card-container'>";
                    }

                    $isSolvedClass = in_array($row["id"], $solved_id) ? "solved" : "points";
                    echo "<div class='card zoom' data-id='".$row["id"]."'>";
                    echo "<p>".$row["title"]."</p>";
                    echo "<p class='$isSolvedClass'>".$row["score"]."</p>";
                    echo "</div>";
                }

                echo "</div>";

            }
        ?>
</div>


<div id="modal-display-challenge" class="modal">
    <div class="modal-card">
        <h2 id="challenge-id">Challenge </h2>
        <form id="solve-form" action="solve_challenge.php" method="POST">
            <h3 id="challenge-name" class="modal-title">Challenge Title</h3>
            <p id="challenge-desc" >
            </p>
            <div class="row">
                <input type="text" id="text-flag" placeholder="Flag"  autocomplete="off" name="flag" />
            </div>
            <input type="submit" id="btn-solve" name="add_challenge" value="SOLVE">
        </form>
        <button id="btn-modal-close" class="btn-close"><img src="images/close.png" widht="34" height="20"  / ></button>
    </div>
</div>

<script src="js/profile.js"></script>
<script src="js/modal.js"></script>
<script>
    let challengeModal = new ChallengeModal("modal-display-challenge", "card", "btn-modal-close", "btn-solve");
    challengeModal.init(<?php echo "\"$login_user_id\"" ?>);
</script>
