<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

require("fpdf/fpdf.php");
include "db_connect.php";

$email = $_SESSION['email'];

$stmt = $conn_enroll->prepare("SELECT * FROM students WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont("Arial","B",16);
$pdf->Cell(0,10,"PTC Registration Profile",0,1,'C');
$pdf->Ln(5);

$pdf->SetFont("Arial","",12);

foreach ($data as $key => $value) {
    if ($key == "picture" || $key == "qr_code") continue;
    $pdf->Cell(50,10,ucwords(str_replace("_"," ",$key)).": ",0,0);
    $pdf->Cell(0,10,$value,0,1);
}

$pdf->Ln(10);

// QR Code
$qrURL = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $data['qr_id'];
$pdf->Image($qrURL, 80, $pdf->GetY(), 50, 50);

$pdf->Output();
?>
