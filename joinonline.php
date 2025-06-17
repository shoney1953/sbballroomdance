<?php
session_start();

  require_once 'config/Database.php';
  require_once 'models/PaymentCustomer.php';
  require_once 'models/PaymentProduct.php';
  $err_switch = 0;

if (isset($_GET['resubmit'])) {
    $err_switch = 1;
    unset($_GET['resubmit']);
} 

  if (isset($_GET['error'])) {
 
    echo '<div class="container-error">';
    echo '<br><br><br><h3 class="error"> ERROR:  '.$_GET['error'].'.<br> Please Reenter Data or Check to see if you are already a member.</h3><br>';
    echo '</div>';
    $err_switch = 1;
    unset($_GET['error']);
}  
if ($err_switch != 1) {
  $_SESSION['potentialMember1'] = [];
  $_SESSION['potentialMember2'] = [];
  $_SESSION['memsameaddr'] = '';
   $_SESSION['joinonlineurl'] = $_SERVER['REQUEST_URI']; 
}
$potentialmember1 = [];
$potentialmember2 = [];
if ($err_switch == 1) {
  if (isset($_SESSION['potentialMember1'])) {
    $potentialmember1 = $_SESSION['potentialMember1']; 

  }
  if (isset($_SESSION['potentialMember2'])) {
    $potentialmember2 = $_SESSION['potentialMember2']; 
  }
}


  $memberProducts = $_SESSION['memberproducts'];
  $current_year = date('Y');

  $next_year = date('Y', strtotime('+1 year'));
  $current_month = date('m');
  if ((int)$current_month >= $_SESSION['discountmonth']) {
  $searchIndividual = $next_year." Individual Membership";
  $searchCouple = $next_year." Couple Membership";

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
 


   <h1>To Join, enter your (and your partner's) information, and click the button to submit membership information.</h1>
   
  <?php

   echo '<div class="form-container">';
           echo '<form method="POST" action="actions/join.php">';
           echo '<table>' ;
           echo '<thead>';
           echo '<tr>';
  
           echo '<th colspan=3>If you enter two members you will be charged for the couple membership, otherwise for the individual price.</th>'; 
           echo '</tr>';
          if ((int)$current_month >= $_SESSION['discountmonth']) {
           echo '<tr>';
          //  echo '<th colspan=3><em>Since we are in the discount part of the membership year, you may select to pay the discount rate for just this year: '.$current_year.'
          //  or pay the full price and become a member for the current year and next year: '.$next_year.'</em></th>'; 
           echo '<th colspan=3><em>Since we are in the discount part of the membership year, you will become a member(s) for the remainder of '.$current_year.' as well as next year '.$next_year.'</em></th>'; 
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
         
         }
         echo '</tbody>';
         echo '</table>';  
         echo '</tbody>';
         echo '</table>';  

?>
    <br>


    <div class="form-grid4">

    <div class="form-grid-div">
    <h4>Member One Information will be used for billing</h4>

    <div class="form-item">
    <h4 class='form-item-title'>Member 1 First Name</h4>
    <?php
    if ($err_switch === 1) {
     
      echo "<input type='text' name='firstname1' title='Enter the first members first name' value=".$potentialmember1['firstname']."  required>";
    } else {
      echo "<input type='text' name='firstname1' placeholder='member one first name' 
             title='Enter the first members first name' required>";
    } 
    ?>

    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Member 1 Last Name </h4>
    <?php
    if ($err_switch === 1) {
       echo "<input type='text' name='lastname1'  title='Enter the first members last name' value=".$potentialmember1['lastname']." required>";
    } else {
          echo "<input type='text' name='lastname1' placeholder='member one last name' 
             title='Enter the first members last name' required>";
    }
     ?>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Member 1 Email</h4>
    <?php
        if ($err_switch === 1) {
             echo "<input type='email' name='email1' title='Enter the first members email' value=".$potentialmember1['email']." required>";
        } else {
          echo "<input type='email' name='email1' placeholder='member one email -- must be unique' 
             title='Enter the first members email' required>";
        }
    ?>
    </div>
    <div class="form-item">
      <h4 class="form-item-title">Member 1 Primary Phone</h4>
       <?php
        if ($err_switch === 1) {
          echo "<input type='text' pattern='\d{1,10}'  maxlength='10' name='phone1' value=".$potentialmember1['phone1']."><br>"; 
        } else {
          echo "<input type='text' pattern='\d{1,10}'  maxlength='10' placeholder='9 digits no dashes' name='phone1'><br>";
        }
      ?>
      </div> 
       <div class="form-item">
      <h4 class="form-item-title">Member 1 Directory List?</h4>
      <select name = "directorylist1">
      <?php
 
        if ($err_switch === 1) { 
          if ($potentialmember1['directorylist'] == 1) {
             echo '<option value = "1" selected>List in Directory</option>';
             echo '<option value = "0">Omit from Directory</option>';
          } else {
            echo '<option value = "0" selected>Omit from Directory</option>';
            echo '<option value = "1">List in Directory</option>';
          }
         } else {
          echo '<option value = "1">List in Directory</option>';
          echo '<option value = "0">Omit from Directory</option>';
         }

      ?>
      </select>
      </div>
      <div class="form-item">
      <h4 class="form-item-title">Member 1 SaddleBrooke Street Address</h4>
      <?php
      $sta = '';
   

        if ($err_switch == 1) {
          //  echo "<input type='text' name='streetaddress1' required value=".$potentialmember1['streetaddress']." >";
          $sta = $potentialmember1['streetaddress'];
          echo '<input type="text" id="streetaddress1" name="streetaddress1" required value="'.$sta.'" >';
        } else {
          echo '<input type="text" id="streetaddress1" name="streetaddress1" required >';
        }
      ?>
      </div> 

      <div class="form-item">
      <h4 class="form-item-title">Which HOA?</h4>
      <select name = "hoa1" id="hoa1">
      <?php
      if ($err_switch === 1) { 
        if ($potentialmember1['hoa'] === 1) {
           echo '<option value = "1" selected>HOA 1</option>';
             echo '<option value = "2">HOA 2</option>';
        } else {
          echo '<option value = "2" selected>HOA 2</option>';
           echo '<option value = "1">HOA 1</option>';
        }

      } else {
        echo '<option value = "1">HOA 1</option>';
        echo '<option value = "2">HOA 2</option>';
      }
     ?>
      </select>
      </div>
      <div class="form-item">
      <h4 class="form-item-title">Member 1 Fulltime?</h4>
      <select name = "fulltime1" id="fulltime1">
      <?php
 
        if ($err_switch === 1) { 
          if ($potentialmember1['fulltime'] === 1) {
             echo '<option value = "1" selected>Fulltime</option>';
             echo '<option value = "0">Gone for the Summer</option>';
          } else {
            echo '<option value = "0" selected>Gone for the Summer</option>';
            echo '<option value = "1">Fulltime</option>';
          }
        } else {
          echo '<option value = "1">Fulltime</option>';
          echo '<option value = "0">Gone for the Summer</option>';
        }

      ?>
      </select>
      </div>


    </div>
    <div class="form-grid-div"> 
        <div class="form-item">
      <h4 class="form-item-title">Add Member2</h4>
      <?php 
      if ($err_switch === 1) {
        if (isset($_SESSION['addmem2'])) {
          if ($_SESSION['addmem2'] === 'YES') {
            echo "<input type='checkbox' name='addmem2' checked  title='Check to indicate you are adding a second member'>";
          }  else {
            echo "<input type='checkbox' name='addmem2'   title='Check to indicate you are adding a second member'>";
          }
        } 
         else {
          echo "<input type='checkbox' name='addmem2'  title='Check to indicate you are adding a second member' >";
        }
      } else {
       echo "<input type='checkbox' name='addmem2'   title='Check to indicate you are adding a second member'>";
      }

       ?>

      </div> 
        <div class="form-item">
      <h4 class="form-item-title">Same Address for Member 2</h4>
        <?php 
      if ($err_switch === 1) {
        if (isset($_SESSION['memsameaddr'] )) {
          if ($_SESSION['memsameaddr'] === 'YES') {
            
            echo "<input type='checkbox' id='mem2sameaddr' name='mem2sameaddr' checked  title='Check to indicate same address for member 2'>";
          } 
          else {
              echo "<input type='checkbox' id='mem2sameaddr' name='mem2sameaddr'   title='Check to indicate same address for member 2' onchange='autofill()'>";
            }
         }   else {
          echo "<input type='checkbox' id='mem2sameaddr' name='mem2sameaddr'   title='Check to indicate same address for member 2' onchange='autofill()'>";
        }
        

      } else {
         echo "<input type='checkbox' id='mem2sameaddr' name='mem2sameaddr'   title='Check to indicate same address for member 2' onchange='autofill()'>";
      }
                  echo "<script>
            function autofill(){
                  document.getElementById('streetaddress2').value=document.getElementById('streetaddress1').value;  
                   document.getElementById('fulltime2').value=document.getElementById('fulltime1').value;   
                   document.getElementById('hoa2').value=document.getElementById('hoa1').value;   
              }
            </script>";
       ?>
      </div> 
    </div>
        <div class="form-grid-div">
      <h4>Member Two</h4>
    

    <div class="form-item">
    <h4 class='form-item-title'>Member 2 First Name</h4>
    <?php
    if ($potentialmember2) {
      if ($err_switch === 1) {
      echo "<input type='text' name='firstname2' title='Enter the second members first name' value=".$potentialmember2['firstname']." >";
      } 
       else {
          echo "<input type='text' name='firstname2' placeholder='member two first name' 
             title='Enter the second members first name' >";
       }
    }
     else {
          echo "<input type='text' name='firstname2' placeholder='member two first name' 
             title='Enter the second members first name' >";
    }
  
    ?>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Member 2 Last Name</h4>
    <?php
    if ($potentialmember2) {
        if ($err_switch === 1) {
        echo "<input type='text' name='lastname2'  
                title='Enter the second members last name' value=".$potentialmember2['lastname'].">";
        } 
         else {
          echo "<input type='text' name='lastname2' placeholder='member two last name' 
             title='Enter the second members last name'>";
    }
    }
    else {
    echo "<input type='text' name='lastname2' placeholder='member two last name' 
             title='Enter the second members last name'>";
    }
    ?>
    </div>
    <div class="form-item">
    <h4 class='form-item-title'>Member 2 Email</h4>
    <?php
    if ($potentialmember2) {
        if ($err_switch === 1) {
            echo "<input type='email' name='email2' 
                title='Enter the second members email' value=".$potentialmember2['email']." >";
        } 
             else {
            echo "<input type='email' name='email2' placeholder='member two email -- must be unique' 
             title='Enter the second members email' >";
    }
    }
     else {
      echo "<input type='email' name='email2' placeholder='member two email -- must be unique' 
             title='Enter the second members email' >";
    }
 
    ?>
    </div>
    <div class="form-item">
      <h4 class="form-item-title">Member 2 Primary Phone</h4>
      <?php
      if ($potentialmember2) {
        if ($err_switch === 1) {
          echo "<input type='text' pattern='\d{1,10}''  maxlength='10' name='phone2' value=".$potentialmember2['phone1']."><br>";
        } 
        else {
        echo '<input type="text" pattern="\d{1,10}"  maxlength="10" placeholder="9 digits no dashes" name="phone2"><br>';
          }
      }
       else {
        echo '<input type="text" pattern="\d{1,10}"  maxlength="10" placeholder="9 digits no dashes" name="phone2"><br>';
      }
       ?>
      </div> 

      <div class="form-item">
      <h4 class="form-item-title">Member 2 Directory List?</h4>
      <select name = "directorylist2">
      <?php

       if ($potentialmember2) {
       
        if ($err_switch === 1) { 
          if ($potentialmember2['directorylist'] == 1) {
             echo '<option value = "1" selected>List in Directory</option>';
             echo '<option value = "0">Omit from Directory</option>';
          } else {
            echo '<option value = "0" selected>Omit from Directory</option>';
            echo '<option value = "1">List in Directory</option>';
          }
        }  else {
            echo '<option value = "1">List in Directory</option>';
            echo '<option value = "0">Omit from Directory</option>'; 
        }
        } else {
              echo '<option value = "1">List in Directory</option>';
              echo '<option value = "0">Omit from Directory</option>';
           
        }

         

      ?>
      </select>

    
      <div class="form-item">
      <h4 class="form-item-title">Member 2 SaddleBrooke Street Address</h4>
      <?php
      if ($potentialmember2) {
        $sta2 = '';
        $sta2 = $potentialmember2['streetaddress'];
         if ($err_switch === 1) {
           echo '<input type="text"    name="streetaddress2" value="'.$sta2.'"  >';
         } 
               else {
          echo '<input type="text" id="streetaddress2" name="streetaddress2"  >';
      }
        }
      else {
          echo '<input type="text" id="streetaddress2" name="streetaddress2"  >';
      }
      ?>
      </div> 
      <?php

    ?>
      <div class="form-item">
      <h4 class="form-item-title">Which HOA?</h4>
      <select name = "hoa2" id="hoa2">
      <?php

      if ($potentialmember2) {

      if ($err_switch === 1) { 
        if ($potentialmember2['hoa'] === 1) {
           echo '<option value = "1" selected>HOA 1</option>';
             echo '<option value = "2">HOA 2</option>';
          } else {
            echo '<option value = "2" selected>HOA 2</option>';
            echo '<option value = "1">HOA 1</option>';
          }
        
        } else {
 
    
          echo '<option value = "1">HOA 1</option>';
          echo '<option value = "2">HOA 2</option>';
         }
      } else {
 
        echo '<option value = "1">HOA 1</option>';
        echo '<option value = "2">HOA 2</option>';
      }
    
     ?>


      </select>
      </div>
      <div class="form-item">
      <h4 class="form-item-title">Member 2 Fulltime?</h4>
      <select name = "fulltime2" id="fulltime2">
        <?php
        if ($potentialmember2) {
            if ($err_switch === 1) { 
              if ($potentialmember2['fulltime'] === 1) {
                echo '<option value = "1" selected>Fulltime</option>';
                echo '<option value = "0">Gone for the Summer</option>';
              } else {
                echo '<option value = "0" selected>Gone for the Summer</option>';
                echo '<option value = "1">Fulltime</option>';
              }
            } else {
          echo '<option value = "1">Fulltime</option>';
          echo '<option value = "0">Gone for the Summer</option>';
        }
        } else {
          echo '<option value = "1">Fulltime</option>';
          echo '<option value = "0">Gone for the Summer</option>';
        }

      ?>

      </select>
      </div>
      
      </div>
    </div>
   
    </div>
    <?php
     echo '<input type="hidden"  name="city1"   value="Tucson" >';
      echo '<input type="hidden" name="state1"  value="AZ" >';
      echo '<input type="hidden" name="zip1"    value="85739">';

      echo '<input type="hidden" name="city2"   value="Tucson"  >';
     echo '<input type="hidden"  name="state2"  value="AZ">';    
     echo '<input type="hidden"  name="zip2"    value="85739" >';   
 
     echo '<input type="hidden"  name="indprodid" value="'.$indProductID.'">';
     echo '<input type="hidden"  name="coupleprodid" value="'.$coupleProductID.'">';
     echo '<input type="hidden"  name="indpriceid" value="'.$indPriceID.'">';
     echo '<input type="hidden"  name="couplepriceid" value="'.$couplePriceID.'">';
     /* Discount prices */
    //  echo '<input type="hidden" name="indproddiscid" value="'.$indProductDiscID.'">';
    //  echo '<input type="hidden" name="coupleproddiscid" value="'.$coupleProductDiscID.'">';
    //  echo '<input type="hidden" name="indpricediscid" value="'.$indPriceDiscID.'">';
    //  echo '<input type="hidden" name="couplepricediscid" value="'.$couplePriceDiscID.'">';
     ?>
    </div>
    <button type="submit" name="submitMembership">Submit Membership Information</button><br>
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