<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$userEmail = $_SESSION['email'];

// Check if already submitted
$stmt = $conn_enroll->prepare("SELECT id FROM students WHERE email=?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$alreadySubmitted = $result->num_rows > 0;


// Gravatar profile
$email = strtolower(trim($_SESSION['email']));
$profile_pic = "https://www.gravatar.com/avatar/" . md5($email) . "?s=200&d=mp";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<link rel="stylesheet" href="signlog.css">
</head>
<body class="dashboard-body">

<div class="dashboard-container">
    <header>
        <div class="header-left">
            <img src="imagesptc/ptclogo.png" alt="PTC Logo" class="logo">
            <h1>PTC Registration Portal</h1>
        </div>
    </header>

    <div class="dashboard-main">
        <!-- User Card -->
        <div class="user-card">
            <img src="<?php echo $profile_pic; ?>" alt="Profile" class="profile-pic">
            <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
            <div class="progress-barnew">Application Status</div>
            <div class="progress-bar">
                <div class="progress-step completed">Application</div>
                <div class="progress-step completed">Register</div>
                <div class="progress-step">Verified</div>
            </div>
        </div>

        <!-- Main Content Area -->
      <div class="main-content">
    <div class="main-contentbox">

      <a href="announcement_view.php?" class="item">
    <h3>Announcements</h3>
    <p>View the latest announcements.</p>
</a>

<?php if (!$alreadySubmitted): ?>
    <a href="registration.php" class="item"> 
        <h3>Registration</h3>
        <p>Upcoming freshmen</p>
    </a>
<?php else: ?>
    <div class="item" style="opacity:0.5; pointer-events:none; cursor:not-allowed;">
        <h3>Registration</h3>
        <p style="color:red;">Already Submitted</p>
    </div>
<?php endif; ?>


<a href="myprofile.php" class="item">
    <h3>Profile</h3>
    <p>View and edit your profile information.</p>
</a>

<a href="https://www.paterostechnologicalcollege.edu.ph/academic%20affairs/REGISTRAR_OFFICE/" class="item">
    <h3>Support</h3>
    <p>Contact support for assistance.</p>
</a>

</div>

</div>
    </div>
    <a href="logout.php" class="logout-btn">Log Out</a>
</div>
<footer class="admin-footer">
    <p>© 2025 Pateros Technological College — Enrollment & Registration Portal</p>
    <p>Need help? <a href="https://www.paterostechnologicalcollege.edu.ph/academic%20affairs/REGISTRAR_OFFICE/">Contact Support</a></p>
</footer>

</body>
</html>
