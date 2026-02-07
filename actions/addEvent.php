<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/PaymentProduct.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'EVENTADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
           if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
        }
       } else {
          if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
       }
}

if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN') ) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
 
   $stripeSecretKey = $_SESSION['prodkey'] ;
}
// setting to test stripe account if testmode or localhost 
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    
  $stripeSecretKey = $_SESSION['testkey'] ;
}
$productDescription = '';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$product = new PaymentProduct($db);

  $newStripeProduct = $stripe->products->create(
    ['name' => $_POST['eventname'],
    'description' => $_POST['eventname']]) ;

  if ($newStripeProduct) {

    $memberpriceID = $stripe->prices->create([
        'product' => $newStripeProduct->id,
         'unit_amount'=> $_POST['eventcost'] * 100,
         'currency' => 'usd',
    ]);
 
    $guestpriceID = $stripe->prices->create([
        'product' => $newStripeProduct->id,
         'unit_amount'=> $_POST['eventguestcost'] * 100,
         'currency' => 'usd',
    ]);
}
    $event->eventname = $_POST['eventname'];
    $event->eventtype = $_POST['eventtype'];
    $event->eventdesc = $_POST['eventdesc'];
    $event->eventdj = $_POST['eventdj'];
    $event->eventform = '';
    $event->eventroom = $_POST['eventroom'];
    $event->eventdate = $_POST['eventdate'];
    $event->eventregopen = $_POST['eventregopen'];
    $event->eventregend = $_POST['eventregend'];
    $event->eventcost = $_POST['eventcost'];
    $event->eventguestcost = $_POST['eventguestcost'];
    $event->eventdinnerregend = $_POST['eventdinnerregend'];
    $event->eventguestpriceid = $guestpriceID->id;
    $event->eventmempriceid = $memberpriceID->id;
    $event->eventproductid = $newStripeProduct->id;
    $event->orgemail = $_POST['orgemail'];
    $event->eventnumregistered = 0;
    $event->create();

    $product->eventid = $db->lastInsertId(); // get new id from new event
    $product->productid = $newStripeProduct->id; // using the id assigned from Stripe
    $product->description = $_POST['eventname'];
    $product->name = $_POST['eventname'];
    $product->price = $_POST['eventcost'] * 100;
    $product->priceid = $memberpriceID->id;

    $product->type = 'dance';


    $product->create(); // create product in our tables

    $redirect = "Location: ".$_SESSION['adminurl']."#events";
     header($redirect);
     exit;

?>