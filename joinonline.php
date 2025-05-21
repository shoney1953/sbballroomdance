<?php
session_start();
  require_once 'config/Database.php';
  require_once 'models/PaymentCustomer.php';
  require_once 'models/PaymentProduct.php';
  

  $memberProducts = $_SESSION['memberproducts'];
  $current_year = date('Y');

  $next_year = date('Y', strtotime('+1 year'));
  $current_month = date('m');
  if ((int)$current_month >= $_SESSION['discountmonth']) {
  $searchIndividual = $next_year." Individual Membership";
  $searchCouple = $next_year." Couple Membership";
  $searchIndividualDisc = $current_year." Discount Individual Membership";
  $searchCoupleDisc = $current_year." Discount Couple Membership";
  } else {
      $searchIndividual = $current_year." Individual Membership";
      $searchCouple = $current_year." Couple Membership";
  }
  $indProductID = '';
  $coupleProductID = '';
  $indPriceID = '';
  $couplePriceID = '';
  $newProduct = [];
  $chargePriceID = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Sign UP and Pay Online</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <section id="joinOnline" class="content">
    <br><br><br>
    <div class="container-section ">
 

   <h1>Join Our Club Today</h1>
   <h2>Enter Your Information and Click to Charge</h2>
   
  <?php
   echo '<div class="form-container">';
           echo '<form method="POST" action="actions/join.php">';
           echo '<table>' ;
           echo '<thead>';
           echo '<tr>';
  
           echo '<th colspan=3>If you enter two members you will be charged for the couple membership, otherwise the individual price</th>'; 
           echo '</tr>';
          if ((int)$current_month >= $_SESSION['discountmonth']) {
           echo '<tr>';
           echo '<th colspan=3><em>Since we are in the discount part of the membership year, you may select to pay the discount rate for just this year: '.$current_year.'
           or pay the full price and become a member for the current year and next year: '.$next_year.'</em></th>'; 
           echo '</tr>';

          }


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
              if ($product['name'] == $searchIndividualDisc) {
              $indProductDiscID = $product['productid'];
              $indPriceDiscID = $product['priceid'];
             }
             if ($product['name'] == $searchCoupleDisc) {
              $coupleProductDiscID = $product['productid'];
              $couplePriceDiscID = $product['priceid'];
             }
         
         }
         echo '</tbody>';
         echo '</table>';  
         echo '</tbody>';
         echo '</table>';  



