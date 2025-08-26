<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/EventRegistration.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/PaymentProduct.php';

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
if ($_SERVER['SERVER_NAME'] === 'localhost') {
 
  $stripeSecretKey = $_SESSION['testkey'] ;
}
$stripe = new \Stripe\StripeClient($stripeSecretKey);

// header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();
$event = new Event($db);
$eventRegistration = new EventRegistration($db);
$mealChoices = new DinnerMealChoices($db);
$paymentProduct = new PaymentProduct($db);
$rowCount = 0;
$productName = '';
if (isset($_POST['submitUpdateMeal'])) {
  $mChoices = $_SESSION['eventmealchoices'] ;

     foreach ($mChoices as $choice ) {
   
          $upID = "up".$choice['id'];
          $mcID = "mc".$choice['id'];
          $mdID = "md".$choice['id'];
          $mpID = "mp".$choice['id'];
          $gpID = "pp".$choice['id'];
          $prodID = "prod".$choice['id'];
          $mpriceID = "mprice".$choice['id'];
          $gpriceID = "gprice".$choice['id'];

          if (isset($_POST["$upID"])) {
            $result = $eventRegistration->read_ByMealID($choice['id']);
            $rowCount = $result->rowCount();
            if ($rowCount > 0) {
              $redirect = "Location: ".$_SESSION['returnurl'].'?error=Meal Choice in Use Cannot be Modified';
              header($redirect);
              exit;  
            }
           
            $paymentProduct->read_byProductId($choice['productid']);
            
            $event->id = $choice['eventid'];
            $event->read_single();
              $product->description = $_POST["$mdID"];
              $productName = $event->eventname;
              $productName .= ' ';
              $productName .= $_POST["$mcID"];
              $product->name = $productName;
            
             $mealChoices->id = $choice['id'];
             $mealChoices->memberprice  = $_POST["$mpID"];
             $mealChoices->guestprice   = $_POST["$gpID"];
             $mealChoices->mealname   = $_POST["$mcID"];
            $mealChoices->mealdescription   = $_POST["$mdID"];
             $paymentProduct->name =  $product->name;
             $paymentProduct->description =  $product->description;
        
            $product = $stripe->products->update(
              $choice['productid'],
              ['name' =>  $paymentProduct->name,
               'description' => $paymentProduct->description]
            );
            // to update we have to inactivate the old price id and make a new one
            $memberprice = $stripe->prices->update(
              $choice['priceid'],
              ['active' => false]
            );
            $guestprice = $stripe->prices->update(
              $choice['guestpriceid'],
              ['active' => false]
            );
        
            $memberpriceOBJ = $stripe->prices->create([
                 'product' =>  $choice['productid'],
                  'unit_amount'=> $mealChoices->memberprice,
                  'currency' => 'usd',
               ]);
               
             $paymentProduct->price = $mealChoices->memberprice;
             $paymentProduct->priceid = $memberpriceOBJ->id;
             $paymentProduct->type = 'meal';
             $paymentProduct->productid =  $choice['productid'];
             $paymentProduct->update();

              $mealChoices->priceid = $memberpriceOBJ->id;

            $guestpriceOBJ = $stripe->prices->create([
                 'product' => $choice['productid'],
                  'unit_amount'=> $mealChoices->guestprice,
                  'currency' => 'usd',
               ]);
        
               $mealChoices->guestpriceid = $guestpriceOBJ->id;

              $mealChoices->update();
          }
     }
              $redirect = "Location: ".$_SESSION['returnurl'];
              header($redirect);
              exit;  
}
?>