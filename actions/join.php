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
$stripe = new \Stripe\StripeClient('sk_test_51IVzJTL8mOGPmzyGuvJjZymxynpVAmNHkWGOT42oYyGMEMG3hf94zkJ8bvbUgHVEwGH5wJxWKRd6PkZBnYwd9ChL003mJKdQxN');
$YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';   
}
$memberProducts = $_SESSION['memberproducts'];
$_SESSTION['potentialMem1'] = [];
$_SESSTION['potentialMem2'] = [];
$potentialMem1 = [];
$potentialMem2 = [];
$database = new Database();
$db = $database->connect();
$paymentcustomer = new PaymentCustomer($db);
$chargeProductID = '';
$chargePriceID = '';

if (isset($_POST['submitMembership'])) {
  
  $chargeProductID = $_POST['indprodid'];
  $chargePriceID = trim($_POST['indpriceid']);
  

  if (isset($_POST['firstname1'])) {
    $potentialMem1['firstname'] = $_POST['firstname1'];
  }
  if (isset($_POST['lastname1'])) {
    $potentialMem1['lastname'] = $_POST['lastname1'];
  }
  if (isset($_POST['email1'])) {
    $potentialMem1['email'] = $_POST['email1'];
  }  
  if (isset($_POST['phone1'])) {
    $potentialMem1['phone1'] = $_POST['phone1'];
  }
  if (isset($_POST['streetaddress1'])) {
    $potentialMem1['streetaddress'] = $_POST['streetaddress1'];
  } 
  if (isset($_POST['state1'])) {
    $potentialMem1['state'] = $_POST['state1'];
  }
  if (isset($_POST['city1'])) {
    $potentialMem1['city'] = $_POST['city1'];
  } 
  if (isset($_POST['zip1'])) {
    $potentialMem1['zip'] = $_POST['zip1'];
  }
  if (isset($_POST['hoa1'])) {
    $potentialMem1['hoa'] = $_POST['hoa1'];
  }
  if (isset($_POST['fulltime1'])) {
    $potentialMem1['fulltime'] = $_POST['fulltime1'];
  }
  // 2nd member specified
  if (isset($_POST['addmem2'])) {
   if (isset($_POST['firstname2'])) {
    if ($_POST['firstname2'] !== ' ') {

    $chargeProductID = $_POST['coupleprodid'];
    $chargePriceID = trim($_POST['couplepriceid']);
    
     $potentialMem2['firstname'] = $_POST['firstname2'];
   
    if (isset($_POST['lastname2'])) {
      $potentialMem2['lastname'] = $_POST['lastname2'];
    
    }
    if (isset($_POST['email2'])) {
      $potentialMem2['email'] = $_POST['email2'];
    }  
    if (isset($_POST['phone2'])) {
      $potentialMem2['phone1'] = $_POST['phone2'];
    }
    if (isset($_POST['mem2sameadd'])) {
        if (isset($_POST['streetaddress1'])) {
          $potentialMem2['streetaddress'] = $_POST['streetaddress1'];
        } 
        if (isset($_POST['state1'])) {
          $potentialMem2['state'] = $_POST['state1'];
        }
        if (isset($_POST['city1'])) {
          $potentialMem2['city'] = $_POST['city1'];
        } 
        if (isset($_POST['zip1'])) {
          $potentialMem2['zip'] = $_POST['zip1'];
        }
        if (isset($_POST['hoa1'])) {
          $potentialMem2['hoa'] = $_POST['hoa1'];
        }
        if (isset($_POST['fulltime1'])) {
          $potentialMem2['fulltime'] = $_POST['fulltime1'];
        }
     } else {
      if (isset($_POST['streetaddress2'])) {
        $potentialMem2['streetaddress'] = $_POST['streetaddress2'];
      } 
      if (isset($_POST['state2'])) {
        $potentialMem2['state'] = $_POST['state2'];
      }
      if (isset($_POST['city2'])) {
        $potentialMem2['city'] = $_POST['city2'];
      } 
      if (isset($_POST['zip2'])) {
        $potentialMem2['zip'] = $_POST['zip2'];
      }
      if (isset($_POST['hoa2'])) {
        $potentialMem2['hoa'] = $_POST['hoa2'];
      }
      if (isset($_POST['fulltime2'])) {
        $potentialMem2['fulltime'] = $_POST['fulltime2'];
      }
    }
  }
     }
  //     
   }
   $_SESSION['potentialMember1'] = $potentialMem1;
   $_SESSION['potentialMember2'] = $potentialMem2;
//  $qstring =  "email:\'".$potentialMem1['email']."\' '";

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
  'success_url' => $YOUR_DOMAIN . '/joinsuccess.php',
  'cancel_url' => $YOUR_DOMAIN . '/joincancel.php',
]);  

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

  } // end of submitted if

?>