?>
    <br>

  
     <?php
       if ((int)$current_month >= $_SESSION['discountmonth']) {

         echo '<div class="form-grid6">';
    
         echo '<div class="form-grid-div">';
          echo '<h4>Discount Membership Option</h4>';
          echo '<div class="form-item">';
          echo '<h4 class="form-item-title">Join for just '.$current_year.'?</h4>';
          echo '<input type="checkbox" name="discyear"   title="Check to indicate you want to join for only the remainder of the current year">';
          echo '</div> ';
          echo '</div> ';
              
          echo '</div> ';

       }
     ?>
    <div class="form-grid2">

    <div class="form-grid-div">
    <h4>Member One Information will be used for billing</h4>
    <div class="form-item">
    <h4 class='form-item-title'>First Name</h4>
    <input type='text' name='firstname1' placeholder='member one first name' 
             title='Enter the first members first name' required>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Last Name</h4>
    <input type='text' name='lastname1' placeholder='member one last name' 
             title='Enter the first members last name' required>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Email</h4>
    <input type='email' name='email1' placeholder='member one email -- must be unique' 
             title='Enter the first members email' required>
    </div>
    <div class="form-item">
      <h4 class="form-item-title">Primary Phone</h4>
      <input type="tel" name="phone1" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
      <small>Format: 123-456-7890</small>
      </div> 

      <div class="form-item">
      <h4 class="form-item-title">Street Address</h4>
      <input type="text" name="streetaddress1" required >
      </div> 

      <div class="form-item">
      <h4 class="form-item-title">City</h4>
      <input type="text" name="city1" required >
      </div>

      <div class="form-item">
      <h4 class="form-item-title">State</h4>
      <input type="text" name="state1" maxlength="2" required>
      </div>

      <div class="form-item">
      <h4 class="form-item-title">Zip</h4>
      <input type="text" name="zip1" maxlength="10" required>
      </div>

      <div class="form-item">
      <h4 class="form-item-title">HOA</h4>
      <select name = "hoa1">
      <option value = "1">HOA 1</option>
      <option value = "2">HOA 2</option>
      </select>
      </div>
      <div class="form-item">
      <h4 class="form-item-title">Fulltime</h4>
      <select name = "fulltime1">
      <option value = "1">Fulltime</option>
      <option value = "0">Gone for the Summer</option>
      </select>
      </div>

    </div>
    <div class="form-grid-div">
    <h4>Member Two</h4>
    <div class="form-item">
      <h4 class="form-item-title">Add Member2</h4>
      <td><input type='checkbox' name='addmem2'   title='Check to indicate you are adding a second member'></td>
      </div> 
    <div class="form-item">
    <h4 class='form-item-title'>First Name</h4>
    <input type='text' name='firstname2' placeholder='member two first name' 
             title='Enter the second members first name' >
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Last Name</h4>
    <input type='text' name='lastname2' placeholder='member two last name' 
             title='Enter the second members last name'>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Email</h4>
    <input type='email' name='email2' placeholder='member two email -- must be unique' 
             title='Enter the second members email' >
    </div>
    <div class="form-item">
      <h4 class="form-item-title">Primary Phone</h4>
      <input type="tel" name="phone2" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
      <small>Format: 123-456-7890</small>
      </div> 
      <div class="form-item">
      <h4 class="form-item-title">Same Address</h4>
      <td><input type='checkbox' name='mem2sameaddr'   title='Check to indicate same address for member 2'></td>
      </div> 
      <div class="form-item">
      <h4 class="form-item-title">Street Address</h4>
      <input type="text" name="streetaddress2"  >
      </div> 

      <div class="form-item">
      <h4 class="form-item-title">City</h4>
      <input type="text" name="city2" >
      </div>

      <div class="form-item">
      <h4 class="form-item-title">State</h4>
      <input type="text" name="state2" maxlength="2" >
      </div>

      <div class="form-item">
      <h4 class="form-item-title">Zip</h4>
      <input type="text" name="zip2" maxlength="10" >
      </div>

      <div class="form-item">
      <h4 class="form-item-title">HOA</h4>
      <select name = "hoa2">
      <option value = "1">HOA 1</option>
      <option value = "2">HOA 2</option>
      </select>
      </div>
      <div class="form-item">
      <h4 class="form-item-title">Fulltime</h4>
      <select name = "fulltime2">
      <option value = "1">Fulltime</option>
      <option value = "0">Gone for the Summer</option>
      </select>
      </div>
    </div>
    </div>
    <?php

     echo '<input type="hidden" name="indprodid" value="'.$indProductID.'">';
     echo '<input type="hidden" name="coupleprodid" value="'.$coupleProductID.'">';
     echo '<input type="hidden" name="indpriceid" value="'.$indPriceID.'">';
     echo '<input type="hidden" name="couplepriceid" value="'.$couplePriceID.'">';
     /* Discount prices */
     echo '<input type="hidden" name="indproddiscid" value="'.$indProductDiscID.'">';
     echo '<input type="hidden" name="coupleproddiscid" value="'.$coupleProductDiscID.'">';
     echo '<input type="hidden" name="indpricediscid" value="'.$indPriceDiscID.'">';
     echo '<input type="hidden" name="couplepricediscid" value="'.$couplePriceDiscID.'">';
     ?>
    </div>
    <button type="submit" name="submitMembership">Enter Membership Information</button><br>
        </form>

   
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