<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Fetch announcements
$announcements = $conn_enroll->query("SELECT * FROM announcements ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements</title>
<link rel="stylesheet" href="signlog.css">
<style>
.announce-container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

.announce-card {
    background: #ffffff;
    border-left: 6px solid #2dc72d;
    box-shadow: 0px 3px 10px rgba(0,0,0,0.12);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    animation: fadeSlide 0.4s ease;
}

.announce-card h3 {
    margin: 0;
    color: #1d7e33;
}

.announce-card small {
    color: #666;
}

@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body class="dashboard-body">

<div class="dashboard-container">
    <header>
        <div class="header-left">
            <img src="imagesptc/ptclogo.png" class="logo">
            <h1>Announcements</h1>
        </div>
        <a href="user_dashboard.php" class="logout-btn" style="margin-left:auto;">⬅ Back</a>
    </header>

    <div class="announce-container">

        <h2 style="margin-bottom:20px;">Latest Announcements</h2>

        <?php if ($announcements->num_rows > 0): ?>
            <?php while ($row = $announcements->fetch_assoc()): ?>
                <div class="announce-card">
                    <h3><?php echo $row['title']; ?></h3>
                    <small>Posted on: <b><?php echo $row['date_posted']; ?></b></small>
                    <p style="margin-top:15px;"><?php echo nl2br($row['content']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements yet.</p>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
