<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/secrets.php';
require_once '../config/Database.php';

require_once '../models/PaymentProduct.php';
require_once '../models/PaymentCustomer.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
$stripe = new \Stripe\StripeClient($stripeSecretKey);
$YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';
$memberProducts = $_SESSION['memberproducts'];


$database = new Database();
$db = $database->connect();
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

 

$checkout_session = \Stripe\Checkout\Session::create([
   # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
  'line_items' => [[
    'price' => $chargePriceID,
    'quantity' => 1,
  ]],
  'customer' => $customer->id,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/renewsuccess.php',
  'cancel_url' => $YOUR_DOMAIN . '/renewcancel.php',
]);  

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

?>