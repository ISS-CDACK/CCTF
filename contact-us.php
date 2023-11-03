<?php 
	include 'config.php';
	require './includes/sanitizer.php';

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {

		$visitor_name=$_POST['name'];
		$visitor_name_sanitazied = mysqli_real_escape_string($conn, $visitor_name);

		$visitor_email=$_POST['email'];
		$visitor_email_sanitazied = mysqli_real_escape_string($conn, $visitor_email);

		$visitor_message=$_POST['message'];
		$visitor_message_sanitazied = mysqli_real_escape_string($conn, $visitor_message);



		$visitor_name_sanitazied_final = sanitizeInput($visitor_name_sanitazied);
		// strip_tags( (filter_var ( $visitor_name_sanitazied, FILTER_SANITIZE_STRING) ) );
		$visitor_email_sanitazied_final = sanitizeInput($visitor_email_sanitazied);
		// strip_tags( (filter_var ( $visitor_email_sanitazied, FILTER_SANITIZE_STRING) ) );
		$visitor_message_sanitazied_final = sanitizeInput($visitor_message_sanitazied);
		// strip_tags( (filter_var ( $visitor_message_sanitazied, FILTER_SANITIZE_STRING) ) );


		// $sql="INSERT INTO visitors VALUES(0,'$visitor_name_sanitazied_final','$visitor_email_sanitazied_final','$visitor_message_sanitazied_final')";
		// $result = mysqli_query($conn,$sql) or die(mysqli_error());
		try{
            $sql = "INSERT INTO visitors VALUES(0, ?, ?, ?)";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("sss", $visitor_name_sanitazied_final, $visitor_email_sanitazied_final, $visitor_message_sanitazied_final );
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
	}
?>
<html>
<head>
	<?php 
    include 'includes/header.php';
	?>
</head>
<body>
	<div class="background"></div>
    <div class="foreground"></div>
    <nav>
        <div class="nav-container">
            <h1>
                <img src="/images/cdaclogo.png" style="height: 90px; width: 90px; margin-left: 0.4em; margin-top: 0.2em;" />
            </h1>
        </div>

	</nav>
	<div class="main-container">
        <div class="login-card">
		<form id="contact-form" method="post" action="contact-us.php">
			<input name="name" type="text" class="form-control" placeholder="Your Name" required>
			<input name="email" type="email" class="form-control" placeholder="Your Email" required>
			<textarea name="message" class="form-control" placeholder="Message" row="4" required></textarea>
			<input type="submit" class="form-control submit" name="submit-message" value="SEND MESSAGE">
		</form>		
		</div>
	</div>
</body>
</html>	
