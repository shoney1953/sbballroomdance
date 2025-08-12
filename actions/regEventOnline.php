<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
   $stripeSecretKey = $_SESSION['prodkey'] ;
   if (($_SESSION['testmode'] === 'YES') && (isset($_SESSION['testmode']))) {
    $stripeSecretKey = $_SESSION['testkey'] ;
   }
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}
\Stripe\Stripe::setApiKey($stripeSecretKey);
$stripe = new \Stripe\StripeClient($stripeSecretKey);
$events = $_SESSION['upcoming_events'];

$eventname = '';
$eventdate = '';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);
$user = new User($db);
$mealchoices = new DinnerMealChoices($db);
$message = '';
$result = 0;
$danceCost = 0;
$regSelected = [];
$regAll = '';

$eventNum = 0;
$regUserid1 = 0;
$regUserid2 = 0;

$smealCHK1 = '';
$mChoices = [];
$meal1 = '';
$mealprice1 = '';
$mealid1 = 0;
$mealproductid1 = '';
$mealpriceid1 = '';
$dietaryRestriction1 = '';
$smealCHK2 = '';

$meal2 = '';
$mealprice2 = '';
$mealid2 = 0;
$mealproductid2 = '';
$mealpriceid2 = '';
$dietaryRestriction2 = '';
$drID1 = '';
$drID2 = '';
$id_int = 0;
$potentialReg1 = [];
$potentialReg2 = [];
$num_registered = 0;
$currentDate = new DateTime();

