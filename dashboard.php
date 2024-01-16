<?php
    include 'session.php';
?>
<!DOCTYPE html>
<html>

<?php
include 'includes/header.php';
include 'includes/comp_time.php';

?>
<?php
    $CHALLENGES = "challenges";
    $LEADERBOARD = "leaderboard";
    $SETTINGS = "settings";

    $current_page = $CHALLENGES;

    if (isset($_GET["p"]) && $_GET["p"] == $LEADERBOARD) {
        $current_page = $LEADERBOARD;
    } else if (isset($_GET["p"]) && $_GET["p"] == $CHALLENGES){
        $current_page = $CHALLENGES;
    } else if (isset($_GET["p"]) && $_GET["p"] == $SETTINGS && (!$ldap_connection)){
        $current_page = $SETTINGS;
    } else {
        header('Location: dashboard.php?p=challenges');
        die();
    }
?>

<body>

<?php
    $t=time();
    $start_time = $ctf_start_time;
    $end_time = $ctf_end_time;

    $user_score = 0;
    $user_solve = 0;
    $users_count = 0;
    $challenges_count = 0;
    $user_rank = 0;

    if ($t >= $start_time && $t <= $end_time){
        $comp_state = 'going';
    }
    elseif ($t <= $start_time){
        $comp_state = 'upcoming';
    }
    elseif ($t >= $end_time){
        $comp_state = 'end';
    }
    // echo($comp_state);
    if ($comp_state == 'going'){
        $sql = "select @a:=@a+1 as rank, u.id as user_id, (select ts from scoreboard where user_id=sb.user_id order by ts DESC LIMIT 1) as ti, u.name as name, u.status as status, count(sb.c_id) as solved, sum(ch.score) as sscore from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id group by sb.user_id order by sscore desc, rank asc;";
        $result = mysqli_query($conn, $sql) or die(mysqli_error());
        $count = mysqli_num_rows($result);
        $z = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $z=$z+1;
        }
        $sql = "select @a:=@a+1 as rank, u.id as user_id, (select ts from scoreboard where user_id=sb.user_id order by ts DESC LIMIT 1) as ti, u.name as name, u.status as status, count(sb.c_id) as solved, sum(ch.score) as sscore from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id group by sb.user_id order by sscore desc, rank asc;";
        $result = mysqli_query($conn, $sql) or die(mysqli_error());
        $count = mysqli_num_rows($result);
        $i = 1;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if ( $row['user_id'] == $login_user_id ){
                $user_score = $row['sscore'] ;
                $user_solve = $row['solved'];
                break;
            }
            else{
                $i=$i+1;
            }
        }
        if ($i > $z){
            $user_rank = 0;
        }
        else{
            $user_rank = $i;
        }

        $sql = "select count(u.id) as u_count from  users u WHERE role = 'user' AND status = 'true'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error());
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $users_count = $row['u_count'];
        }

        $sql = "select count(ch.id) as ch_count from  challenges ch";
        $result = mysqli_query($conn, $sql) or die(mysqli_error());
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $challenges_count = $row['ch_count'];
        }
    }
