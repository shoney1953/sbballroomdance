<?php
session_start();
date_default_timezone_set("America/Phoenix");
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/TempOnlineMember.php';
require_once '../models/PaymentProduct.php';


// $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';
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
$_SESSION['partialyearmem'] = 0;
$py = $_SESSION['partialyearmem'];
$memberProducts = $_SESSION['memberproducts'];
$_SESSTION['potentialMem1'] = [];
$_SESSTION['potentialMem2'] = [];
$potentialMem1 = [];
$potentialMem2 = [];
$database = new Database();
$db = $database->connect();

$paymentproduct = new PaymentProduct($db);
$user = new User($db);
$tempMember = new TempOnlineMember($db);
$chargeProductID = '';
$_SESSION['addmem2'] = 'NO';
$_SESSION['memsameadd'] = 'NO';

if (isset($_POST['submitMembership'])) {

  if (isset($_POST['discyear'])) {
      $chargeProductID = $_POST['indproddiscid'];
       $chargePriceID = trim($_POST['indpricediscid']);
       $_SESSION['partialyearmem'] = 1;
    } else {
      $chargeProductID = $_POST['indprodid'];
      $chargePriceID = trim($_POST['indpriceid']);
  }
   $_SESSION['chargeProductID'] = $chargeProductID;
   $_SESSION['chargePriceID'] = $chargePriceID;
  if (isset($_POST['firstname1'])) {
    $potentialMem1['firstname'] = $_POST['firstname1'];
    
  }
  if (isset($_POST['lastname1'])) {
    $potentialMem1['lastname'] = $_POST['lastname1'];
  }
  if (isset($_POST['email1'])) {
    $potentialMem1['email'] = $_POST['email1'];
  }  
  if (isset($_POST['phone1'])) {
    $potentialMem1['phone1'] = $_POST['phone1'];
  }
  if (isset($_POST['streetaddress1'])) {
    $potentialMem1['streetaddress'] = $_POST['streetaddress1'];
  } 
  if (isset($_POST['state1'])) {
    $potentialMem1['state'] = $_POST['state1'];
  }
  if (isset($_POST['city1'])) {
    $potentialMem1['city'] = $_POST['city1'];
  } 
  if (isset($_POST['zip1'])) {
    $potentialMem1['zip'] = $_POST['zip1'];
  }
  if (isset($_POST['hoa1'])) {
    $potentialMem1['hoa'] = $_POST['hoa1'];
  }
  if (isset($_POST['fulltime1'])) {

    $potentialMem1['fulltime'] = $_POST['fulltime1'];
  }

  if (isset($_POST['directorylist1'])) {
      $potentialMem1['directorylist'] = $_POST['directorylist1'];
    }
  //
  // 2nd member specified
  //
  
  if (isset($_POST['addmem2'])) {

    $_SESSION['addmem2'] =  'YES' ;
   if (isset($_POST['firstname2'])) {
    if ($_POST['firstname2'] !== ' ') {
    if (isset($_POST['discyear'])) {
        $chargeProductID = $_POST['coupleproddiscid'];
        $chargePriceID = trim($_POST['couplepricediscid']);
    } else {
          $chargeProductID = $_POST['coupleprodid'];
        $chargePriceID = trim($_POST['couplepriceid']);
    }
       $_SESSION['chargeProductID'] = $chargeProductID;
       $_SESSION['chargePriceID'] = $chargePriceID;
     $potentialMem2['firstname'] = $_POST['firstname2'];
   
    if (isset($_POST['lastname2'])) {
      $potentialMem2['lastname'] = $_POST['lastname2'];
    
    }
    if (isset($_POST['email2'])) {
      $potentialMem2['email'] = $_POST['email2'];
    }  
    if (isset($_POST['phone2'])) {
      $potentialMem2['phone1'] = $_POST['phone2'];
    }

      if (isset($_POST['directorylist2'])) {
      $potentialMem2['directorylist'] = $_POST['directorylist2'];
 
    }

    if (isset($_POST['mem2sameaddr'])) {
        $_SESSION['memsameaddr'] = 'YES';
        if (isset($_POST['streetaddress1'])) {
          $potentialMem2['streetaddress'] = $_POST['streetaddress1'];
        } 
        if (isset($_POST['state1'])) {
          $potentialMem2['state'] = $_POST['state1'];
        }
        if (isset($_POST['city1'])) {
          $potentialMem2['city'] = $_POST['city1'];
        } 
        if (isset($_POST['zip1'])) {
          $potentialMem2['zip'] = $_POST['zip1'];
        }
        if (isset($_POST['hoa1'])) {
          $potentialMem2['hoa'] = $_POST['hoa1'];
        }
        if (isset($_POST['fulltime1'])) {
          $potentialMem2['fulltime'] = $_POST['fulltime1'];
        }
     } else {
    
      if (isset($_POST['streetaddress2'])) {
        $potentialMem2['streetaddress'] = $_POST['streetaddress2'];
      } 
      if (isset($_POST['state2'])) {
        $potentialMem2['state'] = $_POST['state2'];
      }
      if (isset($_POST['city2'])) {
        $potentialMem2['city'] = $_POST['city2'];
      } 
      if (isset($_POST['zip2'])) {
        $potentialMem2['zip'] = $_POST['zip2'];
      }
      if (isset($_POST['hoa2'])) {
        $potentialMem2['hoa'] = $_POST['hoa2'];
      }
      if (isset($_POST['fulltime2'])) {
        $potentialMem2['fulltime'] = $_POST['fulltime2'];
      }
    }
  }
     }
  //     
   }
   $_SESSION['potentialMember1'] = $potentialMem1;
   $_SESSION['potentialMember2'] = $potentialMem2;

    $user->email = $potentialMem1['email'];
    $user->username = $potentialMem1['email'];

    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    } else {
       if (isset($_POST['mem2sameaddr'])) {
            $_SESSION['memsameaddr'] = "YES";
       }
       if (isset($_POST['addmem2'])) {
            $_SESSION['addmem2'] = 'YES';
       }
        $redirect = "Location: ".$_SESSION['joinonlineurl'].'?error=MEMBER 1 Email Invalid';
        header($redirect);
        exit;
    }

    if ($user->validate_user($user->username)) {
       if (isset($_POST['mem2sameaddr'])) {
            $_SESSION['memsameaddr'] = "YES";
       }
       if (isset($_POST['addmem2'])) {
            $_SESSION['addmem2'] = 'YES';
       }
      
      $redirect = "Location: ".$_SESSION['joinonlineurl'].'?error=MEMBER 1 Email Exists';
        header($redirect);
        exit;  
    } 
    if (isset($_POST['mem2sameaddr'])) {

       $user->email = $potentialMem2['email'];
        $user->username = $potentialMem2['email'];
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        } else {
            if (isset($_POST['mem2sameaddr'])) {
                  $_SESSION['memsameaddr'] = "YES";
            }
            if (isset($_POST['addmem2'])) {
                  $_SESSION['addmem2'] = 'YES';
            }
            $redirect = "Location: ".$_SESSION['joinonlineurl'].'?error=MEMBER 2 Email Invalid';
            header($redirect);
            exit;
        }

        if ($user->validate_user($user->username)) {
            if (isset($_POST['mem2sameaddr'])) {
                  $_SESSION['memsameaddr'] = "YES";
            }
            if (isset($_POST['addmem2'])) {
                  $_SESSION['addmem2'] = 'YES';
            }
          $redirect = "Location: ".$_SESSION['joinonlineurl'].'?error=MEMBER 2 Email Exists';
            header($redirect);
            exit;  
        } 
    }
  }

