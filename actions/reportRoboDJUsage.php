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
$neverLoggedOn = 0;
$robodjlogins = 0;


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
            'SBDC User Login by Num Robo DJ Logins - '.$today, 0, 1, 'C'
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

if (isset($_POST['submitRoboDJRep'])) {
 
    $result = $user->readByNumRoboDJLogins();

    $userCount = $result->rowCount();

    if ($userCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $usr_item = array(
                'id' => $id,
                'lastLogin' => $lastLogin,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'numlogins' => $numlogins,
                'robodjnumlogins' => $robodjnumlogins,
                'robodjlastlogin' => $robodjlastlogin


            );
            if ($usr_item['robodjnumlogins'] > 0) {
               $robodjlogins++;
               array_push($userArr, $usr_item);
            }
            

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

    $pdf->Cell(35,5,"ROBODJ LOGINS",1,0,"L");
    $pdf->Cell(40,5,"ROBODJ LAST LOGIN",1,1,"L");


    $pdf->SetFont('Arial', '', 10);
    foreach ($userArr as $usr) {


         // $pdf->Cell(0, 5, $user_string1, 0, 1);
         $pdf->Cell(30,5,$usr['firstname'],1,0,"L");
         $pdf->Cell(30,5,$usr['lastname'],1,0,"L");
 

         $pdf->Cell(35,5,$usr['robodjnumlogins'],1,0,"L");
         $pdf->Cell(40,5,substr($usr['robodjlastlogin'],0,10),1,1,"L");
        

    }
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);

    $pdf->Cell(0, 5, "Num Members Logged into ROBODJ:  ".$robodjlogins, 0, 1);
  
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO Members FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "UsageReport.".$today);
}

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>
