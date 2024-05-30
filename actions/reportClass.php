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
        $this->Image('../img/SBDC LOGO.png',8,6,20);
        // Arial bold 15
        $this->SetFont('Arial','B',10);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,8,
            'SBDC Class Registration Report  - '.$today, 0, 1, 'C');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',6);
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
                'classdate2' => $classdate2,
                'classdate3' => $classdate3,
                'classdate4' => $classdate4,
                'classdate5' => $classdate5,
                'classdate6' => $classdate6,
                'classdate7' => $classdate7,
                'classdate8' => $classdate8,
                'classdate9' => $classdate9,
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
    $pdf->SetFont('Arial', '', 10);

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

            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0, 5, $class_string, 0, 1);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(25,8,"FIRST ",1,0,"L"); 
            $pdf->Cell(30,8,"LAST ",1,0,"L");  
            $pdf->Cell(50,8," ",1,0,"L"); 
            $pdf->Cell(10,8," ",1,0,"L"); 
            $pdf->Cell(110,8,"DATES ATTENDED",1,1,"L");  
          
            // $pdf->Cell(80,8,"DATES       ATTENDED",1,1,"L");

            $pdf->Cell(25,8,"NAME",1,0,"L"); 
            $pdf->Cell(30,8,"NAME",1,0,"L");  
            $pdf->Cell(50,8,"EMAIL",1,0,"L"); 
            $pdf->Cell(10,8,"MEM",1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate2'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate3'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate4'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate5'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate6'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate7'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate8'],5,5),1,0,"L"); 
            $pdf->Cell(12,8,substr($reg['classdate8'],5,5),1,1,"L"); 
           
          
        }
        if ($reg['classid'] !== $prevClass) {
            $pdf->SetFont('Arial','B',10);
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
            $pdf->SetFont('Arial','BU',10);
            $pdf->Ln(3);
            $pdf->AddPage('L');
            $pdf->Cell(0, 15, $class_string, 0, 1);
            $pdf->SetFont('Arial', 'B', 10); 

            $pdf->Cell(25,8,"FIRST ",1,0,"L"); 
            $pdf->Cell(30,8,"LAST ",1,0,"L");  
            $pdf->Cell(50,8," ",1,0,"L"); 
            $pdf->Cell(10,8," ",1,0,"L");   
            $pdf->Cell(20,8,$reg['classdate'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate2'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate3'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate4'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate5'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate6'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate7'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate8'],1,0,"L"); 
            $pdf->Cell(20,8,$reg['classdate9'],1,1,"L");     
            // $pdf->Cell(80,8,"DATES       ATTENDED",1,1,"L");

            $pdf->Cell(25,8,"NAME",1,0,"L"); 
            $pdf->Cell(30,8,"NAME",1,0,"L");  
            $pdf->Cell(50,8,"EMAIL",1,0,"L"); 
            $pdf->Cell(10,8,"MEM",1,0,"L"); 


        
         }

        $regCount++;
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(25,8,$reg['firstname'],1,0,"L"); 
        $pdf->Cell(30,8,$reg['lastname'],1,0,"L"); 
        $pdf->SetFont('Arial','',10); 
        $pdf->Cell(50,8,$reg['email'],1,0,"L"); 
        if ($user->getUserName($reg['email'])) {
            $pdf->Cell(10,8,"YES",1,0,"L"); 
            $memReg++;
        } else {
            $pdf->Cell(10,8,"NO",1,0,"L");
            $nonMemReg++; 
        }

        $pdf->SetFont('Arial','',10); 
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,0,"L");
        $pdf->Cell(12,8," ",1,1,"L");

      
       

    }
    /* 6 blank lines at the end */
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,8,' ',1,0,"L"); 
    $pdf->Cell(30,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    $pdf->Cell(50,8,' ',1,0,"L"); 
    $pdf->Cell(10,8,' ',1,0,"L"); 
    $pdf->SetFont('Arial','',10); 
    // $pdf->Cell(80,8," ",1,1,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,0,"L");
    $pdf->Cell(12,8," ",1,1,"L");
    /* */
    $pdf->SetFont('Arial','B',10);
    $pdf->Ln(5);
    $pdf->Cell(0, 5, "Total Registrations for this Class:  ".$regCount, 0, 1); 
    $pdf->Cell(0, 5, "Total Member Registrations:  ".$memReg, 0, 1);
    $pdf->Cell(0, 5, "Total Non Member Registrations:  ".$nonMemReg, 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $regCount == 0;
    $memReg == 0;
    $nonMemReg == 0;
} else {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "ClassRegistrationReport.".$today.".pdf");
}

// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
