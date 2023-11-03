<?php 
    require '../../admin_session.php';
    require_once '../../config.php';

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_challenge'])) {

        $ch_name = mysqli_real_escape_string($conn,$_POST['title']);
        $ch_desc = mysqli_real_escape_string($conn,$_POST['description']);
        $ch_cat_id = mysqli_real_escape_string($conn,$_POST['cat_id']);
        $ch_score = mysqli_real_escape_string($conn,$_POST['score']);
        $ch_flag = mysqli_real_escape_string($conn,$_POST['flag']);


        // $sql = "insert into challenges (title, description, score, cat_id, flag)";
        // $sql = $sql." values (\"$ch_name\", \"$ch_desc\", $ch_score, $ch_cat_id, \"$ch_flag\");";
        
        // if(! $conn ) {
        //     die('Could not connect: ' . mysqli_error());
        // }

        // $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));



        try{
            $sql = "insert into challenges (title, description, score, cat_id, flag) values (?,?,?,?,?)";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("sssis", $ch_name, $ch_desc, $ch_score, $ch_cat_id, $ch_flag);
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
        
        header('Location: /admin.php?p=challenges');
    }
?>
