<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);
$partner = new User($db);
$userArr = [];


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
            'SBDC Users', 0, 0, 'C'
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

if (isset($_POST['submitUserRep'])) {
 
    $result = $user->read();

    $userCount = $result->rowCount();

    if ($userCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $usr_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'partnerid' => $partnerid,
                'phone1' => $phone1,
                'hoa' => $hoa,
                'email' => $email,
                'streetaddress' => $streetaddress

            );
            array_push($userArr, $usr_item);
        }
    }

    $pdf = new PDF("L");
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

if ($userCount > 0) {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,5,"FIRST NAME",1,0,"L");
    $pdf->Cell(30,5,"LAST NAME",1,0,"L");
    $pdf->Cell(60,5,"EMAIL",1,0,"L");
    $pdf->Cell(5,5,"H",1,0,"L");
    $pdf->Cell(25,5,"PHONE",1,0,"L");
    $pdf->Cell(60,5,"STREET ADDRESS",1,1,"L");
    $pdf->SetFont('Arial', '', 10);
    foreach ($userArr as $usr) {

        $nameStr = str_pad($usr['firstname'].' '.$usr['lastname'], 35);
        $emailStr = str_pad($usr['email'], 30);
   
        $user_string1 = 
          " ".$nameStr." ".$emailStr." ".$usr['partnerid']." ".$usr['phone1'].
          " ".$usr['streetaddress']." ".$usr['hoa'];

         // $pdf->Cell(0, 5, $user_string1, 0, 1);
         $pdf->Cell(30,5,$usr['firstname'],1,0,"L");
         $pdf->Cell(30,5,$usr['lastname'],1,0,"L");
         $pdf->Cell(60,5,$usr['email'],1,0,"L");
         $pdf->Cell(5,5,$usr['hoa'],1,0,"L");
         $pdf->Cell(25,5,$usr['phone1'],1,0,"L");
         $pdf->Cell(60,5,$usr['streetaddress'],1,1,"L");


    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Users:  ".$userCount, 0, 1);
  
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO Users FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}

$pdf->Output("I", "UserReport");
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
