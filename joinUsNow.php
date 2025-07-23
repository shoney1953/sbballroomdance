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
$current_month = date('m');
$current_year = date('Y');

$next_year = date('Y', strtotime('+1 year'));

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
 
     if ((int)$current_month >= $_SESSION['discountmonth']){


       $dpos = strpos($product_item['name'], 'Discount'); 
       if ($dpos) {
        $ypos = strpos($product_item['name'], $current_year); 


        if ($ypos !== FALSE) {
            array_push($memberProducts, $product_item);
   
        } 
       } else {
        $ypos = strpos($product_item['name'], $next_year); 
        if ($ypos !== FALSE) {
            array_push($memberProducts, $product_item);
       }
      }
     } else {
      if ($pos) {
         $ypos = strpos($product_item['name'], $current_year); 
         if ($ypos !== FALSE) {
           array_push($memberProducts, $product_item);
         }
       
      }        
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

    <section id="joinUs" class="content">
   <br><br><br>
   <h3>Join Our Club Today</h3>
   <h4>Members enjoy the benefits of attending any of our classes at no cost!
   They also receive reduced prices for our dinner dances!</h4>
   <h4>We would love to have you join us! It's easy.</h4>
   <?php
   if ((int)$current_month >= $_SESSION['discountmonth']) {
     echo '<br><h4><em>NOTE: We are currently in the discount membership period of the year, so you will have the option of a discount rate for just the rest of '.$current_year.',<br>or
     paying the full price of membership and becoming a member for the rest of '.$current_year.' and the next full year '.$next_year.'.</em><br><br>';
   }

      echo ' <button class="button-round" ><h1><em><a href="joinonline.php">JOIN and PAY ONLINE NOW!</a></em></h1></button>  ';
      echo '<h4><em>OR</em></h4>';


  
  ?>

  <h4>Just click on the form below, print it and then fill it in and send it along with member dues to the treasurer of our club (name and address is on the form).</h4>
        <h4><a  
            href='img/SBDC Membership Form 2025.pdf' target='_blank'>
            Click for Membership Form</a></h4>
    <h4> As soon as your information is entered, you'll get your userid and password and can login to the website to register for events and classes.</h4>  
   

    <br><br>
   
    </section>


    <footer >

    <?php
  require 'footer.php';
?>
    
</div> 

</footer>
</body>
</html>