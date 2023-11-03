<?php 
    include '../session.php';
    include '../config.php';

    function sanitizeInputT($val) {
        include '../config.php';
        $sprey1 = mysqli_real_escape_string($conn,$val);
        $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
        $sprey3 = strip_tags($sprey2);
        $sprey4 = htmlspecialchars($sprey3);
        $sprey5 = trim($sprey4," ");
        return $sprey5;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change-name'])) {

        if(isset($_POST['username']) && strlen($_POST['username'])>=3) {
            $change_name = mysqli_real_escape_string($conn,$_POST['username']);
            // $change_name_sanitazied = strip_tags( (filter_var ( $change_name, FILTER_SANITIZE_STRING) ) );
            $change_name_sanitazied = sanitizeInputT($change_name);
            $l_u_i = sanitizeInputT($login_user_id);
            // $sql = "update users set name = \"".$change_name_sanitazied."\" where id = ".sanitizeInputT($login_user_id);
            try{
                $sql = "update users set name = ? where id = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("si", $change_name_sanitazied, $l_u_i);
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
            // $count = mysqli_num_rows($result);
            
            // echo $sql;
            // $result = mysqli_query($conn, $sql) or die(mysqli_error());
            // echo $result;
            // $countf = mysqli_num_rows($result);
            header('Location: /dashboard.php?p=settings');
        } else {
            echo "Invalid Parameter";
        }
    }else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['old-password']) && isset($_POST['new-password'])) {
        if(isset($_POST['old-password']) && isset($_POST['new-password'])) {
            $password = sanitizeInputT($_POST['new-password']);

            $change_Password = password_hash($password, PASSWORD_DEFAULT);

            $old_password = sanitizeInputT($_POST['old-password']);

            // $sql = "select password from users where id = $login_user_id;";
            // $result = mysqli_query($conn, $sql) or die(mysqli_error());


            $l_u_i2 = sanitizeInputT($login_user_id);

            try{
                $sql = "select password from users where id = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("i", $l_u_i2);
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

            $count = mysqli_num_rows($result);
            $row = $result->fetch_assoc();
            // $row = mysqli_fetch_array($result);
            if ($count == 1 ) {
                $raw_pass =  $row['password'];
                if (password_verify($old_password, $raw_pass)) {
                    $l_u_i3 = sanitizeInputT($login_user_id);
                    // $sql = "update users set password = \"$change_Password\" where id = $login_user_id";
                    // $result = mysqli_query($conn, $sql) or die(mysqli_error());
                    try{
                        $sql = "update users set password = ?  where id = ? ";
                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("si", $change_Password, $l_u_i2);
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
                    // echo ($result);
                    // $count = mysqli_num_rows($result);
                    // echo($count);
                    // die();
                    if (empty($result)){
                        if (session_destroy()) {
                            header('Location: /success.php?p=changed');
                            die(); 
                         }
                         else{
                            // header('Location: /includes/pass_error.php?p=error');
                            header('Location: ../../includes/pass_error.php?p=error');
                            die(); 
                         }
                    }
                    else{
                        // myToast.showError("Passwords doesn't match", null);
                        // echo '<script type="text/javascript">','myToast.showError("Wrong Old Passwords", "null");','</script>';
                        // header('Location: /includes/pass_error.php?p=error');
                        header('Location: pass_error.php?p=error');
                        die();   
                    }
                } else {
                    header('Location: pass_error.php?p=error');
                    die();                    // echo '<script type="text/javascript">','myToast.showError("Wrong Old Passwords", "null");','</script>';
                    // echo 'Invalid password.';
                }
            }
            else {
                header('Location: pass_error.php?p=error');
                die();    
            }
        } else {
            header('Location: pass_error.php?p=error');
            die();    
        }
    }else {
        die('error');
    }

?>