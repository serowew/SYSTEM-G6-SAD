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

$title = $_POST['title'];
$content = $_POST['content'];
$imagePath = "";

// HANDLE IMAGE
if (!empty($_FILES['image']['name'])) {
    $fileName = time() . "_" . $_FILES['image']['name'];
    $target = "uploads/" . $fileName;

    if (!is_dir("uploads")) mkdir("uploads");

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $imagePath = $target;
    }
}

$stmt = $conn->prepare("INSERT INTO announcements (title, content, image) VALUES (?,?,?)");
$stmt->bind_param("sss", $title, $content, $imagePath);

$stmt->execute();
$stmt->close();

header("Location: admin_dashboard.php?posted=1");
exit();
?>
