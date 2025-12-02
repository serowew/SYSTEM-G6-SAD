<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$userEmail = $_SESSION['email'];

// Fetch user submitted data
$stmt = $conn_enroll->prepare("SELECT * FROM students WHERE email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Generate QR Code URL
$qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($data['qr_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<link rel="stylesheet" href="signlog.css">

<style>
/* --- PROFILE CARD STYLE --- */
.profile-card {
    width: 95%;
    max-width: 1050px;
    margin: auto;
    margin-top: 20px;
    background: white;
    border-radius: 18px;
    box-shadow: 0px 5px 18px rgba(0,0,0,0.20);
    overflow: hidden;
}

.profile-header {
    background: #009900;
    color: white;
    padding: 18px;
    font-size: 28px;
    font-weight: bold;
    text-align: center;
}

.profile-content {
    padding: 25px;
}

.profile-table {
    width: 100%;
    border-collapse: collapse;
}

.profile-table th {
    background: #009900;
    color: white;
    padding: 12px;
    width: 35%;
    font-size: 16px;
}

.profile-table td {
    background: #f2f2f2;
    padding: 12px;
    font-size: 16px;
}

.qr-box {
    text-align: center;
    margin-top: 25px;
}

.download-btn {
    display: inline-block;
    margin-top: 25px;
    background: #007700;
    padding: 12px 18px;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.download-btn:hover {
    background: #005c00;
}
</style>
</head>

<body class="dashboard-body">

<div class="dashboard-container">

<header>
    <div class="header-left">
        <img src="imagesptc/ptclogo.png" class="logo">
        <h1>My Registration Profile</h1>
    </div>
    <a href="user_dashboard.php" class="logout-btn">⬅ Back</a>
</header>

<div class="profile-card">

    <div class="profile-header">Your Submitted Details</div>

    <?php if (!$data): ?>
        <h2 style="color: red; padding:20px; text-align:center;">
            You have not submitted any registration form yet.
        </h2>
    <?php else: ?>

    <div class="profile-content">

        <!-- PROFILE PICTURE -->
        <div style="text-align:center; margin-bottom:20px;">
            <img src="<?= $data['picture'] ?>" 
                 style="width:130px; height:130px; border-radius:10px; border:3px solid #009900;">
        </div>

        <table class="profile-table">
            <tr><th>Full Name</th><td><?= $data['fullname'] ?></td></tr>
            <tr><th>Email</th><td><?= $data['email'] ?></td></tr>
            <tr><th>Course</th><td><?= $data['course'] ?></td></tr>
            <tr><th>Exam Date</th><td><?= $data['exam_date'] ?></td></tr>
            <tr><th>QR ID</th><td><?= $data['qr_id'] ?></td></tr>
            <tr><th>Status</th><td><?= $data['verification_status'] ?></td></tr>

            <tr><th>Birth Certificate</th><td><a href="<?= $data['birth_cert'] ?>" target="_blank">View</a></td></tr>
            <tr><th>JHS File</th><td><a href="<?= $data['jhs_file'] ?>" target="_blank">View</a></td></tr>
            <tr><th>SHS File</th><td><a href="<?= $data['shs_file'] ?>" target="_blank">View</a></td></tr>
            <tr><th>Grades File</th><td><a href="<?= $data['grades_file'] ?>" target="_blank">View</a></td></tr>
        </table>

        <!-- QR CODE PREVIEW -->
        <div class="qr-box">
            <h3>Your QR Code</h3>
            <img src="<?= $qrImage ?>" style="width:170px;">
        </div>

        <!-- DOWNLOAD PDF -->
        <center>
            <a href="profile_pdf.php" class="download-btn">📄 Download PDF</a>
        </center>

    </div>

    <?php endif; ?>

</div>

</div>

</body>
</html>
