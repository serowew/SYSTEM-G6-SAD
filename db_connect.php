<?php
$host = "localhost";
$user = "root";
$pass = "";

// Connect to enrollment_system
$conn_system = new mysqli($host, $user, $pass, "enrollment_system");
if ($conn_system->connect_error) {
    die("Connection failed (system): " . $conn_system->connect_error);
}

// Connect to enrollment_db
$conn_enroll = new mysqli($host, $user, $pass, "enrollment_db");
if ($conn_enroll->connect_error) {
    die("Connection failed (enroll): " . $conn_enroll->connect_error);
}
?>