$formatphone = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Sign Up Confirmation</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../index.php">Back to Home</a></li>
            <li><a href="../joinonline.php">Back to Join Online</a></li>
        </ul>
        </div>
    </nav>
  <div class="content">
    <br><br><br>
  
      <h4>Please confirm the information submitted.</h4><br>
      <h4>Click the CONFIRM button to proceed, or the GO BACK button to modify information.</h4><br>
      <?php

        $paymentproduct->read_single($chargeProductID);
       $fprice = number_format(($paymentproduct->price/100),2);

         echo "<h4>You will be charged: $".$fprice." for ".$paymentproduct->name."</h4><br>";
        echo  '<div class="form-grid4">';
         echo '<div class="form-grid-div">';
         echo "</div>";
        echo '<div class="form-grid-div">';
        echo '<h4>Member 1 Information</h4>';
        echo '<ul>';
        echo "<li>First Name: ".$potentialMem1['firstname']."</li>";
        echo "<li>Last Name: ".$potentialMem1['lastname']."</li>";
        echo "<li>Email: ".$potentialMem1['email']."</li>";
        $formatphone = substr($potentialMem1['phone1'],0,3);
        $formatphone .= "-";
        $formatphone .= substr($potentialMem1['phone1'],3,3);
        $formatphone .= "-";
        $formatphone .= substr($potentialMem1['phone1'],6,4);
        echo "<li>Phone: ".$formatphone."</li>";
        echo "<li>Street Address: ".$potentialMem1['streetaddress']."</li>";
        echo "<li>HOA: ".$potentialMem1['hoa']."</li>";
        if ($potentialMem1['fulltime'] == 1) {
              echo "<li>Fulltime: Yes</li>";
            } else {
              echo "<li>Fulltime: No</li>";
            }
        if ($potentialMem1['directorylist'] == 1) {
              echo "<li>Directory List: Yes</li>";
            } else {
              echo "<li>Directory List: No</li>";
            }

        echo '</ul>';
        echo "</div>";
        if (isset($_POST['addmem2'])) {
 
           echo '<div class="form-grid-div">';
            echo '<h4>Member 2 Information</h4>';
            echo '<ul>';
            echo "<li>First Name: ".$potentialMem2['firstname']."</li>";
            echo "<li>Last Name: ".$potentialMem2['lastname']."</li>";
            echo "<li>Email: ".$potentialMem2['email']."</li>";
            $formatphone = substr($potentialMem2['phone1'],0,3);
            $formatphone .= "-";
            $formatphone .= substr($potentialMem2['phone1'],3,3);
            $formatphone .= "-";
            $formatphone .= substr($potentialMem2['phone1'],6,4);
            echo "<li>Phone: ".$formatphone."</li>";
            echo "<li>Street Address: ".$potentialMem2['streetaddress']."</li>";
            echo "<li>HOA: ".$potentialMem2['hoa']."</li>";
            if ($potentialMem2['fulltime'] == 1) {
              echo "<li>Fulltime: Yes</li>";
            } else {
              echo "<li>Fulltime: No</li>";
            }
    
            if ($potentialMem2['directorylist'] == 1) {
              echo "<li>Directory List: Yes</li>";
            } else {
              echo "<li>Directory List: No</li>";
            }
            echo '</ul>';
            echo '</div>';
        }
         echo "</div>";
         echo '<div class="form-grid4">';
        echo '<div class="form-grid-div">';
               echo "</div>";
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="joinconfirm.php">';
        echo '<div class="form-item">';
        echo '<br><button   type="submit" name="submitJoinConfirm">CONFIRM AND PROCEED</button>'; 
        echo '</div>';
        echo "</div>";
        echo '</form>';
        echo '<div class="form-grid-div">';

         echo '<br><button><a  title="Return and Resubmit Info" href="../joinonline.php?resubmit=resubmit">Return and Correct Information</a></button>';
          echo "</div>";
      

      ?>
      
  

      </div>
    <footer >

    <?php
  require '../footer.php';
?>
    
</div> 

</footer>
</body>
</html>
