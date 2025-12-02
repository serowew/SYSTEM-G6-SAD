<?php
include 'db_connect.php';

if (!isset($_GET['id'])) exit("No ID provided");
$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($result);

$files = [];
foreach (['birth_cert','jhs_file','shs_file','picture','grades_file'] as $key)
 {
  if ($student[$key]) $files[] = $student[$key];
}

$zip = new ZipArchive();
$zipName = "student_{$student['id']}_files.zip";
if ($zip->open($zipName, ZipArchive::CREATE) !== TRUE) exit("Cannot create ZIP");

foreach ($files as $file) if (file_exists($file)) $zip->addFile($file, basename($file));
$zip->close();

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$zipName");
header("Content-Length: " . filesize($zipName));
readfile($zipName);
unlink($zipName);
?>
