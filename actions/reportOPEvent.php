<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/Event.php';

require_once '../vendor/autoload.php';

$database = new Database();
$db = $database->connect();
$event = new Event($db);


if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
   $stripeSecretKey = $_SESSION['prodkey'] ;

}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}
 
\Stripe\Stripe::setApiKey($stripeSecretKey);

// header('Content-Type: application/json');
$charges = [];
$totalCharges = [];
$searchID = $_POST['eventId'];
      $database = new Database();
     $db = $database->connect();
      $searchID = $_POST['eventId'];

      $event->id = $searchID;
      $event->read_single();   
$stripe = new \Stripe\StripeClient($stripeSecretKey);
$qstring = "status: 'succeeded' AND metadata['eventid']: '".$searchID."'";

   $charges = $stripe->charges->search([
   ['query' => $qstring],
  'limit' => 100
  ]);

       foreach($charges['data'] as $transaction) {
                   $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];
            array_push($totalCharges, $transaction); 

        }
      if ($charges['has_more']) {

      do {
             $charges = $stripe->charges->search([
                'query' => $qstring,
                'page' => $charges['next_page']
              ]);
            foreach($charges['data'] as $transaction) {
                  $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];

               array_push($totalCharges, $transaction); 
               
            }
         } while ($charges['has_more']);
}

class PDF extends FPDF
{
    function Header() {

        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/SBDC LOGO.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            50,
            10,
            'SBDC Event Online Payment Report '.$today, 0, 1, 'C'
        );
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 10);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0,' C');
    }
}
   $totalAmount = 0;
   $totalStripeFees = 0;
    $pdf = new PDF("L");
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
     

    $pdf->Cell(60,10,$event->eventname,0,1,"L");
    $pdf->SetFont('Arial','B',10);
    if (count($totalCharges) > 0) {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,10,"EMAIL",1,0,"L");
    $pdf->Cell(40,10,"DATE",1,0,"L");
    $pdf->Cell(40,10,"AMOUNT CHARGED",1,0,"L");
    $pdf->Cell(40,10,"STRIPE FEE",1,0,"L");
    $pdf->Cell(40,10,"NET",1,1,"L");

    $num = 0;
    $pdf->SetFont('Arial', '', 10);
    foreach($totalCharges as $payment) {
    $num++;
    $pdf->Cell(50,10,$payment['metadata']['email'],1,0,"L");
    $pdf->Cell(40,10,date('Y-m-d', $payment['created']),1,0,"L");
    $pdf->Cell(40,10,number_format($payment['amount']/100, 2),1,0,"L");
    $pdf->Cell(40,10,number_format($payment['stripefee']/100, 2),1,0,"L");
    $net = $payment['amount'] - $payment['stripefee'];
     $pdf->Cell(40,10,number_format($net /100, 2),1,1,"L");
    $totalAmount = $totalAmount + $payment['amount'];
    $totalStripeFees = $totalStripeFees + $payment['stripefee'];

    }
   $pdf->Cell(50,10,"TOTALS FOR ".$num." PAYMENTS",1,0,"L");
    $pdf->Cell(40,10," ",1,0,"L");
    $pdf->Cell(40,10,number_format($totalAmount/100, 2),1,0,"L");
    $pdf->Cell(40,10,number_format($totalStripeFees/100, 2),1,0,"L");
    $netTotal = $totalAmount - $totalStripeFees;
     $pdf->Cell(40,10,number_format($netTotal/100, 2),1,1,"L");
    } else {
        $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(0, 10, "   NO Online Payments found for this event ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
    }
$today = date("m-d-Y");
$pdf->Output("I", $event->eventname."OnlinePaymentReport.".$today.".pdf");
?>