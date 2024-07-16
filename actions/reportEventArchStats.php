<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/EventArch.php';

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistrationArch($db);
$eventArr = [];
$prevYear = '0000';
$prevEventType = '';

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/SBDC LOGO.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            50,
            10,
            'SBDC Archived Event Statistics Report - '.$today, 0, 1, 'C'
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

if (isset($_POST['submitEventStatsRep'])) {
 
        $result = $eventReg->readByType();
    
}
    $rowCount = $result->rowCount();
    $num_reg = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $event_item = array(
                'id' => $id,
                'previd' => $previd,
                'eventname' => $eventname,
                'eventtype' => $eventtype,
                'eventroom' => $eventroom,
                'eventdesc' => $eventdesc,
                'eventdj' => $eventdj,
                'orgemail' => $orgemail,
                'eventdate' => $eventdate,
                'eventcost' => $eventcost,
                'eventnumregistered' => $eventnumregistered,
                'eventregopen' => $eventregopen,
                'eventregend' => $eventregend
            );
            array_push($eventArr, $event_item);
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage('L');
    $pdf->SetFont('Arial', '', 10);

if ($rowCount > 0) {
    $regCount = 0;
    $paidNum = 0;
    $prevEvent = '';
    $init = 1;
    foreach ($eventArr as $event) {

        $regCount++;
        if ($init == 1) {
     
            $prevYear = substr($event['eventdate'], 0, 4);
            $prevEventType = $event['eventtype'];
            $prevEvent = $event['preveventid'];
            $init = 0;
 
        }
        $eventYear = substr($event['eventdate'], 0, 4);
        if ($eventYear != $prevYear) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Ln(2);
            $pdf->Cell(0, 5, "Total Registrations for this Event:  ".$regCount, 0, 1); 
            $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1);  
            $regCount = 1;
            $paidNum = 0;
         
            $prevEvent = $reg['preveventid'];
            $event_string = ' '.$reg['eventname'].'  '
            .$reg['eventdate'].' ';
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(35,5,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(35,5,"LAST NAME",1,0,"L");  
            $pdf->Cell(60,5,"EMAIL",1,0,"L");   
            $pdf->Cell(60,5,"DATE REGISTERED",1,0,"L");
            $pdf->Cell(35,5,"REG BY",1,1,"L");
         }
         $paid = 'Not Paid';
        if ($reg['paid'] == true) {
            $paidNum++;
          $paid = 'Paid';
        }
        

       
          $pdf->Cell(35,5,$reg['firstname'],1,0,"L"); 
          $pdf->Cell(35,5,$reg['lastname'],1,0,"L");  
          $pdf->Cell(60,5,$reg['email'],1,0,"L");   
          $pdf->Cell(60,5,$reg['dateregistered'],1,0,"L");
          $pdf->Cell(35,5,$reg['registeredby'],1,1,"L");


    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Registrations for this Event:  ".$regCount, 0, 1);
    $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1);  
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "EventRegistrationReport.".$today);
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
