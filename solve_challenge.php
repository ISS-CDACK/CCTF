<?php
    include 'config.php';
    require './includes/sanitizer.php';
    $_POST = json_decode(file_get_contents('php://input'), true);
    //$_POST = json_decode($_POST["message"], TRUE);

    if (isset($_POST['cid']) && isset($_POST['flag']) && isset($_POST['userid'])) {
        
        // $cid = mysqli_real_escape_string($conn, $_POST['cid']);
        // $userId = mysqli_real_escape_string($conn, $_POST['userid']);
        // $flag = strip_tags( (filter_var ( $_POST['flag'], FILTER_SANITIZE_STRING) ) );
        
        $cid = substr(sanitizeInput($_POST['cid']),0,2);
        $userId = substr(sanitizeInput($_POST['userid']),0,3);
        $flag = substr(sanitizeInput($_POST['flag']),0,25);

        // $_POST['flag'];
        // die($flag);
        // strip_tags( (filter_var ( $change_name, FILTER_SANITIZE_STRING) ) );



        
        $response = new StdClass();
        // $sql = 'select * from scoreboard where c_id = '.$cid.' and user_id = '.$userId.';';
        // ($result = mysqli_query($conn, $sql)) or die(mysqli_error());
        // $count = mysqli_num_rows($result);

        try{
            $sql = "SELECT * FROM scoreboard WHERE c_id = ? and user_id = ?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ii", $cid, $userId);
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

        if ($count > 0) {
            $response->status = 200;
            $response->message = 'You have already solved this question';
            die(json_encode($response));
        }
        // $sql = 'select flag from challenges where id = '.$cid.';';
        // ($result = mysqli_query($conn, $sql)) or die(mysqli_error());
        // $count = mysqli_num_rows($result);
        try{
        $sql = "SELECT flag FROM challenges WHERE id = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $cid);
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
        
        if ($count > 0) {
            $row = $result->fetch_assoc();
            if ($row['flag'] == $flag) {
                $sql = 'insert into scoreboard(user_id, c_id) values('.$userId.','.$cid.');';
                ($result = mysqli_query($conn, $sql)) or die(mysqli_error());
                $response->status = 200;
                $response->message = 'Awesome! Correct Flag!';
                echo json_encode($response);
            } else {
                $response->status = 500;
                $response->message = 'Wrong Flag';
                echo json_encode($response);
            }
        } else {
            $response->status = 500;
            $response->message = 'Invalid Challenge';
            echo json_encode($response);
        }
    } else {
        $response->status = 500;
        $response->message = 'in sufficient parameters';
        echo json_encode($response);
    }
?>
