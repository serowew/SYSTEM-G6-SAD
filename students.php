<?php
session_start();

// Connect to the enrollment database
$host = "localhost";
$user = "root";
$pass = "";
$conn_enroll = new mysqli($host, $user, $pass, "enrollment_db");

if ($conn_enroll->connect_error) {
    die("Connection failed (enroll): " . $conn_enroll->connect_error);
}

// HANDLE SEARCH
$search_email = "";
$where = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_email = $conn_enroll->real_escape_string($_GET['search']);
    $where = "WHERE email LIKE '%$search_email%'";
}

// HANDLE DELETE
if (isset($_POST['delete_value']) && !empty($_POST['delete_value'])) {
    $delete = $conn_enroll->real_escape_string($_POST['delete_value']);
    $conn_enroll->query("DELETE FROM students WHERE id='$delete' OR email='$delete'");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Students | Admin Dashboard</title>
  <link rel="stylesheet" href="signlog.css">

<style>
/* SEARCH + DELETE BAR STYLING */
.action-bar {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.action-bar input {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  width: 250px;
}

.action-bar button {
  padding: 10px 18px;
  border: none;
  background: #007bff;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}

.delete-input {
  border: 1px solid #ccc !important;
}

.delete-btn {
  background: red !important;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  padding: 12px;
  border-bottom: 1px solid #8fe6b0ff;
}

th {
  background: #2dc72dff;
}
</style>

</head>
<body class="dashboard-body">
  <div class="dashboard-container">

    <header>
      <div class="header-left">
        <img src="imagesptc/ptclogo.png" alt="PTC Logo" class="logo">
        <h1>Enrolled Students</h1>
      </div>
      <a href="admin_dashboard.php" class="logout-btn" style="margin-left:auto;">⬅ Back to Dashboard</a>
    </header>

    <div class="main-content">
      <div class="admin-section">
        <h2>Submitted Students</h2>

        <!-- ===========================
             SEARCH + DELETE BAR
        ============================ -->
        <form method="GET" class="action-bar">
          <input type="text" name="search" placeholder="Search by Email..." value="<?php echo $search_email; ?>">
          <button type="submit">🔍 Search</button>
        </form>

        <form method="POST" class="action-bar">
          <input type="text" name="delete_value" class="delete-input" placeholder="Enter ID or Email to Delete">
          <button type="submit" class="delete-btn">🗑 Delete</button>
        </form>

        <!-- ===========================
             STUDENTS TABLE
        ============================ -->
        <table>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Exam Date</th>
            <th>Birth Certificate</th>
            <th>JHS Card</th>
            <th>SHS Card</th>
            <th>Picture</th>
            <th>Grade</th>
            <th>QR Code</th>
            <th>Verification</th>

          </tr>

          <?php
            $students = $conn_enroll->query("SELECT * FROM students $where ORDER BY id DESC");

            if ($students->num_rows > 0) {
                while ($row = $students->fetch_assoc()) {
                    echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['fullname']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['course']}</td>
                      <td>{$row['exam_date']}</td>
                      <td><a href='{$row['birth_cert']}' target='_blank'>View</a></td>
                      <td><a href='{$row['jhs_file']}' target='_blank'>View</a></td>
                      <td><a href='{$row['shs_file']}' target='_blank'>View</a></td>
                      <td><a href='{$row['picture']}' target='_blank'>View</a></td>
                      <td><a href='{$row['grades_file']}' target='_blank'>View</a></td>
                      <td><a href='{$row['qr_code']}' target='_blank'>View</a></td>
                      <td>{$row['verification_status']}</td>

                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding:20px;'>No results found.</td></tr>";
            }
          ?>
        </table>

      </div>
    </div>
  </div>
</body>
</html>
