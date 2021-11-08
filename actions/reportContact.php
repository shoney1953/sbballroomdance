<?php
session_start();
require('../includes/fpdf.php');
include_once '../config/Database.php';
include_once '../models/Contact.php';

$database = new Database();
$db = $database->connect();
$contact = new Contact($db);

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	$this->Image('../img/sbdc_logo_small.png',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(80);
	// Title
	$this->Cell(50,10,'SBDC Contacts',1,0,'C');
	// Line break
	$this->Ln(20);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetTextColor(122, 2, 73);
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
// go get contacts
$result = $contact->read();

$rowCount = $result->rowCount();
$num_contacts = $rowCount;
if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $contact_string = 
          "   ".$firstname.
          " ".$lastname.
          "     ".$email.
           " ";
		$pdf->Cell(0,10,$contactdate,1,1);
        $pdf->Cell(0,10,$contact_string,1,1);
		$pdf->Cell(0,10,"    ".$message,1,1);
    }


} else {
   echo 'NO Contacts';

}

$pdf->Output();

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;
?>
