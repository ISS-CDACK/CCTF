<?php
    if (isset($_COOKIE['key'])) {
        unset($_COOKIE['key']);
        setcookie('key', '', time() - 3600, '/password_reset'); // empty value and old timestamp
    }
    include 'config.php';
    if (!$ldap_connection){
        header("Location: /login.php", true, 302);
        header("Connection: close");
        exit;
    }
    require './includes/sanitizer.php';
    session_start();

    if (isset($_SESSION['login_user'])) {
        header('Location: dashboard.php');
        die();
    }

    function findArrayElement($array, $email)
    {
        foreach ($array as $key => $element) {
            if (isset($element["mail"]["count"]) && $element["mail"]["count"] === 1 && $element["mail"][0] === $email) {
                return $key;
            }
        }
        return false;
    }

    function login_user ($email, $username) {
        include 'config.php';
        $myEmail = $email;
        try{
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $myEmail);
            $stmt->execute();
            $result = $stmt->get_result();
        } catch(Exception $e){
            if ($debug_mode == true){
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
                if ($row['role'] == 'user') {
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
                            die('debug: '.$e);
                        }
                        else{
                            echo 'error';
                            die();
                        }
                    }

                    $_SESSION['logid'] = $ts_hash;
                    header('location: dashboard.php');
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
                            die('debug: '.$e);
                        }
                        else{
                            echo 'error';
                            die();
                        }
                    }

                    $_SESSION['logid'] = $ts_hash;
                    header('location: admin.php');
                } else {
                    header('Location: success.php?p=login_failed');
                }
            } else {
                header('Location: success.php?p=not_active');
                die();
            }
        }
    }



    $count = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login-submit'])) {
        $myEmail = sanitizeInput($_POST['email']);
        $myPassword = $_POST['password'];

        if (!strlen($myPassword) >= 4 ){
            header('Location: /login_ldap.php');
            die();
        }

        $ldapconn = ldap_connect($ldap_hostname, $ldapPort);

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, $ldap_protocol);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // recommended for W2K3
        if ($ldap_uft8) {
            $ldapusername = utf8_encode($myEmail);
            $ldappassword = utf8_encode($myPassword);
        }
        else {
            $ldapusername = $myEmail;
            $ldappassword = $myPassword;
        }

        if ($lbind = ldap_bind($ldapconn, $ldap_rootDN, $ldap_root_password)) {

            $searchResults = ldap_search($ldapconn, $ldapBaseDn, $ldap_filter);
            $entries = ldap_get_entries($ldapconn, $searchResults);
            $empID = findArrayElement($entries, $ldapusername);

            if ($entries['count'] > 0) {
                $ldapUserDN = $entries[$empID]['dn'];
                if (@ldap_bind($ldapconn, $ldapUserDN, $ldappassword)) {
                    $userMail = $entries[$empID]['mail'][0];
                    $givenName = $entries[$empID]['givenname'][0];

                    // Login user
                    try{
                        $sql = "SELECT password FROM users WHERE email = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userMail);
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
                    if ($count != 1){
                        try{
                            $sql2 = "INSERT INTO users(name, email) VALUES (?, ?)";
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->bind_param("ss", $givenName, $userMail);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
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
                        login_user($userMail, $givenName);
                    }elseif ($count == 1){
                        login_user($userMail, $givenName);
                    }
                } else {
                    header('Location: success.php?p=login_failed');
                    exit;
                }
            }
            else {
                header('Location: success.php?p=login_failed');
                exit;
            }
            @ldap_unbind($ldapconn);
        }
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
                        <li><a class="active" style="cursor: default;">Login</a></li>
                    </ul>
                </div>
                <?php include 'includes/login.php'; ?>
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