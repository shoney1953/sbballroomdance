<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/EventArch.php';


$database = new Database();
$db = $database->connect();
$event = new EventArch($db);

$eventArr = [];
$num_events = 0;

$totalYearlyEvents = 0;
$averageRegistrations = 0;
$totNumRegistered = 0;
$prevEventYear = '1999';
$prevEventMonth = '00';
$eventCount = 0;

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/sbdc_logo_small.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            10,
            10,
            'SBDC Archived Event Summary - '.$today, 0, 0, 'C'
        );
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0,' C');
    }
}

if (isset($_POST['submitSummaryRep'])) {

    $result = $event->read();
    $rowCount = $result->rowCount();
    $num_events = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(

                'eventdate' => $eventdate,
                'eventtype' => $eventtype,
                'eventnumregistered' => $eventnumregistered,
                'eventyear' => date('Y', strtotime($eventdate)),
                'eventmonth' => date('m', strtotime($eventdate))

            );
            array_push($eventArr, $reg_item);
        }
    }
  }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

if ($rowCount > 0) {
    $regCount = 0;
    $paidNum = 0;
    $prevEvent = '';
    $init = 1;
    foreach ($eventArr as $event) {

        $regCount++;
        if ($init == 1) {
            $prevEventYear = $event['eventyear'];
            $prevEventMonth = $event['eventmonth'];
            $init = 0;
            $event_string = ' '.$event['eventyear'].' ';

            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(45,5,"EVENT TYPE",1,0,"L"); 
            $pdf->Cell(25,5,"YEAR",1,0,"L"); 
            $pdf->Cell(25,5,"MONTH",1,0,"L"); 
            $pdf->Cell(25,5,"REGISTERED",1,1,"L"); 

    
        }
        if ($event['eventyear'] !== $prevEventYear) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Ln(2);
            $pdf->Cell(0, 5, "Event Year:  ".$prevEventYear, 0, 1);
            $pdf->Cell(0, 5, "Total Yearly Events:  ".$eventCount, 0, 1); 
            $pdf->Cell(0, 5, "Total Registrations ".$totNumRegistered, 0, 1);
            $averageRegistrations = $totNumRegistered / $eventCount;
            $pdf->Cell(0, 5, "Average Registrations:  ". $averageRegistrations, 0, 1);
            $totNumRegistered = 0;
            $eventCount = 0;

         
           $prevEventYear = $event['eventyear'];
           $prevEventMonth = $event['eventmonth'];

            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'BU', 10);
            $event_string = ' '.$event['eventyear'].' ';
            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(45,5,"EVENT TYPE",1,0,"L"); 
            $pdf->Cell(25,5,"YEAR",1,0,"L"); 
            $pdf->Cell(25,5,"MONTH",1,0,"L"); 
            $pdf->Cell(25,5,"REGISTERED",1,1,"L"); 

         }
         if ($event['eventmonth'] !== $prevEventMonth) {
            $pdf->Cell(45,5," ----- ",1,0,"L"); 
            $pdf->Cell(25,5," ----- ",1,0,"L"); 
            $pdf->Cell(25,5," ----- ",1,0,"L"); 
            $pdf->Cell(25,5," -----",1,1,"L"); 
            $prevEventMonth = $event['eventmonth'];
         }

         $pdf->Cell(45,5,$event['eventtype'],1,0,"L");
         $pdf->Cell(25,5,$event['eventyear'],1,0,"L");
         $pdf->Cell(25,5,$event['eventmonth'],1,0,"L");
         $pdf->Cell(25,5,$event['eventnumregistered'],1,1,"L");
         $totNumRegistered = $totNumRegistered + $event['eventnumregistered'];
         $eventCount = $eventCount + 1;


    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Event Year:  ".$prevEventYear, 0, 1);
    $pdf->Cell(0, 5, "Total Yearly Events:  ".$eventCount, 0, 1); 
    $pdf->Cell(0, 5, "Total Registrations ".$totNumRegistered, 0, 1);
    $averageRegistrations = $totNumRegistered / $eventCount;
    $pdf->Cell(0, 5, "Average Registrations:  ". $averageRegistrations, 0, 1);
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
  $pdf->Cell(0, 10, "   NO Archived Events Found ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "Archive Event Summary Report.".$today.".PDF");


$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
