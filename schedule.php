<?php
$name = $_GET['name'];
$date = $_GET['date'];
$qr = $_GET['qr'];
$code = $_GET['code'];
$bday = $_GET['bday']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam Schedule</title>
  <style>
    body { font-family: Arial; text-align: center; padding-top: 50px; background: #f9fafc; }
    img { width: 200px; margin: 15px; }
    .card { display: inline-block; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { color: #2b7cff; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Enrollment Successful!</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Birthday:</strong> <?= htmlspecialchars($bday) ?></p>
    <p><strong>Exam Date:</strong> <?= htmlspecialchars($date) ?></p>
    <p><strong>Your Unique ID:</strong> <?= htmlspecialchars($code) ?></p>
    <p>Show this QR code on exam day:</p>
    <img src="qrcodes/<?= htmlspecialchars($qr) ?>" alt="QR Code">
    <br>
    <a href="admin.php">Go to Admin Verification</a>
  </div>
</body>
</html>
