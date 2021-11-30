<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
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
        $this->Cell(10,10,
            'SBDC Class Registration Report', 0, 0, 'C');
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
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if (isset($_POST['submitClassRep'])) {
 
    if (isset($_POST['classId'])) {
        if ($_POST['classId'] !== '') {
            $classId = htmlentities($_POST['classId']);
            $result = $classReg->read_ByClassId($classId);
        } else {
        $result = $classReg->read();
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
                'classid' => $classid,
                'classname' => $classname,
                'classdate' => $classdate,
                'classtime' => date('h:i:s A', strtotime($classtime)),
                'userid' => $userid,
                'email' => $email,
                'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
            );
            array_push($regArr, $reg_item);
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(122, 2, 73);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

if ($rowCount > 0) {
    $regCount = 0;
    $prevClass = '';
    $init = 1;
    foreach ($regArr as $reg) {

        $regCount++;
        if ($init == 1) {
            $prevClass = $reg['classid'];
            $init = 0;
            $class_string = ' ------ '.$reg['classname'].'  '
                     .$reg['classdate'].' '
                     .$reg['classtime'].' ------ ';
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0, 10, $class_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
        }
        if ($reg['classid'] !== $prevClass) {
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0, 10, "   Total Registrations for this Class:  ".$regCount, 0, 1); 
            $regCount = 1;
            $prevClass = $reg['classid'];
            $class_string = ' ------ '.$reg['classname'].'  '
            .$reg['classdate'].' '
            .$reg['classtime'].' ------ ';
            $pdf->Ln(5);
            $pdf->Cell(0, 10, $class_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
         }

        $reg_string1 = 
          "  ".$reg['firstname'].
          " ".$reg['lastname'].
          "   ".$reg['email'].
          "  ".$reg['dateregistered'].
          " ";

     
          $pdf->Cell(0, 10, $reg_string1, 0, 1);
       

    }
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0, 10, "   Total Registrations for this Class:  ".$regCount, 0, 1); 
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}

$pdf->Output("I", "ClassRegistrationReport");
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>