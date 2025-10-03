<?php
session_start();
date_default_timezone_set("America/Phoenix");
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/PaymentCustomer.php';
require_once '../models/PaymentProduct.php';
$totalCharges = [];
$startDate = 0 ;
$endDate = 0;

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

$stripe = new \Stripe\StripeClient($stripeSecretKey);
// $charges = $stripe->charges->all(['limit' => 3]);
$totalCharges = [];
$searchCustomers = $_SESSION['searchCustomers'];
$charges = '';
$searchEmail = '';
$i = 0;  
if (isset($_POST['submitGetTrans']))  {

foreach ($searchCustomers as $customer) {
   $custCHK = "CCHK".$i;
   $custEmail = "CEMAIL".$i;
    if (isset($_POST["$custCHK"])) {
        $searchEmail =  $_POST["$custEmail"];
     
    }
    $i++;
}

    $charges = $stripe->charges->all([ 'limit' => 100]);

        foreach($charges['data'] as $transaction) {
          if (($transaction['billing_details']['email'] === $searchEmail) || 
               ($transaction['receipt_email'] === $searchEmail)) {
            array_push($totalCharges, $transaction); 
          }

        }
      if ($charges['has_more']) {

      do {
        $ix = count($charges['data']) -1 ;
        $chrgID = $charges['data'][$ix]['id'];

        $charges = $stripe->charges->all(['starting_after' => $chrgID ]);
            foreach($charges['data'] as $transaction) {
              if (($transaction['billing_details']['email'] === $searchEmail) || 
               ($transaction['receipt_email'] === $searchEmail)) {
               array_push($totalCharges, $transaction); 
             }
               
            }
         } while ($charges['has_more']);

}

}

 if (isset($_POST['submitGetTransDate']))  {
    $startDate = strtotime($_POST['startdate']);
     $endDate = strtotime($_POST['enddate']);
     $charges = $stripe->charges->search([
  'query' => 'amount>999 AND metadata[\'order_id\']:\'6735\'',
]);
   $qstring = 'created >= '.$startDate;
   $qstring .= ' AND created <= '.$endDate;

    $charges = $stripe->charges->search([
     'query' => $qstring,
   ]);

        foreach($charges['data'] as $transaction) {
            array_push($totalCharges, $transaction); 

        }
      if ($charges['has_more']) {

      do {
             $charges = $stripe->charges->search([
                'query' => $qstring,
                'page' => $charges['next_page']
              ]);
            foreach($charges['data'] as $transaction) {
               array_push($totalCharges, $transaction); 
               
            }
         } while ($charges['has_more']);
}

       }   



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://js.stripe.com/v3/"></script>
    <title>SBDC Stripe Payment History</title>
</head>
<body>

<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
           echo "<li><a href='index.php'>Back to Home</a></li> ";
      
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='../paymentHist.php'>Back to Payment History</a></li> ";
                echo "<li><a href='../administration.php'>Back to Administration</a></li> ";
               echo "<li><a href='../payments.php'>Back to Payments</a></li> ";
            }

?>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
   <div class="container-section ">
   <section class="content">
    <h1>Stripe Payment History</h1>
    
    <div class="form-grid2">


    <div class="form-grid-div">
        <h2>Online Payments</h2>
        <?php
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th> '; 
                    echo '<th>Date</th> '; 
                    echo '<th>Amount</th> '; 
                    echo '<th>Billing Email</th> '; 
    
                    echo '<th>Receipt URL</th> '; 

                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
            $num = 0;
            if (count($totalCharges) === 0) {
                echo '<tr>';
                echo '<td colspan="5">NO Transactions found with this billing email: '.$searchEmail.'</td>';
                echo '</tr>';
            }
            foreach ($totalCharges as $transaction) {
                $num++;
              echo '<tr>';
                echo "<td>".$num."</td>";
                echo "<td>".date('m/d/Y', $transaction['created'])."</td>";
                echo "<td>$".number_format($transaction['amount']/100, 2)."</td>";
                echo "<td>$".$transaction['billing_details']['email']."</td>";
   
                echo "<td><a target='_blank' href='".$transaction['receipt_url']."'>Click to see Receipt</a></td>";
              echo '</tr>';
            }
             echo '</tbody>';
            echo '</table>';   
            echo '<br>';
          ?>
    </div>
    </div>
   
   </section>
    
   </div>
    <footer >
    <?php
    require '../footer.php';
   ?>
    </footer>
</body>
</html>