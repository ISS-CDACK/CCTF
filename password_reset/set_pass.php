<?php
include("../config.php");
// require '../../includes/sanitizer.php';

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
else{
    if (isset($_COOKIE['key'])){
        $decoded = base64_decode( urldecode( $_COOKIE['key'] ) );
        // unset($_COOKIE['key']);
        // setcookie('key', '', time() - 3600, '/password_reset');
    }
    else{
        if (isset($_COOKIE['key'])){
            unset($_COOKIE['key']);
            setcookie('key', '', time() - 3600, '/password_reset');
        }
        header('Location: ../forgot_pass.php?p=exp');
        die();
    }
}


$count=0;
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    $myKey = $decoded;
    // $myKey = sanitizeInput($decoded);
    $password = sanitizeInput($_POST['password']);
    if (!strlen($password) >= 3){
        // header('Location: ../success.php?p=changed');
        die();
    }
    // $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_Password = password_hash($password, PASSWORD_DEFAULT);

    // $sql = "SELECT `id` FROM `users` WHERE `special_key` = \"".$myKey."\"";

    try{
        $sql = "SELECT id FROM users WHERE special_key = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $myKey);
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



    // if(! $conn ) {
    //     die('Could not connect: ' . mysqli_error());
    // }

    // $result = mysqli_query($conn, $sql);
    // if(! $result ) {
    //     die('Could not get data: '.$sql . mysqli_error());
    // }

    // $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $user_id = $row['id'];

    // $count = mysqli_num_rows($result);
    if($count == 1) {
        // $sql = "UPDATE `users` SET `password` = \"".$hashed_Password."\" WHERE `users`.`id` = \"".$user_id."\"";
        // $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        try{
            $sql = "UPDATE users SET password = ?  WHERE users.id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("si",$hashed_Password, $user_id);
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
        try{
            $sql = "UPDATE special_keys, users SET special_keys.status='true', special_keys.use_time=CURRENT_TIMESTAMP, users.special_key=NULL WHERE special_keys.user_id = ? AND special_keys.key_value = ? AND users.id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("isi",$user_id, $myKey, $user_id);
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
        if (isset($_COOKIE['key'])) {
            unset($_COOKIE['key']);
            setcookie('key', '', time() - 3600, '/password_reset'); // empty value and old timestamp
        } 
        header('Location: ../success.php?p=changed');
        die();
        
        // if(! $result ) {
        //     die('Could not get data: '.$sql . mysqli_error());
        // }
        // else{
        //     // $sql = "UPDATE `users` SET `special_key` = NULL WHERE `users`.`id` = \"".$user_id."\"";
        //     $sql = "UPDATE special_keys, users SET special_keys.status='true', special_keys.use_time=CURRENT_TIMESTAMP, users.special_key=NULL WHERE special_keys.user_id= \"".$user_id."\" AND special_keys.key_value= \"".$myKey."\" AND users.id= \"".$user_id."\";";
        //     $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        //     if (isset($_COOKIE['key'])) {
        //         unset($_COOKIE['key']);
        //         setcookie('key', '', time() - 3600, '/password_reset'); // empty value and old timestamp
        //     }
            // header('Location: ../success.php?p=changed');
            // die();
        }
    // }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CDAC CTF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css" />
    <script src="../js/main.js"></script>
    <script src="../js/axios.min.js"></script>
    <link rel="stylesheet"
    href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">
    <link rel="icon" href="../images/flag.png">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
    <script src="../js/set_pass.js"></script>
  </head>


<style type ="text/css" >
.footer{ 
    position: fixed;     
    text-align: center;    
    bottom: 0px; 
    width: 100%;
    color: #d1b3ff;
}
</style>

<style>
#snackbar {
  visibility: hidden;
  min-width: 250px;
  /* margin: auto; */
  /* margin-left: -125px; */
  background-color: #ff4d4d;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  /* left: 50%; */
  bottom: 30px;
  font-size: 17px;
}

#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>



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
                <li><a href="" class="active">Set New Password</a></li>
                </ul>
            </div>
            <?php 
                include 'pass.php';
            ?>
        </div>
        <div id="snackbar">First</div>
    </div>
    <div class="footer"><b>Harry up!!</b> Session Timeout in <span id="counter">240</span> seconds</div>

<script>
function myFunction(s) {
  var x = document.getElementById("snackbar");
  var text = document.createTextNode(s);
  x.textContent = '';
  x.appendChild(text);
  x.className = "show";
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

const a = document.querySelector('a')
a.onclick = (e) => {
  e.preventDefault()
}

function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=0) {
        location.href = '../forgot_pass.php?p=exp';
    }
    if (parseInt(i.innerHTML)!=0) {
        i.innerHTML = parseInt(i.innerHTML)-1;
    }
}
setInterval(function(){ countdown(); },1000);
</script>
</body>

</html>
