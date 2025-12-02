<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$userEmail = $_SESSION['email'];

// Fetch user data
$stmt = $conn_enroll->prepare("SELECT * FROM students WHERE email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("You have not submitted a registration yet.");
}

$verified = ($data['verification_status'] === "Verified");

// Handle Update
if (isset($_POST["update"]) && !$verified) {

    $fullname = $_POST["fullname"];
    $birthday = $_POST["birthday"];
    $course = $_POST["course"];
    $exam_date = $_POST["exam_date"];

    // FILE UPLOAD HANDLER
    function uploadFile($fieldName, $oldValue) {
        if (!empty($_FILES[$fieldName]["name"])) {
            $target = "uploads/" . time() . "_" . basename($_FILES[$fieldName]["name"]);
            move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target);
            return $target;
        }
        return $oldValue;
    }

    $birth_cert = uploadFile("birth_cert", $data['birth_cert']);
    $jhs_file = uploadFile("jhs_file", $data['jhs_file']);
    $shs_file = uploadFile("shs_file", $data['shs_file']);
    $picture = uploadFile("picture", $data['picture']);
    $grades_file = uploadFile("grades_file", $data['grades_file']);

    $stmt = $conn_enroll->prepare("UPDATE students SET fullname=?, birthday=?, course=?, exam_date=?, birth_cert=?, jhs_file=?, shs_file=?, picture=?, grades_file=? WHERE email=?");
    $stmt->bind_param("ssssssssss", 
        $fullname, $birthday, $course, $exam_date,
        $birth_cert, $jhs_file, $shs_file, $picture, $grades_file,
        $userEmail
    );

    $stmt->execute();

    header("Location: my_profile.php?updated=1");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>
<link rel="stylesheet" href="signlog.css">

<style>
.edit-container {
    background: white;
    padding: 25px;
    border-radius: 15px;
    width: 700px;
    margin: auto;
}
.edit-container h2 {
    text-align: center;
}
input, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
}
button {
    background: #28a745;
    padding: 12px;
    border: none;
    border-radius: 8px;
    width: 100%;
    color: white;
    font-size: 16px;
    cursor: pointer;
}
button:disabled {
    background: gray;
    cursor: not-allowed;
}
.preview-img {
    width: 120px;
    border-radius: 10px;
    margin-bottom: 10px;
}
</style>
</head>

<body class="dashboard-body">
<div class="dashboard-container">

<header>
    <div class="header-left">
        <img src="imagesptc/ptclogo.png" class="logo">
        <h1>Edit Profile</h1>
    </div>
    <a href="my_profile.php" class="logout-btn">⬅ Back</a>
</header>

<div class="main-content">
    <div class="edit-container">

        <?php if ($verified): ?>
            <h2 style="color:red;">Your profile is VERIFIED. Editing is locked.</h2>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <h2>Personal Information</h2>
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?= $data['fullname'] ?>" <?= $verified ? 'disabled' : '' ?>>

            <label>Birthday</label>
            <input type="date" name="birthday" value="<?= $data['birthday'] ?>" <?= $verified ? 'disabled' : '' ?>>

            <label>Course</label>
            <input type="text" name="course" value="<?= $data['course'] ?>" <?= $verified ? 'disabled' : '' ?>>

            <label>Exam Date</label>
            <input type="text" name="exam_date" value="<?= $data['exam_date'] ?>" <?= $verified ? 'disabled' : '' ?>>

            <h2>Uploaded Files</h2>

            <label>2x2 Picture</label><br>
            <img src="<?= $data['picture'] ?>" class="preview-img">
            <input type="file" name="picture" <?= $verified ? 'disabled' : '' ?>>

            <label>Birth Certificate</label>
            <input type="file" name="birth_cert" <?= $verified ? 'disabled' : '' ?>>

            <label>JHS Card</label>
            <input type="file" name="jhs_file" <?= $verified ? 'disabled' : '' ?>>

            <label>SHS Card</label>
            <input type="file" name="shs_file" <?= $verified ? 'disabled' : '' ?>>

            <label>Grades File</label>
            <input type="file" name="grades_file" <?= $verified ? 'disabled' : '' ?>>

            <br><br>

            <?php if (!$verified): ?>
                <button type="submit" name="update">Save Changes</button>
            <?php endif; ?>

        </form>

    </div>
</div>

</div>
</body>
</html>
