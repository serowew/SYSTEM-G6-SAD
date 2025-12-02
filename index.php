  <?php
  session_start();

  // Redirect if already logged in
  if (isset($_SESSION['username'])) {
      if ($_SESSION['role'] === 'admin') {
          header('Location: admin_dashboard.php');
      } else {
          header('Location: user_dashboard.php');
      }
      exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | PTC Portal</title>
  <link rel="stylesheet" href="signlog.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="welcome-page">
  <div>
<img src="imagesptc/ptclogo.png" alt="PTC Logo" class="logo">
  <h1>Welcome to PTC Student Portal</h1>
  <p>Your one-stop platform for managing student information and updates.</p>
    <button onclick="window.location.href='login.php'">Login</button>
    <button onclick="window.location.href='register.php'">Register</button>
<div>
</body>
</html>
