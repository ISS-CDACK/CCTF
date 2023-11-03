<?php
    include 'config.php';
    // require './includes/sanitizer.php';
    
    $_POST = json_decode(file_get_contents('php://input'), true);

    if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cid']) && isset($_POST['userid']) ) {
        // $user_id = $_POST['userid'];
        // $challenge_id = $_POST['cid'];
        $user_id = $_POST['userid'];
        $challenge_id = $_POST['cid'];
        // die($user_id);

        // $sql = "select s_id from scoreboard where user_id = $user_id and c_id = $challenge_id";
        // ($result = mysqli_query($conn, $sql)) or die(mysqli_error());

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
        $count = mysqli_num_rows($result);

        $isSolved = $count != 0 ? true : false;

        // $sql = 'select title, description, score from challenges where id = '.($_POST['cid']).';';
        // ($result = mysqli_query($conn, $sql)) or die(mysqli_error());

        // if (!$result) {
        //     echo 'ERROR';
        // }
        // $count = mysqli_num_rows($result);

        $chal_id = $_POST['cid'];


        try{
            $sql = "select title, description, score from challenges where id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $chal_id);
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
        if (!$result) {
            echo 'ERROR';
        }

        $count = mysqli_num_rows($result);



        $obj = new StdClass();

        if ($count != 1) {
            $obj->message = 'ERROR';
            die(json_encode($obj));
        } else {
            // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $row = $result->fetch_assoc();

            $obj->title = $row['title'];
            $obj->description = $row['description'];
            $obj->score = $row['score'];
            $obj->isSolved = $isSolved;

            echo json_encode($obj);
            die();
        }
    } else {
        die('ERROR');
    }
?>
