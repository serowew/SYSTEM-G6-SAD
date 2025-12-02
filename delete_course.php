<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM courses WHERE id = '$id'");
}
header("Location: admin_dashboard.php");
exit;
?>
