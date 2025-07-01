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
$current_year = date('Y');

  $next_year = date('Y', strtotime('+1 year'));
  $searchIndividual = $current_year." Individual Membership";
  $searchCouple = $current_year." Couple Membership";
  $indProductID = '';
  $coupleProductID = '';
  $indPriceID = '';
  $couplePriceID = '';
  $newProduct = [];
  $chargePriceID = '';
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
    <title>SBDC Renew Online Today</title>
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
   <h1>Renew Your Membership Today!</h1>
   <h3>Members enjoy the benefits of attending any of our classes at no cost!
   They also receive reduced prices for our dinner dances!</h3>
   <h3>We would love to have you continue with us! It's easy.</h3>
   <?php
   echo '<div class="form-container">';
           echo '<form method="POST" action="actions/renew.php">';
           echo '<table>' ;
           echo '<thead>';
          
           echo '<tr>';
  
           echo '<th>Membership Level</th>'; 
           echo '<th>Description</th>';   
           echo '<th>Price</th>';

           echo '</tr>';
           echo '</thead>';
           echo '<tbody>';
         
           foreach ($memberProducts as $product) {
  

              $mlidID = "mlid".$product['productid'];
             
                 echo '<tr>';
                 echo "<td>".$product['name']."</td>";
                 echo "<td>".$product['description']."</td>";
                 $dollarPrice = $product['price']/100;
                 echo "<td>".$dollarPrice."</td>";
                 echo '</tr>';
             if ($product['name'] == $searchIndividual) {
              $indProductID = $product['productid'];
              $indPriceID = $product['priceid'];
             }
             if ($product['name'] == $searchCouple) {
              $coupleProductID = $product['productid'];
              $couplePriceID = $product['priceid'];
             }
         
         }
         if ($_SESSION['partnerid'] > 0) {
            echo '<td colspan="3">';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Renew Both Members?</h4>';
            echo "<input type='checkbox' name='renewmem2'   title='Check to indicate you are renewing your partner as well.'>";
            echo '</td>';
            echo "</div> ";
         }
         echo '</tbody>';
         echo '</table>';  
      
        echo '</div>';

        echo '<input type="hidden" name="indprodid" value="'.$indProductID.'">';
        echo '<input type="hidden" name="coupleprodid" value="'.$coupleProductID.'">';
        echo '<input type="hidden" name="indpriceid" value="'.$indPriceID.'">';
        echo '<input type="hidden" name="couplepriceid" value="'.$couplePriceID.'">';

   
       echo '<button type="submit" name="submitRenewal">Click to Renew</button><br>';
       echo '</div>';
       echo '</form>';
       ?>


 

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