<?php
session_start();
require('../includes/fpdf.php');
$upcomingEvents = $_SESSION['upcoming_events'];
$eventName = '';
$eventDesc = '';
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
            'SBDC Upcoming Events  - '.$today, 0, 0, 'C');
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
if (isset($_POST['submitPrintEvents'])) {
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(25,8,"DATE",1,0,"L"); 
  $pdf->Cell(90,8,"EVENT",1,0,"L");  
  $pdf->Cell(50,8,"TYPE",1,0,"L"); 
  $pdf->Cell(50,8,"ROOM",1,0,"L"); 
  $pdf->Cell(50,8,"COST",1,1,"L"); 
 

  $pdf->SetFont('Arial', '', 12);
  foreach ($upcomingEvents as $event) {
    $pdf->Cell(25,8,$event['eventdate'],1,0,"L"); 
    $eventName = $event['eventname'];
 
    $ampPos = strpos($eventName, "&amp;", 0);
  
    if ($ampPos) {
      $eventName = substr_replace($eventName, 'and', $ampPos, 5) ;
    }
    $eventDesc = $event['eventdesc'];
 
    $ampPos = strpos($eventDesc, "&amp;", 0);
  
    if ($ampPos) {
      $eventDesc = substr_replace($eventDesc, 'and', $ampPos, 5) ;
    }
    $pdf->Cell(90,8,$eventName,1,0,"L");  
    $pdf->Cell(50,8,$event['eventtype'],1,0,"L"); 
    $pdf->Cell(50,8,$event['eventroom'],1,0,"L"); 
    $pdf->Cell(50,8,$event['eventcost'],1,1,"L"); 
 
    $pdf->Cell(25,8," ",0,0,"L"); 
    $pdf->Cell(20,8,"DJ:",1,0,"L"); 
    $pdf->Cell(220,8,$event['eventdj'],1,1,"L");
    $pdf->Cell(25,8," ",0,0,"L"); 
    $pdf->Cell(20,8,"DESC:",1,0,"L"); 
    $pdf->Cell(220,8,$eventDesc,1,1,"L");
    
  }
}
$today = date("m-d-Y");
$pdf->Output("I", "SBDCUpcomingEvents.".$today.".pdf");


$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
