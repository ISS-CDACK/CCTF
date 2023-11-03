<?php 
    include '../../admin_session.php';
    include '../../config.php';

    function sanitizeInputAD($val) {
        include '../../config.php';
        $sprey1 = mysqli_real_escape_string($conn,$val);
        $sprey2 = filter_var ($sprey1, FILTER_SANITIZE_STRING);
        $sprey3 = strip_tags($sprey2);
        $sprey4 = htmlspecialchars($sprey3);
        $sprey5 = trim($sprey4," ");
        return $sprey5;
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change-name'])) {

        if(isset($_POST['username'])) {
            $change_name = mysqli_real_escape_string($conn,$_POST['username']);
            $change_name_sanitazied = sanitizeInputAD( $change_name);

            // $sql = "update users set name = \"".$change_name_sanitazied."\" where id = ".$login_user_id;
            // echo $sql;
            // $result = mysqli_query($conn, $sql) or die(mysqli_error());
            // echo $result;
            // $countf = mysqli_num_rows($result);
            
            try{
                $sql = "update users set name = ? where id = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("si", $change_name_sanitazied, $login_user_id);
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

            header('Location: /admin/sudozone.php?p=setting');
        } else {
            echo "Invalid Parameter";
        }
    }else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['old-password']) && isset($_POST['new-password'])) {
        if(isset($_POST['old-password']) && isset($_POST['new-password'])) {
            $password = sanitizeInputAD( $_POST['new-password']);

            $change_Password = password_hash($password, PASSWORD_DEFAULT);

            $old_password = sanitizeInputAD($_POST['old-password']);

            // $sql = "select password from users where id = $login_user_id;";
            // $result = mysqli_query($conn, $sql) or die(mysqli_error());
            // $row = mysqli_fetch_array($result);
            

            try{
                $sql = "select password from users where id = ?";
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
            $row = $result->fetch_assoc();
            
            $count = mysqli_num_rows($result);


            if ($count == 1 ) {
                $raw_pass =  $row['password'];
                if (password_verify($old_password, $raw_pass)) {
                    // $sql = "update users set password = \"$change_Password\" where id = $login_user_id";
                    // $result = mysqli_query($conn, $sql) or die(mysqli_error());

                    try{
                        $sql = "update users set password = ? where id = ?";
                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("si", $change_Password, $login_user_id);
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
                        header('Location: ../../includes/pass_error.php?p=error');
                        die();   
                    }
                } else {
                    header('Location: ../../includes/pass_error.php?p=error');
                    die();                    // echo '<script type="text/javascript">','myToast.showError("Wrong Old Passwords", "null");','</script>';
                    // echo 'Invalid password.';
                }
            }
            else {
                header('Location: ../../includes/pass_error.php?p=error');
                die();    
            }
        } else {
            header('Location: ../../includes/pass_error.php?p=error');
            die();    
        }
    }else {
        die('error');
    }

?>