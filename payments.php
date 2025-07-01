<?php
session_start();
// require_once './vendor/autoload.php';

require_once 'config/Database.php';
require_once 'models/PaymentCustomer.php';
require_once 'models/PaymentProduct.php';
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
            'price'=> $price

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
    <div class="form-grid2">
   
    <div class="form-grid-div">
        <h2>Products</h2>
        <?php
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>Product ID</th> '; 
                    echo '<th>Product Name</th> '; 
                    echo '<th>Description</th> '; 
                    echo '<th>Price</th>';
  
                  
               echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($allProducts as $product) {
             
                      echo "<tr>";
                        echo "<td>".$product['productid']."</td>";
                        echo "<td>".$product['name']."</td>";
                        echo "<td>".$product['description']."</td>";
                        $dollarPrice = $product['price']/100;
                        echo "<td>".$dollarPrice."</td>";               
         
                      echo "</tr>";
                  }
             echo '</tbody>';
            echo '</table>';   
            echo '<br>';
            echo '<div class="form-container">';
           echo '<form method="POST" action="actions/addPaymentProduct.php">';
           echo "<h4 class='form-title'>Add a Payment Product</h4>";
           echo '<div class="form-grid">';
           echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Product Name</h4>";
           echo "<input type='text' name='productname' placeholder='product name' 
             title='Enter the Name of the Online Option to purchase' required >";
             echo '</div><br>';
           echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Product Description</h4>";
           echo "<input type='text' name='productdescription' placeholder='description' 
             title='Enter the Description of an Online Payment Option' required >";
             echo '</div><br>';
             echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Price in Cents</h4>";
           echo "<input type='number' name='productprice' placeholder='price in cents' 
             title='Enter the Product Price in cents' required>";
             echo '</div><br>';
           echo '</div>';
           echo '<button type="submit" name="submitAddProduct">Add the Product</button><br>';
           echo '</form>';
           echo '</div>';
            ?>
    </div>
  
    <div class="form-grid-div">
        <h2>Customers</h2>
        <?php
           echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>Customer ID</th> '; 
                    echo '<th>User ID</th> '; 
                    echo '<th>First Name</th> '; 
                    echo '<th>Last Name</th>';
                    echo '<th>Email</th>';

                  
               echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($allCustomers as $customer) {
             
                      echo "<tr>";
                        echo "<td>".$customer['customerid']."</td>";
                        echo "<td>".$customer['userid']."</td>";
                        echo "<td>".$customer['firstname']."</td>";
                        echo "<td>".$customer['lastname']."</td>";
                        echo "<td>".$customer['email']."</td>";
           
         
                      echo "</tr>";
                  }
             echo '</tbody>';
            echo '</table>';   
            echo '<br>';
            ?>
    </div>
    </div>
   </section>
    
   </div>
</body>
</html>