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
$potentialRegG1 = [];
$potentialRegG2 = [];
if (isset($_SESSION['potentialReg1'])) {
$potentialReg1 = $_SESSION['potentialReg1'];
}

if (isset($_SESSION['potentialReg2'])) {
  $potentialReg2 = $_SESSION['potentialReg2'];
}
if (isset($_SESSION['potentialRegG1'])) {
  $potentialRegG1 = $_SESSION['potentialRegG1'];
}
if (isset($_SESSION['potentialRegG2'])) {
  $potentialRegG2 = $_SESSION['potentialRegG2'];
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
    $tempReg->ddattenddinner1 = 0;
    $tempReg->ddattenddinner2 = 0;
    $tempReg->visitor = 0;
    if (isset($potentialReg1['firstname'])) {
      $tempReg->registrationemail = $potentialReg1['registrationemail'];
      $tempReg->eventid = $potentialReg1['eventid'];
      $tempReg->eventname = $potentialReg1['eventname'];
      $tempReg->eventtype = $potentialReg1['eventtype'];
      $tempReg->eventdate = $potentialReg1['eventdate'];
      $tempReg->orgemail = $potentialReg1['orgemail'];
      $tempReg->visitor = $potentialReg1['visitor'];
      $tempReg->message = $potentialReg1['message'];
      $tempReg->ddattenddinner1 = $potentialReg1['ddattenddinner'];
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
    } 
    if (isset($potentialReg2['firstname'])) {
     $tempReg->registrationemail = $potentialReg2['registrationemail'];
      $tempReg->eventid = $potentialReg2['eventid'];
      $tempReg->eventname = $potentialReg2['eventname'];
      $tempReg->eventtype = $potentialReg2['eventtype'];
      $tempReg->eventdate = $potentialReg2['eventdate'];
      $tempReg->orgemail = $potentialReg2['orgemail'];
      $tempReg->visitor = $potentialReg2['visitor'];
      $tempReg->message = $potentialReg2['message'];
      $tempReg->registeredby = $_SESSION['username'];
      $tempReg->ddattenddinner2 = $potentialReg2['ddattenddinner'];
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

      if ((isset($potentialRegG1['firstname'])) && ($potentialRegG1['firstname'] !== '')) {
      $tempReg->registrationemail = $potentialRegG1['registrationemail'];
      $tempReg->eventid = $potentialRegG1['eventid'];
      $tempReg->eventname = $potentialRegG1['eventname'];
      $tempReg->eventtype = $potentialRegG1['eventtype'];
      $tempReg->eventdate = $potentialRegG1['eventdate'];
      $tempReg->orgemail = $potentialRegG1['orgemail'];
           $tempReg->registeredby = $_SESSION['username'];
      // $tempReg->visitor = $potentialRegG1['visitor'];
      $tempReg->message = $potentialRegG1['message'];
      $tempReg->guest1attenddinner = $potentialRegG1['ddattenddinner'];
      $tempReg->ddattenddance = 1;
      $tempReg->guest1firstname = $potentialRegG1['firstname'];
      $tempReg->guest1lastname = $potentialRegG1['lastName'];
      $tempReg->guest1email = $potentialRegG1['email'];

      $tempReg->guest1productid = $potentialRegG1['productid'];
      if ($potentialRegG1['ddattenddinner'] === '1') {
        $tempReg->guest1mealchoice = $potentialRegG1['mealchoice'];
        $tempReg->guest1mealdesc = $potentialRegG1['mealdesc'];
        $tempReg->guest1dr = $potentialRegG1['dietaryrestriction'];
      } else {
        $tempReg->guest1mealchoice = 0;
        $tempReg->guest1mealdesc = '';
        $tempReg->guest1dr = '';
      }
     
        $tempReg->guest1priceid = $potentialRegG1['guestpriceid'];
        $priceObj3 = $stripe->prices->retrieve($potentialRegG1['guestpriceid'], []);
        $totalCost = $totalCost + $priceObj3->unit_amount;
      
     
    } // guest 2

    if ((isset($potentialRegG2['firstname']))  && ($potentialRegG2['firstname'] !== '')) {
      $tempReg->registrationemail = $potentialRegG2['registrationemail'];
      $tempReg->eventid = $potentialRegG2['eventid'];
      $tempReg->eventname = $potentialRegG2['eventname'];
      $tempReg->eventtype = $potentialRegG2['eventtype'];
      $tempReg->eventdate = $potentialRegG2['eventdate'];
      $tempReg->orgemail = $potentialRegG2['orgemail'];
           $tempReg->registeredby = $_SESSION['username'];
      // $tempReg->visitor = $potentialRegG2['visitor'];
      $tempReg->message = $potentialRegG2['message'];
      $tempReg->guest2attenddinner = $potentialRegG2['ddattenddinner'];
      $tempReg->ddattenddance = 1;
      $tempReg->guest2firstname = $potentialRegG2['firstname'];
      $tempReg->guest2lastname = $potentialRegG2['lastName'];
      $tempReg->guest2email = $potentialRegG2['email'];

      $tempReg->guest2productid = $potentialRegG2['productid'];
      if ($potentialRegG2['ddattenddinner'] === '1') {
        $tempReg->guest2mealchoice = $potentialRegG2['mealchoice'];
        $tempReg->guest2mealdesc = $potentialRegG2['mealdesc'];
        $tempReg->guest2dr = $potentialRegG2['dietaryrestriction'];
      } else {
        $tempReg->guest2mealchoice = 0;
        $tempReg->guest2mealdesc = '';
        $tempReg->guest2dr = '';
      }

        $tempReg->guest2priceid = $potentialRegG2['guestpriceid'];
        $priceObj4 = $stripe->prices->retrieve($potentialRegG2['guestpriceid'], []);
        $totalCost = $totalCost + $priceObj4->unit_amount;
      
     
    } // guest 2

$tempReg->totalcost = $totalCost;

$tempReg->create();
$tempRegID = $db->lastInsertId();
/* */

  $searchemail = $tempReg->registrationemail;
  

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
$line_item_array = [];

if ($tempReg->priceid1 != NULL) {
   $line_item_array[] =  array('price' => $tempReg->priceid1, 'quantity' => '1');
}
if ($tempReg->priceid2 != NULL) {
  $line_item_array[] = array('price' => $tempReg->priceid2, 'quantity' => '1'); 
}
  
if ($tempReg->guest1priceid != NULL) {
  $line_item_array[] =  array('price' => $tempReg->guest1priceid, 'quantity' => '1');
}
if ($tempReg->guest2priceid != NULL) {
  $line_item_array[] =  array('price' => $tempReg->guest2priceid, 'quantity' => '1');
}


$checkout_session = \Stripe\Checkout\Session::create([
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'line_items' => $line_item_array,
    'customer' => $customer->id,
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/regsuccess.php?regid='.$tempRegID,
    'cancel_url' => $YOUR_DOMAIN . '/regcancel.php',
  ]); 



    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);




  } // end of submitted if