<?php
include '../../admin_session.php';

if ( isset($_GET['id']) && isset($_GET['state'])) {
    $id = $_GET['id'];
    $state = $_GET['state'];
    include "../../config.php"; 
    if ( $_GET['id'] === 'all' ){ 
        // $sql = "UPDATE `users` SET `status` = \"".$state."\" WHERE users.role = 'user';";
        // $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        try{
            $sql = "UPDATE users SET status = ? WHERE role = 'user'";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $state);
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
        header('Location: ../sudozone.php?p=permission');
    }
    // $sql = "UPDATE users SET users.status= \"".$state."\" WHERE users.id= \"".$id."\";";
    // $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    try{
        $sql = "UPDATE users SET status= ? WHERE id = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("si", $state, $id);
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

    header('Location: ../sudozone.php?p=permission&i='.$id);
    die();
 }
 else{
    header('Location: ../../admin.php?p=dashboard');
    die();
 }
// $sql = "UPDATE users, users SET users.sta
?>

