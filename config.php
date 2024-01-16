<?php
    $servername = "localhost";
    $username = "";
    $password = "";
    $database = "CDAC-K_CTF";
    $debug_mode = true;

    $ldap_connection = false;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // echo "Connected successfully";

    // This part is not required when ldap connection is not in use
    $ldap_hostname = "";
    $ldapBaseDn = "";
    $ldapPort = 389;
    $ldap_protocol = 3;
    $ldap_rootDN = null; // The DN for the ROOT Account Set to null for anonymous LDAP binding
    $ldap_root_password = null;
    $ldap_uft8 = true;

    $ldap_filter = '(objectClass=*)';
?>
