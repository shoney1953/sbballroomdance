<?php
session_start();
date_default_timezone_set("America/Phoenix");
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/PaymentProduct.php';
require_once '../models/TempOnlineEventReg.php';
require_once '../models/PaymentCustomer.php';


// $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';
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
$potentialReg1 = $_SESSION['potentialReg1'];
if (isset($_SESSION['potentialReg2'])) {
  $potentialReg2 = $_SESSION['potentialReg2'];
}

$_SESSION['partialyearmem'] = 0;
$line_item1 = [];
$line_item2 = [];
$priceObj1 = [];
$priceObj2 = [];
$totalCost = 0;
$database = new Database();
$db = $database->connect();
$paymentcustomer = new PaymentCustomer($db);
$tempReg = new TempOnlineEventReg($db);


if (isset($_POST['submitRegConfirm'])) {
/*  create temp reg in database */
$tempReg->eventid = $potentialReg1['eventid'];
$tempReg->eventname = $potentialReg1['eventname'];
$tempReg->eventtype = $potentialReg1['eventtype'];
$tempReg->eventdate = $potentialReg1['eventdate'];
$tempReg->orgemail = $potentialReg1['orgemail'];
$tempReg->visitor = $potentialReg1['visitor'];
$tempReg->message = $potentialReg1['message'];
$tempReg->ddattenddinner = $potentialReg1['ddattenddinner'];
$tempReg->ddattenddance = 1;
// $tempReg->registeredby = $potentialReg1['email'];
$tempReg->registeredby = $_SESSION['username'];
$tempReg->firstname1 = $potentialReg1['firstname'];
$tempReg->lastname1 = $potentialReg1['lastname'];
$tempReg->email1 = $potentialReg1['email'];


if ($potentialReg1['ddattenddinner'] === 1) {
  $tempReg->mealchoice1 = $potentialReg1['mealchoice'];
  $tempReg->mealdesc1 = $potentialReg1['mealdesc'];
  $tempReg->dietaryrestriction1 = $potentialReg1['dietaryrestriction'];
} else {
  $tempReg->mealchoice1 = 0;
  $tempReg->mealdesc1 = '';
  $tempReg->dietaryrestriction1 = '';
  $tempReg->mealchoice2 = 0;
  $tempReg->mealdesc2 = '';
  $tempReg->dietaryrestriction2 = '';
}

$tempReg->productid1 = $potentialReg1['productid'];
if ($tempReg->visitor != 1) {
  $tempReg->priceid1 = $potentialReg1['priceid'];
  $priceObj1 = $stripe->prices->retrieve($potentialReg1['priceid'], []);
  $totalCost = $priceObj1->unit_amount;
} else {
   $tempReg->priceid1 = $potentialReg1['guestpriceid'];
   $priceObj1 = $stripe->prices->retrieve($potentialReg1['guestpriceid'], []);
   $totalCost = $priceObj1->unit_amount;
}


if ($potentialReg2) {

  $tempReg->firstname2 = $potentialReg2['firstname'];
  $tempReg->lastname2 = $potentialReg2['lastname'];
  $tempReg->email2 = $potentialReg2['email'];
  $tempReg->mealchoice2 = $potentialReg2['mealchoice'];
  $tempReg->mealdesc2 = $potentialReg2['mealdesc'];
  $tempReg->dietaryrestriction2 = $potentialReg2['dietaryrestriction'];
  $tempReg->productid2 = $potentialReg2['productid'];
  if ($tempReg->visitor != 1) {
    $tempReg->priceid2 = $potentialReg2['priceid'];
     $priceObj2 = $stripe->prices->retrieve($potentialReg2['priceid'], []);
     $totalCost = $totalCost + $priceObj2->unit_amount;
  } else {
    $tempReg->priceid2 = $potentialReg2['guestpriceid'];
     $priceObj2 = $stripe->prices->retrieve($potentialReg2['guestpriceid'], []);
    $totalCost = $totalCost + $priceObj2->unit_amount;
  }
 
}
$tempReg->totalcost = $totalCost;
$tempReg->create();
$tempRegID = $db->lastInsertId();
/* */
$searchemail = $potentialReg1['email'];
$qstring = 'email: "'.$searchemail.'"';

$customer = $stripe->customers->search([
  'query' => $qstring,
]);
$cnt = count($customer);

// if stripe customer not found, create one

if (count($customer) == 0) {
  $fullname = $potentialReg1['firstname']||' '||$potentialReg1['lastname'];
  $customer = $stripe->customers->create([
    'name' => $fullname,
    'email' => $potentialReg1['email'],

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
  'success_url' => $YOUR_DOMAIN . '/regsuccess.php?regid='.$tempRegID,
  'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 
} else {
  $checkout_session = \Stripe\Checkout\Session::create([
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'line_items' => [
      ['price' => $tempReg->priceid1, 'quantity' => '1']
    ],
    'customer' => $customer->id,
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/regsuccess.php?regid='.$tempRegID,
    'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 

}

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

?>