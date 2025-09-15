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
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}
\Stripe\Stripe::setApiKey($stripeSecretKey);
// header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$potentialReg1 = [];
$potentialReg2 = [];
if (isset($_SESSION['potentialReg1'])) {
$potentialReg1 = $_SESSION['potentialReg1'];
}

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

    if (isset($potentialReg1['firstname'])) {
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
      $tempReg->lastname1 = $potentialReg1['lastName'];
      $tempReg->email1 = $potentialReg1['email'];


      if ($potentialReg1['ddattenddinner'] === '1') {
        $tempReg->mealchoice1 = $potentialReg1['mealchoice'];
        $tempReg->mealdesc1 = $potentialReg1['mealdesc'];
        $tempReg->dietaryrestriction1 = $potentialReg1['dietaryrestriction'];
      } else {
        $tempReg->mealchoice1 = 0;
        $tempReg->mealdesc1 = '';
        $tempReg->dietaryrestriction1 = '';
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
    } // potential reg1var_dump($potentialReg2);
    if (isset($potentialReg2['firstname'])) {
  
      $tempReg->eventid = $potentialReg2['eventid'];
      $tempReg->eventname = $potentialReg2['eventname'];
      $tempReg->eventtype = $potentialReg2['eventtype'];
      $tempReg->eventdate = $potentialReg2['eventdate'];
      $tempReg->orgemail = $potentialReg2['orgemail'];
      $tempReg->visitor = $potentialReg2['visitor'];
      $tempReg->message = $potentialReg2['message'];
      $tempReg->ddattenddinner = $potentialReg2['ddattenddinner'];
      $tempReg->ddattenddance = 1;
      $tempReg->firstname2 = $potentialReg2['firstname'];
      $tempReg->lastname2 = $potentialReg2['lastName'];
      $tempReg->email2 = $potentialReg2['email'];

      $tempReg->productid2 = $potentialReg2['productid'];
      if ($potentialReg2['ddattenddinner'] === '1') {
        $tempReg->mealchoice2 = $potentialReg2['mealchoice'];
        $tempReg->mealdesc2 = $potentialReg2['mealdesc'];
        $tempReg->dietaryrestriction2 = $potentialReg2['dietaryrestriction'];
      } else {
        $tempReg->mealchoice2 = 0;
        $tempReg->mealdesc2 = '';
        $tempReg->dietaryrestriction2 = '';
      }
      if ($tempReg->visitor != 1) {
        $tempReg->priceid2 = $potentialReg2['priceid'];
        $priceObj2 = $stripe->prices->retrieve($potentialReg2['priceid'], []);
        $totalCost = $totalCost + $priceObj2->unit_amount;
      } else {
        $tempReg->priceid2 = $potentialReg2['guestpriceid'];
        $priceObj2 = $stripe->prices->retrieve($potentialReg2['guestpriceid'], []);
        $totalCost = $totalCost + $priceObj2->unit_amount;
      }
     
    } // potential reg2


$tempReg->totalcost = $totalCost;

$tempReg->create();
$tempRegID = $db->lastInsertId();
/* */
if (isset($potentialReg1['email'])) {
  $searchemail = $potentialReg1['email'];
} else {
  if (isset($potentialReg2['email'])) {
  $searchemail = $potentialReg2['email'];
  }
}

$qstring = 'email: "'.$searchemail.'"';

$customer = $stripe->customers->search([
  'query' => $qstring,
]);
$cnt = count($customer);

// if stripe customer not found, create one

if (count($customer) == 0) {
  if (isset($potentialReg1['firstname'])) {
    $fullname = $potentialReg1['firstname']||' '||$potentialReg1['lastname'];
    $customer = $stripe->customers->create([
      'name' => $fullname,
      'email' => $potentialReg1['email'],

    ]);
  } else {

    if (isset($potentialReg2['firstname'])) {
      $fullname = $potentialReg2['firstname']||' '||$potentialReg2['lastname'];
      $customer = $stripe->customers->create([
        'name' => $fullname,
        'email' => $potentialReg2['email'],

      ]);
    }
  }

}


if (($tempReg->priceid2 !== NULL) && ($tempReg->priceid1 !== NULL)) {

  $checkout_session = \Stripe\Checkout\Session::create([
   # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
  'line_items' => [
    ['price' => $tempReg->priceid1, 'quantity' => '1'],
    ['price' => $tempReg->priceid2, 'quantity' => '1']
  ],
  'customer' => $customer->id,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/regsuccesst.php?regid='.$tempRegID,
  'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 
} else {
  if ($tempReg->priceid1 !== NULL) {
 
    $checkout_session = \Stripe\Checkout\Session::create([ 
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'line_items' => [
      ['price' => $tempReg->priceid1, 'quantity' => '1']
    ],
    'customer' => $customer->id,
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/regsuccesst.php?regid='.$tempRegID,
    'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 
  }
  if ($tempReg->priceid2 !== NULL) {
  
    $checkout_session = \Stripe\Checkout\Session::create([ 
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'line_items' => [
      ['price' => $tempReg->priceid2, 'quantity' => '1']
    ],
    'customer' => $customer->id,
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/regsuccesst.php?regid='.$tempRegID,
    'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 
  }

}

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if