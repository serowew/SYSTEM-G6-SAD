<?php
include 'db_connect.php';
require 'phpqrcode/qrlib.php'; // QR Code library

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $course = $_POST['course'];
    $exam_date = $_POST['exam_date'];

    // Handle file uploads
   $uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir);

// Sanitize email to safe filename
$safeEmail = preg_replace("/[^a-zA-Z0-9]/", "_", $email);

$birthCert   = $uploadDir . $safeEmail . "_birth_" . basename($_FILES['birth_cert']['name']);
$jhsFile     = $uploadDir . $safeEmail . "_jhs_"   . basename($_FILES['jhs_file']['name']);
$shsFile     = $uploadDir . $safeEmail . "_shs_"   . basename($_FILES['shs_file']['name']);
$picture     = $uploadDir . $safeEmail . "_pic_"   . basename($_FILES['picture']['name']);
$gradesFile  = $uploadDir . $safeEmail . "_grades_" . basename($_FILES['grades_file']['name']);

move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birthCert);
move_uploaded_file($_FILES['jhs_file']['tmp_name'], $jhsFile);
move_uploaded_file($_FILES['shs_file']['tmp_name'], $shsFile);
move_uploaded_file($_FILES['picture']['tmp_name'], $picture);
move_uploaded_file($_FILES['grades_file']['tmp_name'], $gradesFile);

    // Generate unique student ID (QR data)
$uniqueID = uniqid('PTC-');

// Path to store QR code
$qrDir = 'qrcodes/';
if (!is_dir($qrDir)) mkdir($qrDir);
$qrFile = $qrDir . $uniqueID . '.png';

// ✅ Verification link (update the IP or domain below)
$serverURL = "http://192.168.1.40/enrollment-system"; // ⚠️ change this to your LAN IP or domain
$verifyURL = $serverURL . "/verify.php?id=" . urlencode($uniqueID);

// Generate QR code containing the verification link
QRcode::png($verifyURL, $qrFile, QR_ECLEVEL_L, 6);

// Insert student record with QR path + store the unique ID too
$stmt = $conn_enroll->prepare("
  INSERT INTO students 
  (fullname, email, birthday, course, exam_date, birth_cert, jhs_file, shs_file, picture, grades_file, qr_code, qr_id) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
  "ssssssssssss", 
  $fullname, $email, $birthday, $course, $exam_date, 
  $birthCert, $jhsFile, $shsFile, $picture, $gradesFile, 
  $qrFile, $uniqueID
);
$stmt->execute();

    // Output success page (no need for echo string — easier to read)


    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Registration Successful</title>
      <link rel="stylesheet" href="style.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
      <style>
        body.success-page {
          font-family: 'Poppins', sans-serif;
          background: linear-gradient(135deg, #9de094ff, #cbe06cff);
          height: 100vh;
          margin: 0;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .success-container {
          background: rgba(255, 255, 255, 0.9);
          padding: 40px 50px;
          border-radius: 20px;
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
          text-align: center;
          width: 400px;
          animation: fadeIn 0.6s ease-in-out;
        }

        .success-container h1 {
          color: #28a745;
          font-size: 28px;
          margin-bottom: 15px;
        }

        .success-container p {
          font-size: 15px;
          color: #333;
          margin: 8px 0;
        }

        .success-container img {
          width: 180px;
          height: 180px;
          border: 3px solid #007bff;
          border-radius: 12px;
          padding: 10px;
          background: #fff;
          margin: 15px 0;
        }

        .btn {
          display: inline-block;
          margin: 10px 5px;
          padding: 10px 20px;
          background: #007bff;
          color: white;
          border-radius: 8px;
          text-decoration: none;
          font-weight: 600;
          transition: 0.3s;
        }

        .btn:hover {
          background: #0056b3;
          transform: scale(1.05);
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(20px); }
          to { opacity: 1; transform: translateY(0); }
        }
      </style>
    </head>
    <body class="success-page">
      <div class="success-container">
        <h1>✅ Registration Successful!</h1>
        <p>Thank you, <b><?= htmlspecialchars($fullname) ?></b>. Your registration has been recorded.</p>
        <p>Use this QR Code for verification:</p>
        <img src="<?= htmlspecialchars($qrFile) ?>" alt="QR Code">
        <p><b>QR ID:</b> <?= htmlspecialchars($uniqueID) ?></p>
        <a href="<?= htmlspecialchars($qrFile) ?>" download class="btn">⬇ Download QR Code</a><br>
        <a href="user_dashboard.php" class="btn">🏠 Go Back</a>
      </div>
    </body>
    </html>
    <?php
}
?>
