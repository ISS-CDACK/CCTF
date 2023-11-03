<?php 
    require '../../admin_session.php';
    require_once '../../config.php';

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {

        $cat_name = mysqli_real_escape_string($conn,$_POST['title']);


        // $sql = "insert into category (name) values (\"".$cat_name."\")";
        
        // if(! $conn ) {
        //     die('Could not connect: ' . mysqli_error());
        // }

        try{
            $sql = "insert into category (name) values (?)";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("s", $cat_name);
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

        // $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        
        header('Location: /admin.php?p=categories');
    }
?>
