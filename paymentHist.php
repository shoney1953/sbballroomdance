<?php
session_start();

date_default_timezone_set("America/Phoenix");
require_once 'vendor/autoload.php';
require_once 'config/Database.php';
require_once 'models/PaymentCustomer.php';
require_once 'models/PaymentProduct.php';

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
// $charges = $stripe->charges->all(['limit' => 3]);
$charges = $stripe->charges->all();




if (isset($_SESSION['role'])) {

} else {
      header("Location: https://www.sbballroomdance.com/");
     exit;
}
$_SESSION['paymenturl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];


$database = new Database();
$db = $database->connect();
// refresh events

if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$allCustomers = [];
$allProducts = [];
$product = new PaymentProduct($db);
$result = $product->read();
$rowCount = $result->rowCount();
$num_products = $rowCount;
$_SESSION['allProducts'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
  
        $product_item = array(
            'productid' => $productid,
            'description' => $description,
            'name' => $name,
            'priceid' => $priceid,
            'type' => $type,
            'price'=> $price,
            'eventid' => $eventid

        );
        array_push($allProducts, $product_item);
    
    }
  
    $_SESSION['allProducts'] = $allProducts;
}

$customer = new PaymentCustomer($db);
$result = $customer->read();
$rowCount = $result->rowCount();
$num_customers = $rowCount;
$_SESSION['allCustomers'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $customer_item = array(
            'customerid' => $customerid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'userid' => $userid

        );
        array_push($allCustomers, $customer_item);
    
    }
  
    $_SESSION['allCustomers'] = $allCustomers;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://js.stripe.com/v3/"></script>
    <title>SBDC Stripe Payment History</title>
</head>
<body>

<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
           echo "<li><a href='index.php'>Back to Home</a></li> ";
      
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='administration.php'>Back to Administration</a></li> ";
               echo "<li><a href='payments.php'>Back to Payments</a></li> ";
            }

?>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
   <div class="container-section ">
   <section class="content">
    <h1>Stripe Payment History</h1>
    <div class="form-grid2">
   
    <div class="form-grid-div">
        <h2>Online Payments</h2>
        <?php
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>Date</th> '; 
                    echo '<th>Amount</th> '; 
                    echo '<th>Email</th> '; 
                    echo '<th>Receipt URL</th> '; 

                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
            foreach ($charges['data'] as $transaction) {
              echo '<tr>';
                echo "<td>".date('m/d/Y', $transaction['created'])."</td>";
                echo "<td>$".number_format($transaction['amount']/100, 2)."</td>";
                echo "<td>$".$transaction['billing_details']['email']."</td>";
                echo "<td><a href='".$transaction['receipt_url']."'>Click to see Receipt</a></td>";
              echo '</tr>';
            }
             echo '</tbody>';
            echo '</table>';   
            echo '<br>';
          ?>
    </div>
    </div>
   
   </section>
    
   </div>
    <footer >
    <?php
    require 'footer.php';
   ?>
    </footer>
</body>
</html>