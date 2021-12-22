<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$regArr = [];


class PDF extends FPDF
{
    function Header() {
        // Logo
        $this->Image('../img/sbdc_logo_small.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            10,
            10,
            'SBDC Event Registration Report', 0, 0, 'C'
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

if (isset($_POST['submitEventRep'])) {
 
    if (isset($_POST['eventId'])) {
        if ($_POST['eventId'] !== '') {
            $eventId = htmlentities($_POST['eventId']);
            $result = $eventReg->read_ByEventId($eventId);
        } else {
        $result = $eventReg->read();
    }
}
    $rowCount = $result->rowCount();
    $num_reg = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'eventid' => $eventid,
                'eventname' => $eventname,
                'eventdate' => $eventdate,
                'userid' => $userid,
                'email' => $email,
                'paid' => $paid,
                'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
            );
            array_push($regArr, $reg_item);
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
    foreach ($regArr as $reg) {

        $regCount++;
        if ($init == 1) {
            $prevEvent = $reg['eventid'];
            $init = 0;
            $event_string = ' '.$reg['eventname'].'  '
                     .$reg['eventdate'].' ';
            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(35,5,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(35,5,"LAST NAME",1,0,"L");  
            $pdf->Cell(60,5,"EMAIL",1,0,"L");   
            $pdf->Cell(60,5,"DATE REGISTERED",1,1,"L");
    
        }
        if ($reg['eventid'] !== $prevEvent) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Ln(2);
            $pdf->Cell(0, 5, "Total Registrations for this Event:  ".$regCount, 0, 1); 
            $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1);  
            $regCount = 1;
            $paidNum = 0;
         
            $prevEvent = $reg['eventid'];
            $event_string = ' '.$reg['eventname'].'  '
            .$reg['eventdate'].' ';
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(35,5,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(35,5,"LAST NAME",1,0,"L");  
            $pdf->Cell(60,5,"EMAIL",1,0,"L");   
            $pdf->Cell(60,5,"DATE REGISTERED",1,1,"L");
         }
         $paid = 'Not Paid';
        if ($reg['paid'] == true) {
            $paidNum++;
          $paid = 'Paid';
        }
        

       
          $pdf->Cell(35,5,$reg['firstname'],1,0,"L"); 
          $pdf->Cell(35,5,$reg['lastname'],1,0,"L");  
          $pdf->Cell(60,5,$reg['email'],1,0,"L");   
          $pdf->Cell(60,5,$reg['dateregistered'],1,1,"L");


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

$pdf->Output("I", "EventRegistrationReport");
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
