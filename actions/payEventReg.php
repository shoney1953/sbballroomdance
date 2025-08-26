<?php
session_start();
date_default_timezone_set("America/Phoenix");
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/DinnerMealChoices.php';
$regs = $_SESSION['eventregistrations'];

$database = new Database();
$db = $database->connect();
$partnerEventReg = new EventRegistration($db);
$event = new Event($db);
$mealChoice1 = new DinnerMealChoices($db);
$mealChoice2 = new DinnerMealChoices($db);
$productID1 = '';
$productID2 = '';
$priceID1 = '';
$priceID2 = '';
$cost1 = 0;
$cost2 = 0;
$attendDinner = 0;
$totalCost = 0;
$payReg = [];
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

if (isset($_POST['submitPayReg'])) {

 foreach ($regs as $reg) {

       $payId = 'pay'.$reg['id'];

    if (isset($_POST["$payId"])) {
      
      if ($reg['id'] === $_POST["id"]) {
        $event->id = $reg['eventid'];
        $event->read_single();
        $productID1 = $event->eventproductid;
        $priceID1 = $event->eventmempriceid;
        $priceID2 = $event->eventmempriceid;
        if ($event->eventtype === 'Dinner Dance') {
          $reg['ddattenddinner'] = '1';
        }
        $cost1 = $event->eventcost * 100;
        if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
          $partnerEventReg->read_ByEventIdUser((int)($reg['eventid']), (int)($_SESSION['partnerid']));

          $payReg['regid2'] = $partnerEventReg->id;
        }
        if ($reg['ddattenddinner'] !== '0') {
          $attendDinner = 1;
      
          if ($reg['mealchoice'] !== '0') {
          $mealChoice1->id = $reg['mealchoice'];
          $mealChoice1->read_single();
   
          $productID1 = $mealChoice1->productid;
          $priceID1 = $mealChoice1->priceid;
          $cost1 = $mealChoice1->memberprice;
            } // mealchoice
        if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
             if ($partnerEventReg->mealchoice !== '0') {
          $mealChoice2->id = $partnerEventReg->mealchoice;
          $mealChoice2->read_single();
   
          $productID2 = $mealChoice2->productid;
          $priceID2 = $mealChoice2->priceid;
          $cost2 = $mealChoice2->memberprice;
             } // partner meal choice set
        } 
        } else {
          if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
               $cost2 = $event->eventcost * 100;
          }
        }// ddattend dinner

            $totalCost = $cost1 + $cost2;
              $payReg['totalcost'] = $totalCost;
            $payReg['eventid'] = $reg['eventid'];
            $payReg['regid1'] = $reg['id'];
            $payReg['eventname'] = $reg['eventname'];
            $payReg['eventdate'] = $reg['eventdate'];
            $payReg['eventtype'] = $reg['eventtype'];
            $payReg['userid'] = $_SESSION['userid'];
   
            $payReg['firstname1'] = $_SESSION['userfirstname'];
            $payReg['lastname1'] = $_SESSION['userlastname'];
            $payReg['email1'] = $_SESSION['useremail'];
            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
               $payReg['partnerid'] = $_SESSION['partnerid'];
                $payReg['firstname2'] = $_SESSION['partnerfirstname'];
                $payReg['lastname2'] = $_SESSION['partnerlastname'];
                $payReg['email2'] = $_SESSION['partneremail'];
            } else {
               $payReg['partnerid'] = '0';
               $payReg['firstname2'] = '';
                $payReg['flastname2'] = '';
                $payReg['email2'] = '';
            }
            $payReg['ddattenddinner'] = $reg['ddattenddinner'];
            if ($payReg['ddattenddinner'] !== '0') {
               $payReg['mealchoice1'] = $mealChoice1->id;
               $payReg['mealdesc1'] = $mealChoice1->mealdescription;
               $productID1 = $mealChoice1->productid;
              if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
               $payReg['mealchoice2'] = $mealChoice2->id;
               $payReg['mealdesc2'] = $mealChoice2->mealdescription;
               $productID2 = $mealChoice2->productid;
              }

            } else {
              $payReg['mealchoice1'] = '0';
              $payReg['mealdesc1'] = ' ';
              $payReg['mealchoice2'] = '0';
              $payReg['mealdesc2'] = ' ';
            }
           $payReg['cost1'] = $cost1;
           $payReg['cost2'] = $cost2;
           $payReg['productid1'] = $productID1;
           $payReg['productid2'] = $productID2;
           $payReg['priceid1'] = $priceID1;
           $payReg['priceid2'] = $priceID2;

          
      } // id matches reg
    } // payid set

  } // for each reg
   $_SESSION['payreg'] = $payReg;
} // submit set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC - Pay for Event Registrations</title>
</head>
<body>

<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="../index.php">Back to Home</a></li>
        <li><a href="../yourProfile.php">Back to Your Profile</a></li>
        <li><a href="../SBDCEvents.php">Back to Events</a></li>
    </ul>
    </div>
</nav> 

<div class="container-section" >
<div class="content">
    <br><br>

    <section id="eventpay" class="content">
        <h2>Event Registrations</h2>
    <div class="form-container">
    <?php
    echo "<h4> You have chosen to pay for registration(s) to ".$event->eventname." ".$event->eventtype." on ".$event->eventdate."   </h4>";
    if ($attendDinner === 0) {
       
       if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
          echo "<h4> You and your partner have elected not to attend dinner   </h4>";
       } else {
        echo "<h4> You have elected not to attend dinner   </h4>";
       }
    } else {
      echo "<h4> You have elected to attend dinner   </h4>";
      echo "<h4> Your meal selection is ".$mealChoice1->mealdescription." at a cost of $".number_format($cost1/100,2).".</h4>";
        if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
            echo "<h4> Your partners meal selection is ".$mealChoice2->mealdescription." at a cost of $".number_format($cost2/100,2).".</h4>";
        }
    }
    echo "<br><h4>The total cost will be $".number_format($totalCost/100,2).".</h4>";

        echo '<div class="form-grid4">';
        echo '<div class="form-grid-div">';
        echo "</div>";
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="regPayConfirm.php">';
        echo '<div class="form-item">';
        echo '<br><button   type="submit" name="submitRegPay">CONFIRM AND PROCEED</button>'; 
        echo '</div>';
        echo "</div>";
        echo '</form>';
        echo '<div class="form-grid-div">';
         echo '<br><button><a  title="Return and Resubmit Info" href="../yourProfile.php"</a>Return and Resubmit Information</button>';
          echo "</div>";

          ?>
    </div>

    </section>
</div>
</div>
<footer >
<?php
  require '../footer.php';
?>
</body>
</html>