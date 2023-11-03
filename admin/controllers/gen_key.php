<?php
include '../../config.php';

$count = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gen-key'])) {
    $count2 = 0;
    $myEmail = mysqli_real_escape_string($conn, $_POST['email']);

    // $sql = "SELECT `id` FROM `users` WHERE `email` = \"".$myEmail."\"";

    // if (!$conn) {
    //     die('Could not connect: ' . mysqli_error());
    // }

    // $result = mysqli_query($conn, $sql);

    // if (!$result) {
    //     die('Could not get data: ' . $sql . mysqli_error());
    // }

    // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    try{
        $sql = "SELECT id FROM users WHERE email = ?";
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
    
    if($count == 1){
        $user_id = $row['id'];
        $count2 = 0; 
        // $sql = "SELECT `email` FROM `users` WHERE `id` = \"".$user_id."\" AND `special_key` is NULL";
        
        // if (!$conn) {
        //     die('Could not connect: ' . mysqli_error());
        // }
        // $result = mysqli_query($conn, $sql);
    
        // if (!$result) {
        //     die('Could not get data: ' . $sql . mysqli_error());
        // }

        try{
            $sql = "SELECT email FROM users WHERE id = ? AND special_key is NULL";
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
        
        $count2 = mysqli_num_rows($result);
        $row = $result->fetch_assoc();
        // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if(is_null($row)){
            header('Location: ../sudozone.php?p=keypage&&err=key_active');
            die();
            // die('email found but not null');
        }
        else{
            $count3 = 0;
            $length = 15;
            $used_symbols = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
            $gen_key =  substr(str_shuffle($used_symbols), 0, $length);
            $final_key = 'CDAC-K_'.$gen_key;
            
            // $sql = "UPDATE `users` SET `special_key` = \"".$final_key."\" WHERE `id` = \"".$user_id."\" AND `special_key` IS NULL";
            // if (!$conn) {
            //     die('Could not connect: ' . mysqli_error());
            // }
            // $result = mysqli_query($conn, $sql);

            try{
                $sql = "UPDATE users SET special_key = ? WHERE id = ? AND special_key IS NULL";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("si", $final_key, $user_id );
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

            // $sql = "INSERT INTO `special_keys` (`user_id`, `key_value`) VALUES (\"".$user_id."\", \"".$final_key."\");";
            // if (!$conn) {
            //     die('Could not connect: ' . mysqli_error());
            // }
            // $result = mysqli_query($conn, $sql);
            

            try{
                $sql = "INSERT INTO special_keys (user_id, key_value) VALUES (?,?)";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("is", $user_id, $final_key );
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



            if (!empty($result)) {
                die('wrie to special_keys failed');
            }
            else{
                header('Location: ../sudozone.php?p=keypage');
                die();
                // die('wrie to special_keys');
            }
        }
    }
    else{
        header('Location: ../sudozone.php?p=keypage&&err=mismatch');
        die();
        // die('email dont match');
    }
}
?>