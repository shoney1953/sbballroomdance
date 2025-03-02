<?php
  session_start();
  require_once 'config/Database.php';
  require_once 'models/PaymentCustomer.php';
  require_once 'models/PaymentProduct.php';
  $database = new Database();
  $db = $database->connect();
  $memberProducts = [];
$product = new PaymentProduct($db);
$result = $product->read();
$rowCount = $result->rowCount();
$num_products = $rowCount;

$memberProducts = [];
$_SESSION['memberproducts'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $product_item = array(
            'productid' => $productid,
            'description' => $description,
            'name' => $name,
            'price' => $price,
            'priceid' => $priceid

        );
 
     $pos = strpos($product_item['name'], 'Membership');

      if ($pos) {
        array_push($memberProducts, $product_item);
      }
            
        }
    }

    $_SESSION['memberproducts'] = $memberProducts;


date_default_timezone_set("America/Phoenix");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Join Us Today</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <div class="container-section ">
    <section id="joinUs" class="content">
   <br><br><br>
   <h1>Join Our Club Today</h1>
   <h3>Members enjoy the benefits of attending any of our classes at no cost!
   They also receive reduced prices for our dinner dances!</h3>
   <h3>We would love to have you join us! It's easy.</h3>
   <?php
   if (isset($_SESSION['testmode'])) {
    if ($_SESSION['testmode'] == 'YES') {
      echo ' <button ><em><a href="joinonline.php">JOIN ONLINE NOW!</a></em></button>  ';
     }
   }

  
  ?>

  <h3>Just click on the form below, print it and then fill it in and send it along with member dues to the treasurer of our club (name and address is on the form).</h3>
  <h1><a  
            href='img/SBDC Membership Form 2025.pdf' target='_blank'>
            Click for Membership Form</a></h1>
    <h3> As soon as your information is entered, you'll get your userid and password and can login to the website to register for events and classes.</h3>  
   

    <br><br>
   
    </section>
    </div>

    <footer >

    <?php
  require 'footer.php';
?>
    
</div> 

</footer>
</body>
</html>