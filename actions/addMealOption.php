<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/PaymentProduct.php';
require_once '../models/Event.php';

$productDescription = '';
var_dump('inside add meal');
var_dump($_SESSION['role']);
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
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
        if (($_SESSION['role'] != 'EVENTADMIN') && ($_SESSION['role'] != 'SUPERADMIN') ) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
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
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
 
   $stripeSecretKey = $_SESSION['prodkey'] ;
}
// setting to test stripe account if testmode or localhost 
if ($_SERVER['SERVER_NAME'] === 'localhost')  {
  $stripeSecretKey = $_SESSION['testkey'] ;
}


\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);

$database = new Database();
$db = $database->connect();
$event = new Event($db);
$event->id = $_POST['eventId'];
$event->read_single();
$mealChoices = new DinnerMealChoices($db);
$product = new PaymentProduct($db);

if (isset($_POST['submitAddMeals'])) {

     if (isset($_POST['meal1'])  && ($_POST['meal1']) !== '') {

        $product->description = $_POST['mealdesc1'];
        $productName = $event->eventname;
        $productName .= ' ';
        $productName .= $_POST['meal1'];
        $product->name = $productName;
     

    if (isset($_POST['memberprice1'])) {
        $product->price = $_POST['memberprice1'];
    }

   $product->type = 'meal';

    $eventid = (string)$_POST['eventId'];

    $newProduct1 = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description,
     ['metadata' => ['eventid' => $eventid,
                    'producttype' => 'meal']]]) ;

  if ($newProduct1) {
    $product->productid = $newProduct1->id; // using the id assigned from Stripe
    $memberpriceID1 = $stripe->prices->create([
        'product' => $newProduct1->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);
    $guestpriceID1 = $stripe->prices->create([
        'product' => $newProduct1->id,
         'unit_amount'=> $_POST['guestprice1'],
         'currency' => 'usd',
    ]);

    $mealChoices->mealname = $_POST['meal1'];
    $mealChoices->mealdescription = $_POST['mealdesc1'];
    $mealChoices->memberprice = $_POST['memberprice1'];
    $mealChoices->guestprice = $_POST['guestprice1'];
    $mealChoices->priceid = $memberpriceID1->id;
    $mealChoices->guestpriceid = $guestpriceID1->id;
    $mealChoices->productid = $newProduct1->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct1->id;
   $product->priceid = $memberpriceID1->id;
   $product->eventid = $_POST['eventId'];
   $product->create();
  }
     } // end of meal choice 1
    if (isset($_POST['meal2'])  && ($_POST['meal2']) !== '') {

  
        $product->description = $_POST['mealdesc2'];
        $productName = $event->eventname;
        $productName .= ' ';
        $productName .= $_POST['meal2'];
        $product->name = $productName;
    
    
    if (isset($_POST['memberprice2'])) {
        $product->price = $_POST['memberprice2'];        
    }

       
  $newProduct2 = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description,
    ['metadata' => ['eventid' => $eventid,
                    'producttype' => 'meal']]]) ;

  if ($newProduct2) {
    
    $product->productid = $newProduct2->id; // using the id assigned from Stripe
    $memberpriceID = $stripe->prices->create([
        'product' => $newProduct2->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);

    $guestpriceID = $stripe->prices->create([
        'product' => $newProduct2->id,
         'unit_amount'=> $_POST['guestprice2'],
         'currency' => 'usd',
    ]);
    $mealChoices->mealname = $_POST['meal2'];
    $mealChoices->mealdescription = $_POST['mealdesc2'];
    $mealChoices->memberprice = $_POST['memberprice2'];
    $mealChoices->guestprice = $_POST['guestprice2'];
    $mealChoices->priceid = $memberpriceID->id;
    $mealChoices->guestpriceid = $guestpriceID->id;
    $mealChoices->productid = $newProduct2->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct2->id;
   $product->priceid = $memberpriceID2->id;
   $product->eventid = $_POST['eventId'];
   $product->create();
  }
    } // end of meal choice 2

   if (isset($_POST['meal3'])  && ($_POST['meal3']) !== '') {
 
        $product->description = $_POST['mealdesc3'];
        $productName = $event->eventname;
        $productName .= ' ';
        $productName .= $_POST['meal3'];
        $product->name = $productName;
   
    if (isset($_POST['memberprice3'])) {
        $product->price = $_POST['memberprice3'];
    }

    
  $newProduct3 = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description,
     ['metadata' => ['eventid' => $eventid,
                    'producttype' => 'meal']]]) ;

  if ($newProduct3) {
    $product->productid = $newProduct3->id; // using the id assigned from Stripe
    $memberpriceID3 = $stripe->prices->create([
        'product' => $newProduct3->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);

    $guestpriceID3 = $stripe->prices->create([
        'product' => $newProduct3->id,
         'unit_amount'=> $_POST['guestprice3'],
         'currency' => 'usd',
    ]);
    $mealChoices->mealname = $_POST['meal3'];
    $mealChoices->mealdescription = $_POST['mealdesc3'];
    $mealChoices->memberprice = $_POST['memberprice3'];
    $mealChoices->guestprice = $_POST['guestprice3'];
    $mealChoices->priceid = $memberpriceID3->id;
    $mealChoices->guestpriceid = $guestpriceID3->id;
    $mealChoices->productid = $newProduct3->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct3->id;
   $product->priceid = $memberpriceID3->id;
    $product->eventid = $_POST['eventId'];
   $product->create();
  } // new product 3

     } // end of mealchoice 3 

} // end of submit

        $redirect = "Location: ".$_SESSION['adminEventurl'];
        header($redirect);
        exit;

?>