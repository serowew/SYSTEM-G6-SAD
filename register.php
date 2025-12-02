<?php
include 'db_connect.php';
$error = '';
$success = false;

if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirmPassword'];
    $role     = $_POST['role'];

    if($password !== $confirm){
        $error = "Passwords do not match!";
    } else {
        // Check if email exists
        $stmt = $conn_system->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $error = "Email is already registered!";
        } else {
            // Insert new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn_system->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $username,$email,$hashed,$role);

            if($stmt->execute()){
                $success = true;  // Show slide notif
            } else {
                $error = "Registration failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="signlog.css">

<style>
.notif {
    position: fixed;
    top: 20px;
    right: -300px;
    background: #2ecc71;
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    font-size: 18px;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    transition: right 0.4s ease;
    z-index: 9999;
}
.notif.show {
    right: 20px;
}
</style>
</head>

<body>

<?php if($success): ?>
<div id="slideNotif" class="notif">✔ Registration Successful!</div>

<script>
setTimeout(() => {
    document.getElementById("slideNotif").classList.add("show");
}, 200);

// Hide and redirect
setTimeout(() => {
    document.getElementById("slideNotif").classList.remove("show");
    setTimeout(() => {
        window.location.href = "login.php";
    }, 400);
}, 2000);
</script>
<?php endif; ?>


<div class="auth-card">
<h2>Register</h2>

<?php if($error != ''): ?>
<div class="error-message"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
    <select name="role" required>
        <option value="" disabled selected>--Select Role--</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
       
    </select>
    <button type="submit" name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