if (!isset($_POST['submitEventReg'])) {

     $redirect = "Location: ".$_SESSION['homeurl'];  
     header($redirect); 
     exit;
}
    $potentialReg1['visitor'] = $_POST['visitor'];
    $potentialReg1['firstname'] = htmlentities($_POST['regFirstName1']);
    $potentialReg1['lastName'] = htmlentities($_POST['regLastName1']);
    $potentialReg1['email'] = htmlentities($_POST['regEmail1']);  
    $potentialReg1['email'] = filter_var($potentialReg1['email'], FILTER_SANITIZE_EMAIL); 

   if (isset($_POST['message'])) {
       $potentialReg1['message'] = htmlentities($_POST['message']); 
       $potentialReg2['message'] = htmlentities($_POST['message']); 
       }
   if (isset($_POST['regEmail2'])) {
    $potentialReg2['visitor'] = $_POST['visitor'];
    $potentialReg2['firstname'] = htmlentities($_POST['regFirstName2']);
    $potentialReg2['lastName'] = htmlentities($_POST['regLastName2']);
    $potentialReg2['email'] = htmlentities($_POST['regEmail2']);  
    $potentialReg2['email'] = filter_var($potentialReg2['email'], FILTER_SANITIZE_EMAIL); 
 
    }

  

    foreach ($events as $event) {

        $chkboxID = "ev".$event['id'];
        $chkboxID2 = "dd".$event['id'];
        $chkboxID3 = "ch".$event['id'];
        $chkboxID4 = "sb".$event['id'];
        $messID = "mess".$event['id'];
        $evTypeID = "et".$event['id'];
        $evCostID = "ec".$event['id'];

        if (isset($_POST["$messID"])) {            
            $message = $_POST["$messID"];
        }
         $minDanceCost = 0;
        if (isset($_POST["$evCostID"])) {            
            $minDanceCost = $_POST["$evCostID"];
        }
       if (isset($_POST["$chkboxID"])) {
      
        $eventNum = (int)substr($chkboxID,2);

         if ($event['id'] == $eventNum) {
                  
              $eventId = $event['id'];
              $evTypeID = "et".$event['id'];
              $potentialReg1['eventid'] = $event['id'];
              $potentialReg1['orgemail'] = $event['orgemail'];
              $potentialReg1['eventdate'] = $event['eventdate'];
              $potentialReg1['eventtype'] = $event['eventtype'];
              $potentialReg1['eventname'] = $event['eventname'];
              $eventname = $event['eventname'];
              $eventdate = $event['eventdate'];
              $potentialReg1['firstname'] = $_POST['regFirstName1'];
              $potentialReg1['lastname'] = $_POST['regLastName1'];
              $potentialReg1['email'] = $_POST['regEmail1'];
              if ($event['eventtype'] === 'Dinner Dance') {
                   $potentialReg1['ddattenddinner'] = 1;
              } else {
                  if (isset($_POST["$chkboxID2"])) {
                    $potentialReg1['ddattenddinner'] = 1;
                  }
               if ($potentialReg1['ddattenddinner'] !== 1) {
                  $potentialReg1['ddattenddinner'] = 0;
                  $potentialReg1['productid'] = $event['eventproductid'];
                  $potentialReg1['memberprice'] =  $event['eventcost'];
                  $potentialReg1['eventcost'] = $event['eventcost']; 
                  $potentialReg1['guestprice'] =  $event['eventguestcost'];
                  $potentialReg1['guestpriceid'] =  $event['eventguestpriceid'];
                  $potentialReg1['priceid'] =  $event['eventmempriceid'];
                }

              }
          
              if ($potentialReg1['ddattenddinner'] === 1) {

               $evCostID = "ec".$event['id'];
               $potentialReg1['eventcost'] = $_POST["$evCostID"];
              $drID1 = "dr1".$event['id'];
              if (isset($_POST["$drID1"])) {
                  $potentialReg1['dietaryrestriction'] = $_POST["$drID1"];
              }
               $drID2 = "dr2".$event['id'];
              if (isset($_POST["$drID2"])) {
               $potentialReg2['dietaryrestriction'] = $_POST["$drID2"];
              }
              $result = $mealchoices->read_ByEventId($event['id']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid
                        );
                        array_push($mChoices, $meal_item);              
                    } // while
                
                      foreach ($mChoices as $choice) {
  
                         $smealCHK1 = 'sm1'.$choice['id'];
                    
                         if (isset($_POST["$smealCHK1"])) {
                          
                                  $mealid1 = $choice['id'];
                                  $potentialReg1['mealchoice'] =  $choice['id'];
                                  $potentialReg1['mealdesc'] = $choice['mealname'];
                                  $potentialReg1['productid'] = $choice['productid'];
                                  $potentialReg1['memberprice'] =  $choice['memberprice'];
                                  $potentialReg1['guestprice'] =  $choice['guestprice'];
                                  $potentialReg1['guestpriceid'] =  $choice['guestpriceid'];
                                  $potentialReg1['priceid'] =  $choice['priceid'];
                              
                               } //smeal1

                         $smealCHK2 = 'sm2'.$choice['id'];

                         if (isset($_POST["$smealCHK2"])) {    

                                  $mealid2 = $choice['id'];

                                  $meal2 = $choice['mealname'];
                                  $mealprice2 = $choice['memberprice'];
                                  $potentialReg2['productid'] = $choice['productid'];
                                  $mealpriceid2 = $choice['priceid'];
                                  $potentialReg2['mealchoice'] =  $choice['id'];
                                  $potentialReg2['mealdesc'] = $choice['mealname'];                       
                                  $potentialReg2['memberprice'] =  $choice['memberprice'];
                                  $potentialReg2['guestprice'] =  $choice['guestprice'];
                                  $potentialReg2['guestpriceid'] =  $choice['guestpriceid'];
                                  $potentialReg2['priceid'] =  $choice['priceid'];

                               } //smeal2
                      } // foreach choice
                   
                }  // rowCount 

              }
                $num_registered++;
                $eventId = $event['id'];           
                 }

                $potentialReg1['registeredby'] = $_SESSION['username'];

                $potentialReg1['message'] = $message;

   
