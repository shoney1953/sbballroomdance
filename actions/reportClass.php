<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$user = new User($db);
$regArr = [];
$memReg = 0;
$nonMemReg = 0;

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/sbdc_logo_small.png',8,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',16);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(10,8,
            'SBDC Class Registration Report  - '.$today, 0, 0, 'C');
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

    if (isset($_POST['classId'])) {
        if ($_POST['classId'] !== '') {
            $classId = htmlentities($_POST['classId']);
            $result = $classReg->read_ByClassId($classId);
        } else {
        $result = $classReg->read();
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
                'registeredby' => $registeredby,
                'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
            );
            array_push($regArr, $reg_item);
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage('L');
    $pdf->SetFont('Arial', '', 16);

if ($rowCount > 0) {
    $regCount = 0;
    $prevClass = '';
    $init = 1;
    foreach ($regArr as $reg) {

    
        if ($init == 1) {
            $prevClass = $reg['classid'];
            $init = 0;
            $class_string = ' '.$reg['classname'].'  '
                     .$reg['classdate'].' '
                     .$reg['classtime'].' ';

            $pdf->SetFont('Arial','BU',16);
            $pdf->Cell(0, 5, $class_string, 0, 1);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(40,8," ",1,0,"L"); 
            $pdf->Cell(45,8," ",1,0,"L");  
            $pdf->Cell(72,8," ",1,0,"L"); 
            $pdf->Cell(14,8," ",1,0,"L"); 
           
            $pdf->Cell(110,8,"DATES       ATTENDED",1,1,"L");

            $pdf->Cell(40,8,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(45,8,"LAST NAME",1,0,"L");  
            $pdf->Cell(72,8,"EMAIL",1,0,"L"); 
            $pdf->Cell(14,8,"MEM",1,0,"L"); 
            $pdf->Cell(110,8," ",1,1,"L");
          
        }
        if ($reg['classid'] !== $prevClass) {
            $pdf->SetFont('Arial','B',16);
            $pdf->Ln(2);
            $pdf->Cell(0, 5, "Total Registrations for this Class:  ".$regCount, 0, 1); 
            $pdf->Cell(0, 5, "Total Member Registrations:  ".$memReg, 0, 1);
            $pdf->Cell(0, 5, "Total Non Member Registrations:  ".$nonMemReg, 0, 1);
            $regCount = 0;
            $memReg = 0;
            $nonMemReg = 0;
            $prevClass = $reg['classid'];
            $class_string = ' '.$reg['classname'].'  '
            .$reg['classdate'].' '
            .$reg['classtime'].' ';
            $pdf->SetFont('Arial','BU',16);
            $pdf->Ln(3);
            $pdf->AddPage('L');
            $pdf->Cell(0, 15, $class_string, 0, 1);
            $pdf->SetFont('Arial', 'B', 16); 

            $pdf->Cell(40,8," ",1,0,"L"); 
            $pdf->Cell(45,8," ",1,0,"L");  
            $pdf->Cell(72,8," ",1,0,"L"); 
            $pdf->Cell(14,8," ",1,0,"L");       
            $pdf->Cell(110,8,"DATES       ATTENDED",1,1,"L");

            $pdf->Cell(40,8,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(45,8,"LAST NAME",1,0,"L");  
            $pdf->Cell(72,8,"EMAIL",1,0,"L"); 
            $pdf->Cell(14,8,"MEM",1,0,"L"); 
            $pdf->Cell(110,8," ",1,1,"L");

        
         }

        $regCount++;
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(40,8,$reg['firstname'],1,0,"L"); 
        $pdf->Cell(45,8,$reg['lastname'],1,0,"L"); 
        $pdf->SetFont('Arial','',14); 
        $pdf->Cell(72,8,$reg['email'],1,0,"L"); 
        if ($user->getUserName($reg['email'])) {
            $pdf->Cell(14,8,"YES",1,0,"L"); 
            $memReg++;
        } else {
            $pdf->Cell(14,8,"NO",1,0,"L");
            $nonMemReg++; 
        }

        $pdf->SetFont('Arial','',16); 
        $pdf->Cell(110,8," ",1,1,"L");
      
       

    }
    $pdf->SetFont('Arial','B',16);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Registrations for this Class:  ".$regCount, 0, 1); 
    $pdf->Cell(0, 5, "Total Member Registrations:  ".$memReg, 0, 1);
    $pdf->Cell(0, 5, "Total Non Member Registrations:  ".$nonMemReg, 0, 1);
    $pdf->SetFont('Arial', '', 16);
    $regCount == 0;
    $memReg == 0;
    $nonMemReg == 0;
} else {
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 14);
}
$today = date("m-d-Y");
$pdf->Output("I", "ClassRegistrationReport.".$today.".pdf");
}

// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
