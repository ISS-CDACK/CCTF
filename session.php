<?php
    include 'config.php';
    
    function sanitizeInputS($val) {
        include 'config.php';
        $sprey1 = mysqli_real_escape_string($conn,$val);
        $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
        $sprey3 = strip_tags($sprey2);
        $sprey4 = htmlspecialchars($sprey3);
        $sprey5 = trim($sprey4," ");
        return $sprey5;
    }

    session_start();

    // $user_check = $_SESSION['login_user'];
    $user_check = sanitizeInputS($_SESSION['login_user']);

    // $ses_sql = mysqli_query($conn,"select id, email, name, status, role from users where email = '$user_check'");
    // $row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);


    try{
        $sql = "select id, email, name, status, role from users where email = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $user_check);
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


    $login_session = $row['email'];
    $login_username = $row['name'];
    $login_user_id = $row['id'];

    if (isset($_SESSION['login_user'])) {
        if ($row['role'] == 'user') {
            if ($row['status'] == 'true') {
            } else {
                session_start();
                if (session_destroy()) {
                    header('Location: success.php?p=not_active');
                    die();
                }
            }
        } elseif ($row['role'] == 'admin') {
            if ($row['status'] == 'true') {
                header('Location: admin.php');
                die();
            } else {
                session_start();
                if (session_destroy()) {
                    header('Location: success.php?p=not_active');
                    die();
                }
            }
        } else {
            header('Location: login.php');
            die();
        }
    } else {
        header('Location: login.php');
        die();
    }
?>
