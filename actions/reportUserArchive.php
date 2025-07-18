<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/UserArchive.php';

$database = new Database();
$db = $database->connect();
$user = new UserArchive($db);

$userArr = [];
$numHOA1 = 0;
$numHOA2 = 0;
$dateJoined = '';
$dateArchived = '';

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $title = "SaddleBrooke Ballroom Dance Club Archived Members - ".$today;
        $this->Image('../img/SBDC LOGO.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            70,
            10,
            $title, 0, 1, 'C'
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

// if (isset($_POST['submitUserRep'])) {
 
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
                'created' => $created,
                'joinedonline' => $joinedonline,
                'memberorigcreated' => $memberorigcreated,
                'streetaddress' => $streetaddress

            );
            if ($usr_item['hoa'] === '1') {
                $numHOA1++;
            }
            if ($usr_item['hoa'] === '2') {
               $numHOA2++;
           }
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
    $pdf->Cell(5,5,"O",1,0,"L");
    $pdf->Cell(25,5,"PHONE",1,0,"L");
    $pdf->Cell(30,5,"ORG JOINED",1,0,"L");
    $pdf->Cell(30,5,"ARCHIVED",1,1,"L");
    $pdf->SetFont('Arial', '', 10);
    foreach ($userArr as $usr) {
        $dateJoined = substr($usr['memberorigcreated'],0,10);
        $dateArchived = substr($usr['created'],0,10);
         // $pdf->Cell(0, 5, $user_string1, 0, 1);
         $pdf->Cell(30,5,$usr['firstname'],1,0,"L");
         $pdf->Cell(30,5,$usr['lastname'],1,0,"L");
         $pdf->Cell(60,5,$usr['email'],1,0,"L");
         $pdf->Cell(5,5,$usr['hoa'],1,0,"L");
         $pdf->Cell(5,5,$usr['joinedonline'],1,0,"L");
         $pdf->Cell(25,5,$usr['phone1'],1,0,"L");
         $pdf->Cell(30,5,$dateJoined,1,0,"L");
         $pdf->Cell(30,5,$dateArchived,1,1,"L");


    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Archived Members HOA1:  ".$numHOA1, 0, 1);
    $pdf->Cell(0, 5, "Total Archived Members HOA2:  ".$numHOA2, 0, 1);
    $pdf->Cell(0, 5, "Total Archived Members:  ".$userCount, 0, 1);
  
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO Archived Members FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "ArchivedMemberReport".$today);
// }

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>