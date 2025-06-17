<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/PaymentProduct.php';
require_once '../models/TempOnlineMember.php';
require_once '../models/PaymentCustomer.php';


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
// header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$_SESSION['partialyearmem'] = 0;
$memberProducts = $_SESSION['memberproducts'];
$potentialMem1 = $_SESSION['potentialMember1'];
$potentialMem2 = $_SESSION['potentialMember2'];
 $member1ID = 0;
 $member2ID = 0;
$database = new Database();
$db = $database->connect();
$paymentcustomer = new PaymentCustomer($db);
$tempmember1 = new TempOnlineMember($db);
$tempmember2 = new TempOnlineMember($db);
$chargeProductID = $_SESSION['chargeProductID'];
$chargePriceID = $_SESSION['chargePriceID'];

if (isset($_POST['submitJoinConfirm'])) {
/*  create temp member in database */
$tempmember1->firstname = $potentialMem1['firstname'];
$tempmember1->lastname = $potentialMem1['lastname'];
$tempmember1->email = $potentialMem1['email'];
$tempmember1->phone = $potentialMem1['phone1'];
$tempmember1->streetAddress = $potentialMem1['streetaddress'];
$tempmember1->city = $potentialMem1['city'];
$tempmember1->state = $potentialMem1['state'];
$tempmember1->zip = $potentialMem1['zip'];
$tempmember1->hoa = $potentialMem1['hoa'];
$tempmember1->fulltime = $potentialMem1['fulltime'];
$tempmember1->directorylist = $potentialMem1['directorylist'];

$tempmember1->create();
$member1ID = $db->lastInsertId();
if ($potentialMem2) {
  $tempmember2->firstname = $potentialMem2['firstname'];
$tempmember2->lastname = $potentialMem2['lastname'];
$tempmember2->email = $potentialMem2['email'];
$tempmember2->phone = $potentialMem2['phone1'];
$tempmember2->streetAddress = $potentialMem2['streetaddress'];
$tempmember2->city = $potentialMem2['city'];
$tempmember2->state = $potentialMem2['state'];
$tempmember2->zip = $potentialMem2['zip'];
$tempmember2->hoa = $potentialMem2['hoa'];
$tempmember2->fulltime = $potentialMem2['fulltime'];
$tempmember2->directorylist = $potentialMem2['directorylist'];

$tempmember2->create();
$member2ID = $db->lastInsertId();
}

/* */
$searchemail = $potentialMem1['email'];
$qstring = 'email: "'.$searchemail.'"';

$customer = $stripe->customers->search([
  'query' => $qstring,
]);
$cnt = count($customer);

// if stripe customer not found, create one

if (count($customer) == 0) {
  $fullname = $potentialMem1['firstname']||' '||$potentialMem1['lastname'];

  $customer = $stripe->customers->create([
    'name' => $fullname,
    'email' => $potentialMem1['email'],
    'phone' => $potentialMem1['phone1'],
    'address' => [
      'line1' => $potentialMem1['streetaddress'],
      'city' => $potentialMem1['city'],
      'state' => $potentialMem1['state'],
      'postal_code' => $potentialMem1['zip'],
    ]
  ]);
  // create in our database to correspond
  $paymentcustomer->customerid = $customer->id;
  $paymentcustomer->email = $customer->email;
  $paymentcustomer->firstname = $potentialMem1['firstname'];
  $paymentcustomer->lastname = $potentialMem1['lastname'];
  $paymentcustomer->userid = 0;
  $paymentcustomer->create();

}


$checkout_session = \Stripe\Checkout\Session::create([
   # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
  'line_items' => [[
    'price' => $chargePriceID,
    'quantity' => 1,
  ]],
  'customer' => $customer->id,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/joinsuccess.php?id1='.$member1ID.'&id2='.$member2ID,
  'cancel_url' => $YOUR_DOMAIN . '/joincancel.php',
]);  

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

?>