?>
<div class="dash-container">
    <div class="dash-side-nav">
        <h2>CDACK CTF Challenge</h2>
        <p class="nav-username"  style="font-size: 16px;"><?php echo (ucwords($login_username)); echo'<br>('.$login_session.')'; ?></p>
        <div class="score">
            <h1 class="score"><?php echo $user_score ?></h1>
        </div>
        <div class="status">
            <div class="col">
                <h3><?php echo "$user_solve / $challenges_count" ?></h3>
                <p>Solved</p>
            </div>
            <div class="col">
                <h3><?php echo "$user_rank / $users_count" ?></h3>
                <p>Rank</p>
            </div>
        </div>
        <?php
            if ($comp_state == 'going'){
                echo'<p class="nav-username" style="margin-top: 15px; font-size: 14px;">Time Remaning<br><span id="hours">!</span><span id="mins">!</span><span id="secs">!</span></p>';
            }
        ?>
		<div class="links">
            <a href="about-us.html">About Us</a>
            <a href="contact-us.php">Contact Us</a>
		</div>
    </div>
    <div class="dash-content">
        <div class="dash-nav">
            <div class="tabs">
                <ul>
                    <?php
                        if ($current_page == $CHALLENGES) {
                            echo "<li><a href='dashboard.php?p=challenges' class='active'>Challenges</a></li>";
                            echo "<li><a href='dashboard.php?p=leaderboard'>Leaderboard</a></li>";
                            if (!$ldap_connection){
                                echo "<li><a href='dashboard.php?p=settings'>Settings</a></li>";
                            }
                        } else if($current_page == $LEADERBOARD) {
                            echo "<li><a href='dashboard.php?p=challenges'>Challenges</a></li>";
                            echo "<li><a href='dashboard.php?p=leaderboard'  class='active'>Leaderboard</a></li>";
                            if (!$ldap_connection){
                                echo "<li><a href='dashboard.php?p=settings'>Settings</a></li>";
                            }
                        } else if($current_page == $SETTINGS) {
                            echo "<li><a href='dashboard.php?p=challenges'>Challenges</a></li>";
                            echo "<li><a href='dashboard.php?p=leaderboard'>Leaderboard</a></li>";
                            if (!$ldap_connection){
                                echo "<li><a href='dashboard.php?p=settings' class='active'>Settings</a></li>";
                            }
                        }
                    ?>
                </ul>
            </div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <div class="dash-challenge-container">
            <?php
                if ($current_page == $CHALLENGES) {
                    if ($comp_state == 'going'){
                        include 'includes/challenges.php';
                    }
                    else{
                        include 'includes/clocktimer.php';
                    }
                } else if ($current_page == $LEADERBOARD){
                    include 'includes/leaderboard.php';
                } else if ($current_page == $SETTINGS && !$ldap_connection) {
                    include 'includes/settings.php';
                }
            ?>
        </div>
    </div>

</div>

<div class="toast" id="toast">
    <h3 id="message">Error</h3>
</div>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    $.ajax({
        url: '/files/card.png',
        type: 'get',
        dataType: 'html',
        async: false,
        crossDomain: 'true',
        success: function(data, status) {
            // console.log("Status: "+status+"\nData: "+data);
            result = data;

            // /* creating image assuming data is the url of image */
            // var img = $('<img id="image_id">');
            // img.attr('src', data);
            // img.appendTo('#id_of_element_where_you_want_to_add_image');
        }
});
</script>


<script>
let myToast = new Toast();
myToast.init(document.getElementById("toast"));


var ctf_start_js = <?php echo $end_time;?>*1000;
var php_server_time =  <?php date_default_timezone_set('Asia/Kolkata');echo (time()*1000);?>

var inactivityTime = function () {
    var time;
    window.onload = resetTimer;
    // DOM Events
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;

    function logout() {
        alert("You are logged out, for inactivity.")
        location.href = '/logout.php'
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 1800000)
        // 1000 milliseconds = 1 second
    }
};



// 120000
setInterval("yourAjaxCall()",10000);
function yourAjaxCall(){
    $.ajax({    //create an ajax request to display.php
        type: "GET",
        url: "get_user_state.php",
        dataType: "html",   //expect html to be returned
        success: function(response){
            // $("#responsecontainer").html(response);
            text = response;
            string = text.replace(/^(?=\n)$|^\s*|\s*$|\n\n+/gm, "")
            if (string == 'false'){
                alert('You are banned from the CTF. Please contact ADMIN');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }
            else if (string == 'old'){
                alert('This is old sessions you are logging out...');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }
            else if (string == 'error'){
                alert('You are banned from the CTF. Please contact ADMIN');
                window.location.replace("/logout.php");
                $(document).ajaxStop();
            }
        }
    });
}

window.onload = function() {
  inactivityTime();
  yourAjaxCall();
}

</script>


<?php
// if ($current_page == $CHALLENGES) {
    if ($comp_state == 'going'){
        echo '<script src="/js/timer.js"></script>';
    }
// }
?>


</body>
</html>
