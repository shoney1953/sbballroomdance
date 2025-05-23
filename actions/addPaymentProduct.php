<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/secrets.php';
require_once '../config/Database.php';

require_once '../models/PaymentProduct.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
     if ($_SESSION['role'] != 'SUPERADMIN') 
    {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$product = new PaymentProduct($db);
$newProduct = [];


if (isset($_POST['submitAddProduct'])) {
    if (isset($_POST['productdescription'])) {
        $product->description = $_POST['productdescription'];
    }
    if (isset($_POST['productprice'])) {
        $product->price = $_POST['productprice'];
    }
    if (isset($_POST['productname'])) {
        $product->name = $_POST['productname'];
    }
  $newProduct = $stripe->products->create(['name' => $product->name,'description' => $product->description]);


  if ($newProduct) {
    $product->productid = $newProduct->id; // using the id assigned from Stripe
    $price = $stripe->prices->create([
        'product' => $newProduct->id,
         'unit_amount'=> $product->price,
         'currency' => 'usd',
    ]);
   $product->productid = $newProduct->id;
   $product->priceid = $price->id;
   $product->create();
  }

}
$redirect = "Location: ".$_SESSION['paymenturl']."";
header($redirect);
exit;
?>