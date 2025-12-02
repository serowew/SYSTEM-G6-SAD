<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Incoming Freshmen Enrollment | PTC</title>
  <link rel="stylesheet" href="enrollment.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="enrollment-page">
  <main class="enrollment-container">
    <section class="enroll-left">
        <img src="imagesptc/ptclogo.png" alt="PTC Logo" class="enroll-logo">
        <h2>Pateros Technological College</h2>
      <p class="tagline">Gearing the way to your future!</p>
      <p class="subtext">Join PTC — where education meets innovation.</p>
    </section>

    <section class="enroll-form">
      <h2>Incoming Freshmen Enrollment</h2>
      <form action="subenroll.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        
        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" id="birthday" required>

        <label>Course:</label>
        <select name="course" required>

          <option value="">Select Course</option>
          <?php
            include 'db_connect.php';
            $courses = mysqli_query($conn_enroll, "SELECT * FROM courses ORDER BY course_name ASC");
            while ($row = mysqli_fetch_assoc($courses)) {
              echo "<option value='{$row['course_name']}'>{$row['course_name']}</option>";
            }
          ?>
        </select>

        <label>Preferred Exam Date:</label>
        <select name="exam_date" required>
          <option value="">Select Exam Date</option>
          <?php
            $dates = mysqli_query($conn_enroll, "SELECT * FROM exam_dates ORDER BY exam_date ASC");
            while ($row = mysqli_fetch_assoc($dates)) {
              echo "<option value='{$row['exam_date']}'>{$row['exam_date']}</option>";
            }
          ?>
        </select>

        <h3>Attachments</h3>
        <label>Birth Certificate:</label><input type="file" name="birth_cert" required>
        <label>Junior High Diploma:</label><input type="file" name="jhs_file" required>
        <label>Senior High Diploma:</label><input type="file" name="shs_file" required>
        <label>2x2 Picture:</label><input type="file" name="picture" required>
        <label>Senior High Grades:</label><input type="file" name="grades_file" required>

        <div class="form-buttons">
  <button type="submit" class="submit-button">Register</button>
  <button type="button" onclick="history.back()" class="back-button">← Back</button>

</div>

      </form>
    </section>
  </main>

</body>
</html>