//   registrant 2
             if (isset($_POST['regFirstName2'])) {
                $potentialReg2['eventid'] = $event['id'];
                $potentialReg2['firstname'] = $_POST['regFirstName2'];
                $potentialReg2['lastname'] = $_POST['regLastName2'];
                $potentialReg2['email'] = $_POST['regEmail2'];
                $potentialReg2['registeredby'] = $_SESSION['username'];
                $potentialReg2['userid'] = $_POST['regUserid2'];
                if (isset($_POST["$evCostID"])) {
                   $potentialReg2['eventcost'] = $_POST["$evCostID"];
                } else {
                  if ($potentialReg1['visitor'] === '1') {
                    $potentialReg2['eventcost'] = $event['eventguestcost'];
                  } else {
                    $potentialReg2['eventcost'] = $event['eventcost'];
                  }                   
                }

                if ($potentialReg1['eventtype'] === 'Dinner Dance') {
                  $potentialReg2['ddattenddinner'] = 1;
                } else {
                if (isset($_POST["$chkboxID2"])) {
                    $potentialReg2['ddattenddinner'] = 1;                          
                } 
              }
                if ($potentialReg2['ddattenddinner'] !== 1 ) {
                  $potentialReg2['ddattenddinner'] = 0;
                  $potentialReg2['productid'] = $event['eventproductid'];
                  $potentialReg2['memberprice'] =  $event['eventcost'];
                  $potentialReg2['eventcost'] = $event['eventcost']; 
                  $potentialReg2['guestprice'] =  $event['eventguestcost'];
                  $potentialReg2['guestpriceid'] =  $event['eventguestpriceid'];
                  $potentialReg2['priceid'] =  $event['eventmempriceid'];
                }
                

                if ($potentialReg2['ddattenddinner'] === 1) {
                    $potentialReg2['mealchoice'] = $mealid2;
                    if ($dietaryRestriction2 !== '') {
                        $potentialReg2['dietaryrestriction'] = $dietaryRestriction2;
                    }
                }

                $potentialReg2['message'] = $message;

                // $result = $eventReg->checkDuplicate($potentialReg2['email'], $potentialReg2['eventid']);
                // if ($result) {
                //         $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email2 Please check your profile.';
                //         header($redirect);
                //         exit; 
                // } //endresult

                }  //endfirstname2

            } // end if eventid            
       } //end isset
   $_SESSION['potentialReg1'] = $potentialReg1;
   $_SESSION['potentialReg2'] = $potentialReg2;
    if ($num_registered === 0) {

            $redirect = "Location: ".$_SESSION['regeventurl'].'?error=No Events Selected Please check at least 1 event and resubmit.';
            header($redirect);
            exit; 
    }

 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Registration Confirmation</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../index.php">Back to Home</a></li>
            <li><a href="../SBDCEvents.php">Back To Events</a></li>
        </ul>
        </div>
    </nav>
  <div class="content">
    <br><br><br>
  
      <h4>Please confirm the information submitted.</h4><br>
      <h4>Click the CONFIRM button to proceed, or the RETURN button to modify information.</h4><br>
      <?php

  
  
       $danceCost = 0;
       $cost1 = 0;
       $cost2 = 0;
       $totalDanceCost = 0;

      
  
      if ($potentialReg1['eventtype'] === 'Dance Party') {
         if ($potentialReg1['ddattenddinner'] === 1) {
        if ($_POST['visitor'] === '1') { 
         $danceCost = $danceCost + $potentialReg1['guestprice'];
         $cost1 = $potentialReg1['guestprice'];
         if (isset($_POST['regFirstName2'])) { 
            $cost2 = $potentialReg2['guestprice'];
            $danceCost = $danceCost + $potentialReg2['guestprice'];
         }
       } else {
          $danceCost = $danceCost + $potentialReg1['memberprice'];

           $cost1 = $potentialReg1['memberprice'];
       
          if (isset($_POST['regFirstName2'])) { 
            $cost2 = $potentialReg2['memberprice'];
            $danceCost = $danceCost + $potentialReg2['memberprice'];
          
         }
       } // visitor
      } // attend dinner

       if ($potentialReg1['ddattenddinner'] !== 1) {
        if ($_POST['visitor'] === '1') { 
           $danceCost = $potentialReg1['eventcost'] * 100;
           $cost1 = $danceCost;      
            if (isset($_POST['regFirstName2'])) { 
            $cost2 = $dancecost; 
            $danceCost = $danceCost + ($potentialReg1['eventcost'] * 100);
            }
           } else {
             $danceCost = $potentialReg1['eventcost'] * 100;
            $cost1 = $danceCost;
            if (isset($_POST['regFirstName2'])) {      
            $cost2 = $danceCost; 
             $danceCost =$danceCost + ($potentialReg1['eventcost'] * 100);
            }
          }

          } // not attend dinner
  
      } // dance party


        if ($potentialReg1['eventtype'] === 'Dinner Dance') {
        if ($_POST['visitor'] === '1') {
         $danceCost = $danceCost + $potentialReg1['guestprice'];
         $cost1 = $potentialReg1['guestprice'];
         if (isset($_POST['regFirstName2'])) { 
            $cost2 = $potentialReg2['guestprice'];
            $danceCost = $danceCost + $potentialReg2['guestprice'];
         }
       } else {
          $danceCost = $danceCost + $potentialReg1['memberprice'];
           $cost1 = $potentialReg1['memberprice'];
          if (isset($_POST['regFirstName2'])) { 
            $cost2 = $potentialReg2['memberprice'];
            $danceCost = $danceCost + $potentialReg2['memberprice'];
         }
       }
        
      } // dinner dance

        echo "<h4> You are registering for ".$eventname." on ".$eventdate.".</h4>";
         $ftotalprice = number_format(($danceCost/100),2);
         $fprice1 = number_format(($cost1/100),2);
         $fprice2 = number_format(($cost2/100),2);
         echo '<div class="list-box">';

          if (($potentialReg1['eventtype'] === 'Dance Party') && $potentialReg1['ddattenddinner'] !== 1) {
                echo "<h4>You have selected the following options(s). </h4><br>";
                echo "<ol>";
                echo "<li>Dance Only for ".$_POST['regFirstName1']." at a cost of ".$fprice1.".</li>";
                if (isset($_POST['regFirstName2'])) {
                  echo "<li>Dance Only for ".$_POST['regFirstName2']." at a cost of ".$fprice2.".</li>";
                   }
                echo "</ol>";
          }
          if (($potentialReg1['eventtype'] === 'Dinner Dance') ||
          (($potentialReg1['eventtype'] === 'Dance Party') && $potentialReg1['ddattenddinner'] === 1)) { 
         echo "<h4>You have selected the following meal(s). </h4><br>";
         $dr1 = '';
         if ($potentialReg1['dietaryrestriction'] != '') {
            $dr1 = ' with a dietary restriction of ';
            $dr1 .= $potentialReg1['dietaryrestriction'];
         }
          $dr2 = '';
          if ($potentialReg2) {
         if ($potentialReg2['dietaryrestriction'] != '') {
            $dr2 = ' with a dietary restriction of ';
            $dr2 .= $potentialReg2['dietaryrestriction'];
         }
        }
         echo "<ol>";
         echo "<li>Meal Choice for ".$_POST['regFirstName1'].": ".$potentialReg1['mealdesc']." at a cost of ".$fprice1." ".$dr1.".</li>";
         if (isset($_POST['regFirstName2'])) {
             echo "<li>Meal Choice for ".$_POST['regFirstName2'].": ".$potentialReg2['mealdesc']." at a cost of ".$fprice2." ".$dr2."</li>";
          }
        }
         echo "</ol>";
            echo "<br><h4>You will be charged a total of: $".$ftotalprice." </h4><br>";
         echo "</div>";
        $_SESSION['potentialreg1'] = $potentialReg1;
        $_SESSION['potentialreg2'] = $potentialReg2;
         echo '<div class="form-grid4">';
        echo '<div class="form-grid-div">';
        echo "</div>";
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="regEventConfirm.php">';
        echo '<div class="form-item">';
        echo '<br><button   type="submit" name="submitRegConfirm">CONFIRM AND PROCEED</button>'; 
        echo '</div>';
        echo "</div>";
        echo '</form>';
        echo '<div class="form-grid-div">';

         echo '<br><button><a  title="Return and Resubmit Info" href="../regForEventsOnline.php?resubmit=resubmit">Return and Correct Information</a></button>';
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
