<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/MemberPaid.php';

$database = new Database();
$db = $database->connect();
$mem = new MemberPaid($db);

$memArr = [];
$totPaid = 0;


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
            'SBDC Membership', 0, 0, 'C'
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

if (isset($_POST['submitPaidRep'])) {
    $year = $_POST['year'];

    $result = $mem->read_byYear($year);
   

    $memCount = $result->rowCount();
  
    if ($memCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $mem_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'year' => $year,
                'paid' => $paid
   
            );
            array_push($memArr, $mem_item);
        }
    }

    $pdf = new PDF("L");
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

if ($memCount > 0) {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,5,"YEAR",1,0,"L");
    $pdf->Cell(30,5,"FIRST NAME",1,0,"L");
    $pdf->Cell(30,5,"LAST NAME",1,0,"L");
    $pdf->Cell(20,5,"PAID",1,1,"L");
    $pdf->SetFont('Arial', '', 10);
    foreach ($memArr as $mem) {


         // $pdf->Cell(0, 5, $user_string1, 0, 1);
         $pdf->Cell(20,5,$mem['year'],1,0,"L");
         $pdf->Cell(30,5,$mem['firstname'],1,0,"L");
         $pdf->Cell(30,5,$mem['lastname'],1,0,"L");
         if ($mem['paid'] == 1) {
            $pdf->Cell(20,5,"YES",1,1,"L");
            $totPaid++;
         } else {
            $pdf->Cell(20,5,"NO",1,1,"L");
         }
  


    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Members:  ".$memCount, 0, 1);
    $pdf->Cell(0, 5, "Total Members Paid:  ".$totPaid, 0, 1);
  
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO Member Paid Records FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}

$pdf->Output("I", "MembershipReport");
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
