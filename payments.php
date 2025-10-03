<?php
session_start();

date_default_timezone_set("America/Phoenix");
// require_once 'vendor/autoload.php';
require_once 'config/Database.php';
// require_once 'models/PaymentCustomer.php';
require_once 'models/PaymentProduct.php';

// if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
//   $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
//    $stripeSecretKey = $_SESSION['prodkey'] ;

// }
// if ($_SERVER['SERVER_NAME'] === 'localhost') {    
//   $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
//   $stripeSecretKey = $_SESSION['testkey'] ;
// }
// \Stripe\Stripe::setApiKey($stripeSecretKey);
// // header('Content-Type: application/json');

// $stripe = new \Stripe\StripeClient($stripeSecretKey);
// // $charges = $stripe->charges->all(['limit' => 3]);
// $charges = $stripe->charges->all();



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
$membershipProducts = [];
$danceProducts = [];
$mealProducts = [];
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
        if ($product_item['type'] === 'membership') {
            array_push($membershipProducts, $product_item);
        }
       if ($product_item['type'] === 'dance') {
            array_push($danceProducts, $product_item);
        }
        if ($product_item['type'] === 'meal') {
            array_push($mealProducts, $product_item);
        }
        array_push($allProducts, $product_item);
    
    }
  
    $_SESSION['allProducts'] = $allProducts;
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
    <title>SBDC Payment Processing Options</title>
</head>
<body>

<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
           echo "<li><a href='index.php'>Back to Home</a></li> ";
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='administration.php'>Back to Administration</a></li> ";
                 echo "<li><a href='paymentHist.php'>Stripe Payment History</a></li> ";

            }

?>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
   <div class="container-section ">
   <section  class="content">
    <h1>Payment Processing Options</h1>
   <h2>Online Payment Products</h2>
   <div class="form-grid2">
 
     
        <?php
         echo '<div class="form-grid-div">';
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                echo '<td colspan="6">Membership Products </td>';
                echo '</tr>';
                 echo '<tr>';
                    echo '<th>Product ID</th> '; 
                    echo '<th>Event ID</th> '; 
                    echo '<th>Product Type</th> '; 
                    echo '<th>Product Name</th> '; 
                    echo '<th>Description</th> '; 
                    echo '<th>Price</th>';
  
                  
                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($membershipProducts as $product) {
             
                      echo "<tr>";
                        echo "<td>".$product['productid']."</td>";
                         echo "<td>".$product['eventid']."</td>";
                        echo "<td>".$product['type']."</td>";
                        echo "<td>".$product['name']."</td>";
                        echo "<td>".$product['description']."</td>";
                        $dollarPrice = $product['price']/100;
                        echo "<td>".$dollarPrice."</td>";               
         
                      echo "</tr>";
                  }
                  echo '<tr>';
                  echo '<td colspan="6"> ';
           echo '<form method="POST" action="actions/addPaymentProduct.php">';
           echo "<h4 class='form-title'>Add a Membership Payment Product</h4>";
           echo '<div class="form-grid">';
           echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Name</h4>";
           echo "<input type='text' name='productname' placeholder='membership name' 
             title='Enter the Name format should be Year then Indivdual or Couple then Membership' required >";
             echo '</div><br>';
           echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Description</h4>";
           echo "<input type='text' name='productdescription' placeholder='description' 
             title='Enter the Description the membership product' required >";
             echo '</div><br>';
             echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Price in Cents</h4>";
           echo "<input type='number' name='productprice' placeholder='price in cents' 
             title='Enter the Product Price in cents' required>";
             echo '</div><br>';
           echo '</div>';
           echo "<input type='hidden' name='producteventid' value='0'>";
           echo '<button type="submit" name="submitAddProduct">Add the Product</button><br>';
           echo '</form>';
           echo '</div>';
           echo '</td>';
                  echo '</tr>';
             echo '</tbody>';
            echo '</table>';  
            echo '</div>' ;
         echo '<div class="form-grid-div">';
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                echo '<td colspan="6">Dance Products </td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>Product ID</th> '; 
                    echo '<th>Event ID</th> '; 
                    echo '<th>Product Type</th> '; 
                    echo '<th>Product Name</th> '; 
                    echo '<th>Description</th> '; 
                    echo '<th>Price</th>';
  
                  
                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($danceProducts as $product) {
             
                      echo "<tr>";
                        echo "<td>".$product['productid']."</td>";
                         echo "<td>".$product['eventid']."</td>";
                        echo "<td>".$product['type']."</td>";
                        echo "<td>".$product['name']."</td>";
                        echo "<td>".$product['description']."</td>";
                        $dollarPrice = $product['price']/100;
                        echo "<td>".$dollarPrice."</td>";               
         
                      echo "</tr>";
                  }
             echo '</tbody>';
            echo '</table>';  
            echo '</div>' ;

 
            // echo '</div>';
            echo '<div class="form-grid-div">';
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                echo '<td colspan="6">Meal Products </td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>Product ID</th> '; 
                    echo '<th>Event ID</th> '; 
                    echo '<th>Product Type</th> '; 
                    echo '<th>Product Name</th> '; 
                    echo '<th>Description</th> '; 
                    echo '<th>Price</th>';
  
                  
                echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($mealProducts as $product) {
             
                      echo "<tr>";
                        echo "<td>".$product['productid']."</td>";
                         echo "<td>".$product['eventid']."</td>";
                        echo "<td>".$product['type']."</td>";
                        echo "<td>".$product['name']."</td>";
                        echo "<td>".$product['description']."</td>";
                        $dollarPrice = $product['price']/100;
                        echo "<td>".$dollarPrice."</td>";               
         
                      echo "</tr>";
                  }
             echo '</tbody>';
            echo '</table>';  
            echo '</div>' ;
                  echo '</div>' ;
         
            ?>
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