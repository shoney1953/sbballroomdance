<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/PaymentProduct.php';
require_once '../models/Event.php';
$productDescription = '';
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
if (($_SERVER['SERVER_NAME'] === 'localhost') || 
    (($_SESSION['testmode'] === 'YES') && (isset($_SESSION['testmode']))))  {    

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
        $productDescription = $event->eventname;
        $productDescription .= ' ';
        $productDescription .= $_POST['meal1'];
        $product->description = $productDescription;
     }
    if (isset($_POST['memberprice1'])) {
        $product->price = $_POST['memberprice1'];
    }

        $product->name = $productDescription;
        $product->type = 'membership';
    
  $newProduct = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description]) ;


  if ($newProduct) {
    $product->productid = $newProduct->id; // using the id assigned from Stripe
    $memberpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);
    $guestpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $_POST['guestprice1'],
         'currency' => 'usd',
    ]);
    $mealChoices->mealchoice = $_POST['meal1'];
    $mealChoices->memberprice = $_POST['memberprice1'];
    $mealChoices->guestprice = $_POST['guestprice1'];
    $mealChoices->priceid = $memberpriceID->id;
    $mealChoices->guestpriceid = $guestpriceID->id;
    $mealChoices->productid = $newProduct->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct->id;
   $product->priceid = $memberpriceID->id;
   $product->create();
  }

    if (isset($_POST['meal2'])  && ($_POST['meal2']) !== '') {
        $productDescription = $event->eventname;
        $productDescription .= ' ';
        $productDescription .= $_POST['meal2'];
        $product->description = $productDescription;
    
    if (isset($_POST['memberprice2'])) {
        $product->price = $_POST['memberprice2'];
        echo $product->price;
    }

        $product->name = $productDescription;
    
  $newProduct = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description]) ;


  if ($newProduct) {
    
    $product->productid = $newProduct->id; // using the id assigned from Stripe
    $memberpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);

    $guestpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $_POST['guestprice2'],
         'currency' => 'usd',
    ]);
    $mealChoices->mealchoice = $_POST['meal2'];
    $mealChoices->memberprice = $_POST['memberprice2'];
    $mealChoices->guestprice = $_POST['guestprice2'];
    $mealChoices->priceid = $memberpriceID->id;
    $mealChoices->guestpriceid = $guestpriceID->id;
    $mealChoices->productid = $newProduct->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct->id;
   $product->priceid = $memberpriceID->id;
   $product->create();
  }

   if (isset($_POST['meal3'])  && ($_POST['meal3']) !== '') {
 
        $productDescription = $event->eventname;
        $productDescription .= ' ';
        $productDescription .= $_POST['meal3'];
        $product->description = $productDescription;
    
    if (isset($_POST['memberprice3'])) {
        $product->price = $_POST['memberprice3'];
    }

    $product->name = $productDescription;
    
  $newProduct = $stripe->products->create(
    ['name' => $product->name,
    'description' => $product->description]) ;


  if ($newProduct) {
    $product->productid = $newProduct->id; // using the id assigned from Stripe
    $memberpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);


    $guestpriceID = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $_POST['guestprice3'],
         'currency' => 'usd',
    ]);
    $mealChoices->mealchoice = $_POST['meal3'];
    $mealChoices->memberprice = $_POST['memberprice3'];
    $mealChoices->guestprice = $_POST['guestprice3'];
    $mealChoices->priceid = $memberpriceID->id;
    $mealChoices->guestpriceid = $guestpriceID->id;
    $mealChoices->productid = $newProduct->id;
    $mealChoices->eventid = $_POST['eventId'];
    $mealChoices->create();

   $product->productid = $newProduct->id;
   $product->priceid = $memberpriceID->id;
   $product->create();
  }

     }


}
}
        $redirect = "Location: ".$_SESSION['adminEventurl'];
        header($redirect);
        exit;

?>