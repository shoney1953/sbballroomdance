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
if (isset($_POST['submitGetTransEvent']))  {

  $i = 0;
  $searchID = 0;
  $searchEvents = $_SESSION['searchEvents'];
  foreach($searchEvents as $event) {
        $eventCHK = "EVCHK".$i;

        if (isset($eventCHK)) {
          $searchID = $event['id'];
          break;

        }

                  $i++;
  }


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
} // end get trans Event
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
          if ($transaction['status'] === 'succeeded') {

          if (($transaction['billing_details']['email'] === $searchEmail) || 
               ($transaction['receipt_email'] === $searchEmail)) {
                  $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];
            array_push($totalCharges, $transaction); 
          }

        }
      if ($charges['has_more']) {

      do {
        $ix = count($charges['data']) -1 ;
        $chrgID = $charges['data'][$ix]['id'];

        $charges = $stripe->charges->all(['starting_after' => $chrgID ]);
            foreach($charges['data'] as $transaction) {
               if ($transaction['status'] === 'succeeded') {
              if (($transaction['billing_details']['email'] === $searchEmail) || 
               ($transaction['receipt_email'] === $searchEmail)) {
                  $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];
               array_push($totalCharges, $transaction); 
             }
               }
            }
         } while ($charges['has_more']);
      } // has more

        } // status
}  // email

 if (isset($_POST['submitGetTransTypeMembership']))  {

   $charges = $stripe->charges->search([
   ['query' => 'status: \'succeeded\' AND metadata[\'transtype\']:\'membership\''],
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
 } // membership

 if (isset($_POST['submitGetTransEvents']))  {

   $charges = $stripe->charges->search([
   ['query' => 'status: \'succeeded\' AND metadata[\'transtype\']:\'event\''],
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
 } // events
 if (isset($_POST['submitGetTransDate']))  {
    $startDate = strtotime($_POST['startdate']);
     $endDate = strtotime($_POST['enddate']);

   $qstring = 'created >= '.$startDate;
   $qstring .= ' AND created <= '.$endDate;
   
    $charges = $stripe->charges->search([
     'query' => $qstring,
   ]);

        foreach($charges['data'] as $transaction) {
          if ($transaction['status'] === 'succeeded') {
                   $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];
         
            
            array_push($totalCharges, $transaction); 

        }
        }
      if ($charges['has_more']) {

      do {
             $charges = $stripe->charges->search([
                'query' => $qstring,
                'page' => $charges['next_page']
              ]);
            foreach($charges['data'] as $transaction) {
                 if ($transaction['status'] === 'succeeded') {
                  $balanceTransaction = $stripe->balanceTransactions->retrieve(
                   $transaction['balance_transaction'],
                 []
                 );
          
                 $transaction['stripefee'] = $balanceTransaction['fee'];

               array_push($totalCharges, $transaction); 

            }  // trans succeeded
            } // for each
         } while ($charges['has_more']);
      } // end do
      } // date





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
           echo "<li><a href='../index.php'>Back to Home</a></li> ";
      
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
        $totalCharged = 0;
        $totalStripeFees = 0;
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th> '; 
                    echo '<th>Date</th> '; 
                    echo '<th>Amount Paid</th> '; 
                     echo '<th>Stripe Fee</th> '; 
                    echo '<th>Billing Email</th> '; 
                     echo '<th>Type</th> '; 
                    echo '<th>Description</th> '; 
                    echo '<th>Receipt URL</th> '; 

                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
            $num = 0;
            if (count($totalCharges) === 0) {
                echo '<tr>';
                echo '<td colspan="5">NO Transactions found</td>';
                echo '</tr>';
            }
            foreach ($totalCharges as $transaction) {
              $totalCharged = $totalCharged + $transaction['amount'];
                 $totalStripeFees = $totalStripeFees + $transaction['stripefee'];
                $num++;
              echo '<tr>';
                echo "<td>".$num."</td>";
                echo "<td>".date('m/d/Y', $transaction['created'])."</td>";
                echo "<td>$".number_format($transaction['amount']/100, 2)."</td>";
                echo "<td>$".number_format($transaction['stripefee']/100, 2)."</td>";
                echo "<td>".$transaction['billing_details']['email']."</td>";
                echo "<td>".$transaction['metadata']['transtype']."</td>";
                echo "<td>".$transaction['description']."</td>";
                echo "<td><a target='_blank' href='".$transaction['receipt_url']."'>Click to see Receipt</a></td>";
              echo '</tr>';
            }
               echo '<tr>';
               echo '<td colspan="8">TOTALS</td>';

                   echo '</tr>';
                echo "<td>".$num."</td>";
                echo "<td></td>";
                echo "<td>$".number_format($totalCharged/100, 2)."</td>";
                echo "<td>$".number_format($totalStripeFees/100, 2)."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
              echo '</tr>';
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