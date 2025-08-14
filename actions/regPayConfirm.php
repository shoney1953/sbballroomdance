<?php
session_start();
date_default_timezone_set("America/Phoenix");
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/TempOnlineRegPay.php';
require_once '../models/PaymentCustomer.php';

if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
   $stripeSecretKey = $_SESSION['prodkey'] ;
   if (($_SESSION['testmode'] === 'YES') && (isset($_SESSION['testmode']))) {
    $stripeSecretKey = $_SESSION['testkey'] ;
   }
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}
\Stripe\Stripe::setApiKey($stripeSecretKey);
// header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$payReg = $_SESSION['payreg'];


$totalCost = 0;
$database = new Database();
$db = $database->connect();
$paymentcustomer = new PaymentCustomer($db);
$tempReg = new TempOnlineRegPay($db);

if (isset($_POST['submitRegPay'])) {
/*  create temp reg in database */
$tempReg->eventid = $payReg['eventid'];
$tempReg->eventname = $payReg['eventname'];
$tempReg->eventtype = $payReg['eventtype'];
$tempReg->eventdate = $payReg['eventdate'];
$tempReg->ddattenddinner = $payReg['ddattenddinner'];
$tempReg->firstname1 = $payReg['firstname1'];
$tempReg->lastname1 = $payReg['lastname1'];
$tempReg->email1 = $payReg['email1'];
$tempReg->mealchoice1 = $payReg['mealchoice1'];
$tempReg->mealdesc1 = $payReg['mealdesc1'];
$tempReg->productid1 = $payReg['productid1'];
$tempReg->priceid1 = $payReg['priceid1'];
$tempReg->firstname2 = $payReg['firstname2'];
$tempReg->lastname2 = $payReg['lastname2'];
$tempReg->email2 = $payReg['email2'];
$tempReg->mealchoice2 = $payReg['mealchoice2'];
$tempReg->mealdesc2 = $payReg['mealdesc2'];
$tempReg->productid2 = $payReg['productid2'];
$tempReg->priceid2 = $payReg['priceid2'];
$tempReg->totalcost = $payReg['totalcost'];
$tempReg->regid1 = $payReg['regid1'];
$tempReg->regid2 = $payReg['regid2'];
$tempReg->cost1 = $payReg['cost1'];
$tempReg->cost2 = $payReg['cost2'];
$tempReg->create();
$tempRegID = $db->lastInsertId();
/* */
$searchemail = $payReg['email1'];
$qstring = 'email: "'.$searchemail.'"';

$customer = $stripe->customers->search([
  'query' => $qstring,
]);
$cnt = count($customer);

// if stripe customer not found, create one

if (count($customer) == 0) {
  $fullname = $payReg['firstname1']||' '||$payReg['lastname1'];
  $customer = $stripe->customers->create([
    'name' => $fullname,
    'email' => $payReg['email1'],

  ]);
}



if ($tempReg->priceid2 !== NULL) {
  $checkout_session = \Stripe\Checkout\Session::create([
   # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
  'line_items' => [
    ['price' => $tempReg->priceid1, 'quantity' => '1'],
    ['price' => $tempReg->priceid2, 'quantity' => '1']
  ],
  'customer' => $customer->id,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/regPaysuccess.php?regid='.$tempRegID,
  'cancel_url' => $YOUR_DOMAIN . '/regPaycancel.php',
  ]); 
} else {
  $checkout_session = \Stripe\Checkout\Session::create([
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'line_items' => [
      ['price' => $tempReg->priceid1, 'quantity' => '1']
    ],
    'customer' => $customer->id,
    'mode' => 'payment', 
    'success_url' => $YOUR_DOMAIN . '/regPaysuccess.php?regid='.$tempRegID,
    'cancel_url' => $YOUR_DOMAIN . '/regPaycancel.php',
  ]); 

}

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

 ?>