<?php
session_start();
require('../includes/fpdf.php');
$upcomingClasses = $_SESSION['upcoming_classes'];

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
       
        $this->SetFont('Arial','B',14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,8,
            'SBDC Upcoming Classes  - '.$today, 0, 0, 'C');
        // Line break
        $this->Ln(15);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->SetTextColor(26, 22, 22);
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 12);
if (isset($_POST['submitPrintClasses'])) {
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(25,8,"DATE",1,0,"L"); 
  $pdf->Cell(80,8,"CLASS",1,0,"L");  
  $pdf->Cell(50,8,"LEVEL",1,0,"L"); 
  $pdf->Cell(80,8,"INSTRUCTORS",1,0,"L"); 
  $pdf->Cell(30,8,"TIME",1,1,"L"); 


  $pdf->SetFont('Arial', '', 12);
  foreach ($upcomingClasses as $class) {
    $pdf->Cell(25,8,$class['date'],1,0,"L"); 
    $pdf->Cell(80,8,$class['classname'],1,0,"L");  
    $pdf->Cell(50,8,$class['classlevel'],1,0,"L"); 
    $pdf->Cell(80,8,$class['instructors'],1,0,"L"); 
    $pdf->Cell(30,8,$class['time'],1,1,"L"); 
    $pdf->Cell(25,8," ",1,0,"L"); 
    $pdf->Cell(20,8,"ROOM:",1,0,"L"); 
    $pdf->Cell(220,8,$class['room'],1,1,"L");
    $pdf->Cell(25,8," ",1,0,"L"); 
    $pdf->Cell(20,8,"NOTES:",1,0,"L"); 
    $pdf->Cell(220,8,$class['classnotes'],1,1,"L");
  }
}
$today = date("m-d-Y");
$pdf->Output("I", "SBDCUpcomingClasses.".$today.".pdf");


// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
