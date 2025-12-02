<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, "enrollment_db");

$id = $_GET['id'];

$conn->query("DELETE FROM announcements WHERE id='$id'");
header("Location: admin_dashboard.php?deleted=1");
exit();
?>
