<?php
session_start();
require('../includes/fpdf.php');
include_once '../config/Database.php';
include_once '../models/VisitorsArch.php';

$database = new Database();
$db = $database->connect();
$visitor = new VisitorArch($db);

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	$today = date("m-d-Y");
	$this->Image('../img/sbdc_logo_small.png',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(80);
	// Title
	$this->Cell(50,10,'SBDC Visitors Archived- '.$today,0,0,'C');
	// Line break
	$this->Ln(20);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF("L");
$pdf->AliasNbPages();
$pdf->SetTextColor(26, 22, 22);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
// go get contacts
$result = $visitor->read();

$rowCount = $result->rowCount();
$num_visitors = $rowCount;
if($rowCount > 0) {
	$pdf->Cell(35,5,"LOGIN DATE",1,0,"L");
	$pdf->Cell(60,5,"EMAIL",1,0,"L");
	$pdf->Cell(40,5,"FIRST NAME",1,0,"L");
	$pdf->Cell(40,5,"LAST NAME",1,1,"L");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

		$pdf->Cell(35,5,$logindate,1,0,"L");
		$pdf->Cell(60,5,$email,1,0,"L");
		$pdf->Cell(40,5,$firstname,1,0,"L");
		$pdf->Cell(40,5,$lastname,1,1,"L");

    }


} else {
   echo 'NO Visitors';

}
$today = date("m-d-Y");
$pdf->Output("I", "VisitorArchReport.".$today);

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;
?>