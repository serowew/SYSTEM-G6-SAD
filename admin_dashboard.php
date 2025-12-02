<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// DB connection
$host = "localhost";
$user = "root";
$pass = "";
$conn_enroll = new mysqli($host, $user, $pass, "enrollment_db");
if ($conn_enroll->connect_error) {
    die("Connection failed: " . $conn_enroll->connect_error);
}

// Gravatar profile
$email = strtolower(trim($_SESSION['email']));
$profile_pic = "https://www.gravatar.com/avatar/" . md5($email) . "?s=200&d=mp";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="signlog.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <!-- Admin Card -->
        <div class="user-card admin-card">
            <img src="<?php echo $profile_pic; ?>" alt="Admin Profile" class="profile-pic">
            <h2>Welcome, Admin</h2>
            <div class="progress-bar">
               <a href="students.php" class="progress-step completed" style="text-decoration:none; color:white; font-weight:600;">
                    Students
                </a>
            </div>
        </div>

        <!-- Admin Content -->
        <div class="main-content">
            <!-- COURSES -->
            <section class="admin-section">
                <h2>📘 Manage Courses</h2>
                <form action="add_course.php" method="POST" class="admin-form">
                    <input type="text" name="course_name" placeholder="Enter new course..." required>
                    <button type="submit" class="action-btn">Add Course</button>
                </form>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $courses = $conn_enroll->query("SELECT * FROM courses ORDER BY id DESC");
                    while ($row = $courses->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['course_name']}</td>
                                <td><a href='delete_course.php?id={$row['id']}' class='btn-delete'>Delete</a></td>
                              </tr>";
                    }
                    ?>
                </table>
            </section>

            <!-- EXAM DATES -->
            <section class="admin-section">
                <h2>🗓 Manage Exam Dates</h2>
                <form action="add_exam_date.php" method="POST" class="admin-form">
                    <input type="date" name="exam_date" required>
                    <button type="submit" class="action-btn">Add Exam Date</button>
                </form>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Exam Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $dates = $conn_enroll->query("SELECT * FROM exam_dates ORDER BY id DESC");
                    while ($row = $dates->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['exam_date']}</td>
                                <td><a href='delete_exam_date.php?id={$row['id']}' class='btn-delete'>Delete</a></td>
                              </tr>";
                    }
                    ?>
                </table>
            </section>
            
            <!-- ANNOUNCEMENT MAKER -->
<section class="admin-section">
    <h2>📢 Announcement Maker</h2>

    <form action="add_announcement.php" method="POST" class="admin-form" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Announcement Title…" required>

        <textarea name="content" placeholder="Write your announcement…" 
                  style="width:100%; height:120px; padding:10px; border-radius:8px; border:1px solid #ccc; margin-top:10px;"
                  required></textarea>

        <label style="margin-top:10px; font-weight:bold;">Optional Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="action-btn" style="margin-top:10px;">Post Announcement</button>
    </form>

    <h3 style="margin-top:20px;">📄 Existing Announcements</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date Posted</th>
            <th>Action</th>
        </tr>

        <?php
        $ann = $conn_enroll->query("SELECT * FROM announcements ORDER BY id DESC");
        while ($a = $ann->fetch_assoc()) {
            echo "<tr>
                    <td>{$a['id']}</td>
                    <td>{$a['title']}</td>
                    <td>{$a['date_posted']}</td>
                    <td>
                        <a href='announcement_view.php?id={$a['id']}' class='btn-view'>View</a>
                        <a href='delete_announcement.php?id={$a['id']}' class='btn-delete'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</section>

        </div>
    </div>

    <a href="logout.php" class="logout-btn">Log Out</a>
</div>
<footer class="admin-footer">
    <p>© 2025 Pateros Technological College — Enrollment & Registration Portal</p>
    <p>Need help? <a href="support.html">Contact Support</a></p>
</footer>
</body>
</html>
