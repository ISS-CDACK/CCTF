
<?php
include('config.php');
require './includes/sanitizer.php';

session_start();
// $user_check = $_SESSION['login_user'];
$user_check = sanitizeInput($_SESSION['login_user']);
// echo($_SESSION['id']);

$user_id = substr(sanitizeInput($_SESSION['id']),0,3);
$saved_hash = substr(sanitizeInput($_SESSION['logid']),0,8);

// $user_check = sanitizeInput($_SESSION['login_user']);

// $ses_sql = mysqli_query($conn,"select email, name, status, role from users where email = '$user_check' ");
// $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

try{
    $sql = "select email, name, status, role from users where email = ?";
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
if (isset($_SESSION['login_user'])){
    if ($row['status'] =='true'){

        // $ses_sql = mysqli_query($conn,"SELECT ts_hash FROM login_logs WHERE users_id = ".$user_id.";");
        // $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

        try{
            $sql = "select s_id from scoreboard where user_id = ? and c_id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ii", $user_id, $challenge_id);
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
            $sql = "SELECT ts_hash FROM login_logs WHERE users_id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $user_id);
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

        $db_hash = $row['ts_hash'];
        if ($saved_hash == $db_hash){
            echo 'true';
        }
        else{
            echo 'old';
        }
    }
    elseif ($row['status'] =='false'){
        echo 'false';
    }
    else{
        echo 'error';
    }
}
else{
    echo 'error';
}
?>