<?php
    if (isset($_COOKIE['key'])) {
        unset($_COOKIE['key']);
        setcookie('key', '', time() - 3600, '/password_reset'); // empty value and old timestamp
    }
    include 'config.php';
    require './includes/sanitizer.php';
    session_start();

    if (isset($_SESSION['login_user'])) {
        header('Location: dashboard.php');
        die();
    }
    $count = 0;
    // if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //     die(print_r($_POST));
    // }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login-submit'])) {
        // $myEmail = mysqli_real_escape_string($conn, $_POST['email']);
        $myEmail = sanitizeInput($_POST['email']);
        // $myPassword = mysqli_real_escape_string($conn, $_POST['password']);
        $myPassword = sanitizeInput($_POST['password']);
        $newHash_Password = password_hash($myPassword, PASSWORD_DEFAULT);

        if (!strlen($myPassword) >= 4 ){
            header('Location: /login.php');
            die();
        }


        try{
            $sql = "SELECT password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $myEmail);
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
        if ($count == 1) {
            $pass = $row['password'];
            if (password_verify($myPassword, $pass)) {
                try{
                    $sql = "SELECT * FROM users WHERE email = ?";
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("s", $myEmail);
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
                if ($count == 1) {
                    if ($row['status'] == 'true') {
                        $_SESSION['login_user'] = $myEmail;
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['name'] = $row['name'];
                        // $_SESSION[''] = $row['name'];
                        if ($row['role'] == 'user') {
                            $current_ts =  time().'+cdac_ctf_timestmap_hash';
                            $ts_hash =  hash('crc32b', $current_ts);
                            $u_id = $row['id'];
                            // $sql = "INSERT INTO login_logs (users_id, time_stamp, ts_hash) VALUES (".$u_id.", CURRENT_TIMESTAMP(), '".$ts_hash."') ON DUPLICATE KEY UPDATE time_stamp=CURRENT_TIMESTAMP(), ts_hash='".$ts_hash."';";
                            // // die($sql);
                            // if (!$conn) {
                            //     die('Could not connect: ' . mysqli_error());
                            // }
            
                            // $result = mysqli_query($conn, $sql);
            
                            // if (!$result) {
                            //     die('Could not get data: '.$sql. mysqli_error());
                            // }
                            try{
                                $sql = "INSERT INTO login_logs (users_id, time_stamp, ts_hash) VALUES (?, CURRENT_TIMESTAMP(), ?) ON DUPLICATE KEY UPDATE time_stamp=CURRENT_TIMESTAMP(), ts_hash=?";
                                $stmt = $conn->prepare($sql); 
                                $stmt->bind_param("sss", $u_id, $ts_hash, $ts_hash);
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


                            // add query here
                            $_SESSION['logid'] = $ts_hash;
                            header('location: dashboard.php');
                            // die('Logged in as user');
                        } elseif ($row['role'] == 'admin') {
                            $current_ts =  time().'+cdac_ctf_timestmap_hash';
                            $ts_hash =  hash('crc32b', $current_ts);
                            $u_id = $row['id'];

                            try{
                                $sql = "INSERT INTO login_logs (users_id, time_stamp, ts_hash) VALUES (?, CURRENT_TIMESTAMP(), ?) ON DUPLICATE KEY UPDATE time_stamp=CURRENT_TIMESTAMP(), ts_hash=?";
                                $stmt = $conn->prepare($sql); 
                                $stmt->bind_param("sss", $u_id, $ts_hash, $ts_hash);
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

                            // add query here
                            $_SESSION['logid'] = $ts_hash;
                            header('location: admin.php');
                            // die();
                        } else {
                            header('Location: success.php?p=login_failed');
                            // die('CONTACT ADMIN [DEBUG] INVALID ROLE');
                        }
                    } else {
                        header('Location: success.php?p=not_active');
                        die();
                    }
                } else {
                    //     header("location: login.php?p=login#error");
                    //     // echo("Invalid User Category");
                    //     echo '<script type="text/javascript">
                    //    window.onload = function () { alert("Invalid Login"); }';
                    header('Location: success.php?p=login_failed');
                    die();
                }
            } else {
                header('Location: success.php?p=login_failed');
                die();
            }
        } else {
            header('Location: success.php?p=login_failed');
            die();
        }
    } 
    elseif ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create-account']) ) {
        // die('test here');
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $password = sanitizeInput($_POST['password']);
        if (!strlen($password) >= 4 ){
            header('Location: /login.php?p=signup');
            die();
        }
        // $name = mysqli_real_escape_string($conn, $_POST['name']);
        // $email = mysqli_real_escape_string($conn, $_POST['email']);
        // $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashed_Password = password_hash($password, PASSWORD_DEFAULT);

        // echo('myPassword: ' . $password);
        // die('hashed_Password: ' . $hashed_Password);

        // $sql = "SELECT `email` from `users` where `email`='$email'";
        // $result = mysqli_query($conn, $sql);
        // $count = mysqli_num_rows($result);


        try{
            $sql = "SELECT email FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
        } catch(Exception $e){
            if ($debug_mode == true){
                // echo $e;
                die('debug: '.$e);
            }
            else{
                echo 'error';
            }
        }

        $row = $result->fetch_assoc();
        $count = mysqli_num_rows($result);
        // die($count);
        if ($count == 1) {
            header('Location: success.php?p=user_exits');
            die();
        } else {
            // $sql = "INSERT INTO `users`(`name`, `email`, `password`) VALUES('$name', '$email', '$hashed_Password')";
            // ($result = mysqli_query($conn, $sql)) or die(mysqli_error($conn));
            try{
                $sql = "INSERT INTO users(name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("sss", 
                $name, $email, $hashed_Password);
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
            
            header('Location: success.php?p=new_account');
            die();
        }
    }
    // elseif ( $_SERVER['REQUEST_METHOD'] == 'POST'){ 
    // die(print_r($_POST));}
        
    $LOGIN = 'login';
    $SIGNUP = 'signup';
    $current_page = null; //redirect to login page //set the current page to login or sign up based on the param
    if (isset($_GET['p']) && $_GET['p'] == 'login') {
        $current_page = $LOGIN;
    } elseif (isset($_GET['p']) && $_GET['p'] == 'signup') {
        $current_page = $SIGNUP;
    } else {
        // header('Location: success.php');
        header('Location: login.php?p=login');
        die();
    }
?>

<!DOCTYPE html>
<html>

    <head>
    <?php include 'includes/header.php';?>
    </head>

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
                        <?php if ($current_page == $LOGIN) {
                            echo '<li><a href="login.php?p=login" class="active">Login</a></li>';
                            echo '<li><a href="login.php?p=signup">Sign Up</a></li>';
                        } elseif ($current_page == $SIGNUP) {
                            echo '<li><a href="login.php?p=login">Login</a></li>';
                            echo '<li><a href="login.php?p=signup"  class="active">Sign Up</a></li>';
                        } ?>
                    </ul>
                </div>
                <?php if ($current_page == $LOGIN) {
                    include 'includes/login.php';
                } elseif ($current_page == $SIGNUP) {
                    include 'includes/signup.php';
                } ?>
            </div>
            <div id="snackbar">First</div>
        </div>
    <script>
        function myFunction(s) {
        var x = document.getElementById("snackbar");
        var text = document.createTextNode(s);
        x.textContent = '';
        x.appendChild(text);
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    </script>
    </body>
</html>