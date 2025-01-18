<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/secrets.php';
require_once '../config/Database.php';

require_once '../models/PaymentProduct.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
$stripe = new \Stripe\StripeClient('sk_test_51IVzJTL8mOGPmzyGuvJjZymxynpVAmNHkWGOT42oYyGMEMG3hf94zkJ8bvbUgHVEwGH5wJxWKRd6PkZBnYwd9ChL003mJKdQxN');
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
// $price = $stripe->prices->create([
//   'product' => $product->id,
//   'unit_amount' => 2000,
//   'currency' => 'usd',
// ]);
// $product = $stripe->products->create(['name' => 'T-shirt']);
// $product = $stripe->products->create(['name' => '2027 Membership','metadata' => ['Membership' => '2027']]);

// $gotprod = $stripe->products->retrieve($product->id, []);
// $gotprod = $stripe->products->search([
//     'query' => 'active:\'true\' AND metadata[\'Membership\']:\'2027\'',
//   ]);
//   print_r($gotprod) ;
  // $gotprod = $stripe->products->search([
  //   'query' => 'active:\'true\' AND metadata[\'Membership\']:\'2025\'',
  // ]);
  // print_r($gotprod) ;
  
  // $qstring = "active:\'true\' AND product:\'".$gotprod->id."\''";
  // echo $qstring;
  // $gotprice = $stripe->prices->search([
  //   'query' => $qstring,
  // ]);
  // $gotprice = $stripe->prices->retrieve('price_1QgU6RL8mOGPmzyGfJMS4p6D', []);
  // echo $gotprice;
  // echo $gotprice;
  // $price = $stripe->prices->create([
  //   'product' => $product->id,
  //   'unit_amount' => 2000,
  //   'currency' => 'usd',
  // ]);

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