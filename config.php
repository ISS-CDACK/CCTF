<?php
    $servername = "localhost";
    $username = "ctf_code_USER";
    $password = "ctf";
    $database = "CDAC-K_CTF";
    $debug_mode = false;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // echo "Connected successfully";
?>
