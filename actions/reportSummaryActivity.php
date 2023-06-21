<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/DanceClassArch.php';
require_once '../models/EventArch.php';

$database = new Database();
$db = $database->connect();
$class = new DanceClassArch($db);
$event = new EventArch($db);

$eventArr = [];
$sumItem = [];
$num_events = 0;

$totalYearlyEvents = 0;
$averageRegistrations = 0;
$totNumRegistered = 0;
$prevEventYear = '1999';
$prevEventMonth = '00';
$eventCount = 0;
$yearMonth = '';

$classArr = [];
$comboArr = [];
$summaryClass = [];
$num_classes = 0;

$totalYearlyEvents = 0;
$averageRegistrations = 0;
$totNumRegistered = 0;

$prevClassYear = '1999';
$prevClassMonth = '00';
$classCount = 0;

// class PDF extends FPDF
// {
//     function Header() {
//         // Logo
//         $today = date("m-d-Y");
//         $this->Image('../img/sbdc_logo_small.png',10,6,30);
//         // Arial bold 15
//         $this->SetFont('Arial','B',15);
//         // Move to the right
//         $this->Cell(80);
//         // Title
//         $this->Cell(
//             10,
//             10,
//             'SBDC Archived Classes Summary - '.$today, 0, 0, 'C'
//         );
//         // Line break
//         $this->Ln(20);
//     }

//     // Page footer
//     function Footer() {
//         // Position at 1.5 cm from bottom
//         $this->SetY(-15);
//         // Arial italic 8
//         $this->SetFont('Arial', 'I', 8);
//         // Page number
//         $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0,' C');
//     }
// }

if (isset($_POST['submitSummaryRep'])) {

    $result = $class->read();
    $rowCount = $result->rowCount();
    $num_classes = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
               'type' => 'CLASS',
                'date' => $date,
                'eventtype' => $classlevel,
                'numregistered' => $numregistered,
                'year' => date('Y', strtotime($date)),
                'month' => date('m', strtotime($date))

            );
            array_push($comboArr, $reg_item);
        }
    }
    $result = $event->read();
    $rowCount = $result->rowCount();
    $num_events = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'type' => 'EVENT',
                'date' => $eventdate,
                'eventtype' => $eventtype,
                'numregistered' => $eventnumregistered,
                'year' => date('Y', strtotime($eventdate)),
                'month' => date('m', strtotime($eventdate))

            );
            array_push($comboArr, $reg_item);
        }
    }
   

  }

//     $pdf = new PDF();
//     $pdf->AliasNbPages();
//     $pdf->SetTextColor(26, 22, 22);
//     $pdf->AddPage();
//     $pdf->SetFont('Arial', '', 10);

// if ($rowCount > 0) {
//     $regCount = 0;
//     $paidNum = 0;
//     $prevEvent = '';
//     $init = 1;
//     foreach ($classArr as $class) {

//         $regCount++;
//         if ($init == 1) {
//             $prevClassYear = $class['classyear'];
            
//             $init = 0;
//             $prevClassMonth = $class['classmonth'];
//             $class_string = ' '.$class['classyear'].' ';
//             $pdf->SetFont('Arial', 'B', 12);
//             $pdf->Cell(0, 12, 'CLASSES', 0, 1);
//             $pdf->SetFont('Arial', 'BU', 10);
//             $pdf->Cell(0, 10, $class_string, 0, 1);
//             $pdf->SetFont('Arial', '', 10);
//             $pdf->Cell(45,5,"CLASS TYPE",1,0,"L"); 
//             $pdf->Cell(25,5,"YEAR",1,0,"L"); 
//             $pdf->Cell(25,5,"MONTH",1,0,"L"); 
//             $pdf->Cell(25,5,"REGISTERED",1,1,"L"); 

    
//         }
//         if ($class['classyear'] !== $prevClassYear) {
//             $pdf->SetFont('Arial', 'B', 10);
//             $pdf->Ln(2);
//             $pdf->Cell(0, 5, "Class Year:  ".$prevClassYear, 0, 1);
//             $pdf->Cell(0, 5, "Total Yearly Classes:  ".$classCount, 0, 1); 
//             $pdf->Cell(0, 5, "Total Registrations ".$totNumRegistered, 0, 1);
//             $averageRegistrations = $totNumRegistered / $classCount;
//             $pdf->Cell(0, 5, "Average Registrations:  ". $averageRegistrations, 0, 1);
//             $totNumRegistered = 0;
//             $classCount = 0;

         
//            $prevClassYear = $class['classyear'];

//            $prevClassMonth = $class['classmonth'];
//             $pdf->Ln(3);
//             $pdf->SetFont('Arial', 'BU', 10);
//             $class_string = ' '.$class['classyear'].' ';
//             $pdf->SetFont('Arial', 'BU', 10);
//             $pdf->Cell(0, 10, $class_string, 0, 1);
//             $pdf->SetFont('Arial', '', 10);
//             $pdf->Cell(45,5,"CLASS LEVEL",1,0,"L"); 
//             $pdf->Cell(25,5,"YEAR",1,0,"L"); 
//             $pdf->Cell(25,5,"MONTH",1,0,"L"); 
//             $pdf->Cell(25,5,"REGISTERED",1,1,"L"); 

//          }

//         if ($class['classmonth'] !== $prevClassMonth) {
//           $pdf->Cell(45,5," ----- ",1,0,"L"); 
//           $pdf->Cell(25,5," ----- ",1,0,"L"); 
//           $pdf->Cell(25,5," ----- ",1,0,"L"); 
//           $pdf->Cell(25,5," -----",1,1,"L"); 
//           $prevClassMonth = $class['classmonth'];
//         }
//          $pdf->Cell(45,5,$class['classlevel'],1,0,"L");
//          $pdf->Cell(25,5,$class['classyear'],1,0,"L");
//          $pdf->Cell(25,5,$class['classmonth'],1,0,"L");
//          $pdf->Cell(25,5,$class['numregistered'],1,1,"L");
//          $totNumRegistered = $totNumRegistered + $class['numregistered'];
//          $classCount = $classCount + 1;


//     }
//     $pdf->SetFont('Arial','B', 10);
//     $pdf->Ln(2);
//     $pdf->Cell(0, 5, "CLASS Year:  ".$prevClassYear, 0, 1);
//     $pdf->Cell(0, 5, "Total Yearly Classes:  ".$classCount, 0, 1); 
//     $pdf->Cell(0, 5, "Total Registrations ".$totNumRegistered, 0, 1);
//     $averageRegistrations = $totNumRegistered / $classCount;
//     $pdf->Cell(0, 5, "Average Registrations:  ". $averageRegistrations, 0, 1);
//     $pdf->SetFont('Arial', '', 10);
// } else {
//     $pdf->SetFont('Arial','B', 12);
//   $pdf->Cell(0, 10, "   NO Archived Classes Found ", 0, 1); 
//     $pdf->SetFont('Arial', '', 10);
// }
// $today = date("m-d-Y");
// $pdf->Output("I", "Archived Classes Summary Report.".$today.".PDF");


// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
