<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_date = $_POST['exam_date'];
    if (!empty($exam_date)) {
        $stmt = $conn->prepare("INSERT INTO exam_dates (exam_date) VALUES (?)");
        $stmt->bind_param("s", $exam_date);
        $stmt->execute();
    }
}
header("Location: admin_dashboard.php");
exit;
?>
