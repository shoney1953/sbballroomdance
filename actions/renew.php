<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/TempOnlineRenewal.php';
require_once '../models/PaymentProduct.php';
require_once '../models/PaymentCustomer.php';

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';

if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
   $stripeSecretKey = $_SESSION['prodkey'] ;
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}

\Stripe\Stripe::setApiKey($stripeSecretKey);
$stripe = new \Stripe\StripeClient($stripeSecretKey);

$memberProducts = $_SESSION['memberproducts'];


$database = new Database();
$db = $database->connect();
$tempOnlineRenewal = new TempOnlineRenewal($db);
$paymentcustomer = new PaymentCustomer($db);

$chargeProductID = '';
$chargePriceID = '';
$searchemail = $_SESSION['useremail'];
$qstring = 'email: "'.$searchemail.'"';

$customer = $stripe->customers->search([
  'query' => $qstring,
]);
$cnt = count($customer);

// if stripe customer not found, create one

if (count($customer) == 0) {
  $fullname = $_SESSION['userfirstname']||' '||$_SESSION['userlastname'];

  $customer = $stripe->customers->create([
    'name' => $fullname,
    'email' => $_SESSION['useremail']
   
  ]);
  // create in our database to correspond
  $paymentcustomer->customerid = $customer->id;
  $paymentcustomer->email = $customer->email;
  $paymentcustomer->firstname = $_SESSION['userfirstname'];
  $paymentcustomer->lastname = $_SESSION['userlastname'];
  $paymentcustomer->userid = $_SESSION['userid'];
  $paymentcustomer->create();

}


if (isset($_POST['submitRenewal'])) {
  if (isset($_POST['renewmem2'])) {
    $chargeProductID = $_POST['coupleprodid'];
    $chargePriceID = trim($_POST['couplepriceid']);
    $_SESSION['renewboth'] = 1;
  } else {
    $chargeProductID = $_POST['indprodid'];
    $chargePriceID = trim($_POST['indpriceid']);
    $_SESSION['renewboth'] = 0;
  }

$tempOnlineRenewal->userid = $_SESSION['userid']; 
if (isset($_SESSION['partnerid'])) {
  $tempOnlineRenewal->partnerid = $_SESSION['partnerid'];
} else {
   $tempOnlineRenewal->partnerid = 0;
}
$tempOnlineRenewal->renewthisyear = $_SESSION['renewThisYear']; 
$tempOnlineRenewal->renewnextyear = $_SESSION['renewNextYear']; 
$tempOnlineRenewal->renewboth = $_SESSION['renewboth'];

$tempOnlineRenewal->create();
$renewID = $db->lastInsertId();
$checkout_session = \Stripe\Checkout\Session::create([
   # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
  'line_items' => [[
    'price' => $chargePriceID,
    'quantity' => 1,
  ]],
  'customer' => $customer->id,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/renewsuccess.php?renewalid='.$renewID,
  'cancel_url' => $YOUR_DOMAIN . '/renewcancel.php',
]);  

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

?>