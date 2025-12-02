<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn_enroll->prepare("SELECT * FROM students WHERE qr_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $students = $result->fetch_assoc();
        echo "
        <html>
        <head>
          <title>Student Verified</title>
          <link rel='stylesheet' href='style.css'>
          <style>
            body {
              font-family: 'Poppins', sans-serif;
              background: linear-gradient(135deg, #00b09b, #96c93d);
              height: 100vh;
              display: flex;
              justify-content: center;
              align-items: center;
              color: #fff;
              text-align: center;
            }
            .verify-card {
              background: rgba(255, 255, 255, 0.1);
              padding: 40px;
              border-radius: 20px;
              box-shadow: 0 10px 30px rgba(0,0,0,0.2);
              width: 400px;
            }
            .verify-card h1 { color: #fff; margin-bottom: 10px; }
            .verify-card p { margin: 5px 0; color: #fff; font-size: 15px; }
          </style>
        </head>
        <body>
          <div class='verify-card'>
            <h1>✅ Verified Student</h1>
            <p><b>Name:</b> {$students['fullname']}</p>
            <p><b>Course:</b> {$students['course']}</p>
            <p><b>Exam Date:</b> {$students['exam_date']}</p>
            <p><b>Email:</b> {$students['email']}</p>
            <p><b>QR ID:</b> {$students['qr_id']}</p>
          </div>
        </body>
        </html>";
    } else {
        echo "<h2 style='color:red;text-align:center;margin-top:50px;'>❌ Invalid or Unregistered QR Code</h2>";
    }
}
?>
