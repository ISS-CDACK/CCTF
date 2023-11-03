<?php
include("../config.php");
// require '../includes/sanitizer.php';
function sanitizeInput($val) {
    include("../config.php");
    $sprey1 = mysqli_real_escape_string($conn,$val);
    $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
    $sprey3 = strip_tags($sprey2);
    $sprey4 = htmlspecialchars($sprey3);
    $sprey5 = trim($sprey4," ");
    return $sprey5;
}

session_start();
if(isset($_SESSION['login_user'])){
    header("Location: ../dashboard.php");
    die();
}

if (isset($_COOKIE['key'])) {
    unset($_COOKIE['key']);
    setcookie('key', '', time() - 3600, '/password_reset');
}

$count=0;
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset-submit'])) {

    $myEmail = sanitizeInput($_POST['email']);
    // $myEmail = mysqli_real_escape_string($conn,$_POST['email']);
    $myKey = sanitizeInput($_POST['key']);
    // $myKey = mysqli_real_escape_string($conn,$_POST['key']);

    // $sql = "SELECT `id` FROM `users` WHERE `special_key` = \"".$myKey."\" AND `email` = \"".$myEmail."\"";

    // if(! $conn ) {
    //     die('Could not connect: ' . mysqli_error());
    // }

    // $result = mysqli_query($conn, $sql);

    // if(! $result ) {
    //     die('Could not get data: '.$sql . mysqli_error());
    // }

    // $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    try{
        $sql = "SELECT id FROM users WHERE special_key = ? AND email = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("ss",$myKey, $myEmail);
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
    $row = $result->fetch_assoc();

    $count = mysqli_num_rows($result);
    if($count == 1) {
        $str = $myKey;
        $encoded = urlencode( base64_encode($str) );
        setcookie('key', $encoded, time()+240, "/password_reset");  
        header("Location: set_pass.php");
        die ();
    }
    else{
        header('Location: ../success.php?p=key');
        die();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CCTF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css" />
    <script src="../js/main.js"></script>
    <script src="../js/axios.min.js"></script>
    <link rel="stylesheet"
    href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">
    <link rel="icon" href="../images/flag.png">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
    <script src="../js/block.js"></script>
  </head>
  
<body>
    <div class="background"></div>
    <div class="foreground"></div>
    <nav>
        <div class="nav-container">
            <h1>
                <img src="/images/cdaclogo.png" style="height: 90px; width: 90px; margin-left: 0.4em; margin-top: 0.2em;" />
            </h1>
        </div>
    </nav>
    <div class="main-container">
        <div class="login-card">
            <div class="tabs">
                <ul>
                <li><a href="" class="active">Reset Password</a></li>
                </ul>
            </div>
            <?php 
                include 'mail.php';
            ?>
        </div>
    </div>
</body>
<script>
    document.cookie = "pageload=0";
</script>
</html>