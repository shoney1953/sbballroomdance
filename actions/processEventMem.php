<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/EventRegistration.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';

$upcomingEvents = $_SESSION['upcoming_events'];
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$reg = new EventRegistration($db);
$partnerReg = new EventRegistration($db);
$user = new User($db);
$mChoices = new DinnerMealChoices($db);
$mealChoices = [];
$mealChk = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Process Events</title>
</head>
<body>
<nav class="nav">
        <div class="container">      
        <ul>  
            <li><a href="../index.php">Back to Home</a></li>
        </ul>
        </div>
</nav>
 <br><br><br>
<section id="processevents" class="content">
<div class="section-back">

<?php

foreach ($upcomingEvents as $event) {
    $upChk = "up".$event['id'];
    $rpChk = "rp".$event['id'];
    $delChk = "del".$event['id'];
    $regChk = "reg".$event['id'];
    $payChk = "pay".$event['id'];

    if ($event['id'] === $_POST['eventId']) {
        if (isset($_POST["$rpChk"])) {
            // unset($_POST["$rpChk"]);
            echo "<h4>Generated Report for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
            echo "<form  name='reportEventForm'   method='POST' action='reportEvent.php'> ";
            echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
            echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
            unset($_POST["$rpChk"]);
            break;
            }
     switch ($event['eventtype']) {
        case "BBQ Picnic": 
            if (isset($_POST["$regChk"])) {
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
             
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
               }
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<div class="form-grid-div">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Add Registrations(s)?</h4>';
                echo "<input type='checkbox' title='Check to Add Registration(s)' name='addRegs'> ";
                echo '</div>'; // end of form item
                echo '<div class="form-grid">';
                  if ($_SESSION['role'] === 'visitor') {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                  } else {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                  }
  
             
                echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                 echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']." ".$_SESSION['useremail']."</h4>";
               } else {
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
               }
         
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem1CHK' name='mem1Chk' checked>";
                echo '</div>';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Meal?</h4>";
                echo "<input type='checkbox'  title='Check to attend meal' id='ddattm1' name='ddattm1'>";
                echo '</div>';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Cornhole?</h4>";
                echo "<input type='checkbox'  title='Check to play Cornhole' id='ch1' name='ch1'>";
                echo '</div>';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Softball?</h4>";
                echo "<input type='checkbox'  title='Check to play Softball' id='sb1' name='sb1'>";
                echo '</div>';
                echo '</div>'; // form grid
                echo '</div>'; // form grid div
                   if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                    echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                    echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                    echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem2CHK' name='mem2Chk' checked>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Meal?</h4>";
                echo "<input type='checkbox'  title='Check to attend meal' id='ddattm2' name='ddattm2'>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Cornhole?</h4>";
                echo "<input type='checkbox'  title='Check to play Cornhole' id='ch2' name='ch2'>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Softball?</h4>";
                echo "<input type='checkbox'  title='Check to play Softball' id='sb2' name='sb2'>";
                echo '</div>';

                 echo '</div>'; // form grid
                echo '</div>'; // form grid div
                 }
                    echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container 
                echo '</form>';
               }
               
            


             if (isset($_POST["$delChk"])) {
             
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
                   $remID2 = "rem".$partnerReg->id;
               }
               $remID1 = "rem".$reg->id;
            
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                  echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Remove Registration(s)?</h4>';
                echo "<input type='checkbox' title='Check to Remove Event Registration(s)' name='deleteRegs'> ";
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                if ($_SESSION['role'] === 'visitor') {
                   echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                } else {
                     echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                }
             
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>';
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                echo '</div>';
                 }
              
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                  echo  '</form>';
                echo '</div>'; // end of form container
             } // end of delete check

            if (isset($_POST["$upChk"])) {
              if ($_SESSION['role'] === 'visitor') {
                  $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
              } else {
                  $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
              }
        
        
            echo  '<form method="POST" action="updateBBQEventRegt.php">  ';
       
                $chID = "ch".$reg->id;
                $sbID = "sb".$reg->id;
                $updID = "upd".$reg->id;
                $dddinID = "dddin".$reg->id;
                echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
             if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
         
                $chID2 = "ch2".$partnerReg->id;
                $sbID2 = "sb2".$partnerReg->id;
                $dddinID2 = "dddin2".$partnerReg->id;
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Modify BBQ Picnic Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";

                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                
                 echo '<div class="form-item">';
                 if ($_SESSION['role'] === 'visitor') {
                    echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
                 } else {
                   echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
                 }
                
                
                echo '</div>';

                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
          
                if ($reg->ddattenddinner === '1') {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."'>";
                  }
   
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
         
                if ($reg->cornhole === '1') {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."'>";
                  }
               echo '</div>'; // end of form item
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
        
                if ($reg->softball === '1') {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."'>";
                }
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid
                echo '</div>'; // end of form grid div
              if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                 echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                   echo '<div class="form-grid-div">';
                   echo '<div class="form-grid">';
          
                echo '<div class="form-item">';
                echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
                echo '</div>';
               
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                if ($partnerReg->ddattenddinner === '1') {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID2."' name='".$dddinID2."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID2."' name='".$dddinID2."'>";
                  }
   
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                if ($partnerReg->cornhole === '1') {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID2."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID2."'>";
                  }
               echo '</div>'; // end of form item
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
                if ($partnerReg->softball === '1') {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID2."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID2."'>";
                }
                echo '</div>'; // end of form grid
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid div
                    }
                echo '<button type="submit" name="submitUpdateBBQReg">Submit Updates</button>';
                echo '</div>'; // end of form container
                echo '</form>';
            }
        } // end of update checked
        break;
   
        case "Dance Party": 
               if (isset($_POST["$regChk"])) {
                if ($_SESSION['role'] === 'visitor') {
                    $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                   $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
             
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
               }
              $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
              } // while $row
            } // rowcount
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventPt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<input type="hidden" name="eventproductid" value='.$event['eventproductid'].'>';
                echo '<input type="hidden" name="eventcost" value='.$event['eventcost'].'>';
                echo '<input type="hidden" name="eventname" value='.$event['eventname'].'>';
                echo '<input type="hidden" name="eventtype" value='.$event['eventtype'].'>';
                echo '<input type="hidden" name="eventdate" value='.$event['eventdate'].'>';
                echo '<input type="hidden" name="orgemail" value='.$event['orgemail'].'>';
                echo '<input type="hidden" name="priceid" value='.$event['eventmempriceid'].'>';  
                echo '<input type="hidden" name="guestpriceid" value='.$event['eventguestpriceid'].'>';     
                echo '<input type="hidden" name="eventguestcost" value='.$event['eventguestcost'].'>';

                if ($_SESSION['role'] === 'visitor') {
                  echo '<input type="hidden" name="visitor" value="1">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                } else {
                  echo '<input type="hidden" name="visitor" value="0">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                }

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                   echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                   echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                   echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                 }
                 echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Pay Online?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='payonline' name='payonline'>";
                echo '</div>';
               
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Dinner?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='ddattdin' name='ddattdin' onclick='displayMeals()'>";
                echo '</div>';

                 if ($event['eventform']) {
                echo '<div class="form-item" id="eventform">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>VIEW or PRINT FORM</a></h4>";
                echo '</div>'; // end of form item   
                   }
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Message to Event Organizer</h4>';
                  echo "<textarea  title='Enter any message to event organizer' name='message' rows='1' cols='20'></textarea>";
                  echo '</div>'; // form item
                // echo '</div>'; // form grid 
                echo '<div class="form-grid-div hidden" id="memMealChoice">';
                if ($_SESSION['role'] === 'visitor') {
                  echo "<h4 class='form-title-left'>Meal Selection for for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname'].":</h4>";
                } else {
                  echo "<h4 class='form-title-left'>Meal Selection for for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname'].":</h4>";
                }
             
                echo '<div class="form-grid">';

                foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
                  echo '<div class="form-item">';
           
                  echo "<h4 class='form-title-left'>".$choice['mealname']." <input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk ."'></h4>";
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
          
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr1' value='".$_SESSION['dietaryrestriction']."' >"; 
                  echo "</div>";
                echo '</div>'; // form grid div

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-grid-div hidden" id="partMealChoice">';
                echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname'].":</h4>";
                echo '<div class="form-grid">';
  
                // echo '<div class="form-item">';
        
                // echo '</div>'; // end of form item
                foreach ($mealChoices as $choice){
                  $mealChk2 = 'meal2'.$choice['id'];
                  echo '<div class="form-item">';
                  echo "<h4 class='form-title-left'>".$choice['mealname']." <input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'></h4>";
                    echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  // echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice

    
                  echo '</div>'; // form grid
                  // echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr2' value='".$_SESSION['partnerdietaryrestriction']."' >"; 
                  // echo "</div>";
                  echo '</div>'; // form grid div

                }
                
               echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container
              }
             if (isset($_POST["$delChk"])) {
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
                 
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
                   $remID2 = "rem".$partnerReg->id;
               }
               $remID1 = "rem".$reg->id;
            
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                  echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Remove Registration(s)?</h4>';
                echo "<input type='checkbox' title='Check to Remove Event Registration(s)' name='deleteRegs'> ";
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                if ($_SESSION['role'] === 'visitor') {
                     echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                } else {
                   echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                }
               
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>';
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                echo '</div>';
                 }
                echo  '</form>';
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo '</div>'; // end of form container
             } // end of delete check

            if (isset($_POST["$upChk"])) {
      
              if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
             if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);  
               }
           
             $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
              } // while $row
            } // rowcount
            echo '<div class="form-container"';
            echo "<h1 class='form-title'>Update Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
            echo '<form method="POST" name="MemberUpdateEventMeals" action="updateMealEventRegt.php">';  
            echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                 }
             echo '<div class="form-grid-div">';
             
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            if ($_SESSION['role'] === 'visitor') {
                  echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
            } else {
             echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
            }
        
            echo '</div>'; // end of form item
            if ($reg->paid !== '1') {
                  echo '<div class="form-item">';
                   if ($reg->ddattenddinner === '1') {
                          echo "<h4 class='form-item-title'>Attend Dinner</h4>";
                        echo "<input type='checkbox'  title='Attend Dinner' id='ddattdin1' name='ddattdin1' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Attend dinner' id='ddattdin1' name='ddattdin1' >";
                  }
                  echo '</div>'; // end of form item
            } else {
                echo "<input type='hidden'   id='ddattdin1' name='ddattdin1' value".$reg->ddattenddinner.">";
            }
     
            foreach ($mealChoices as $choice){
               $mealChk = 'meal'.$choice['id'];
            //    if ($reg->mealchoice !== '0') {
                     echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";
                  if ($reg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."'>";
                  }
                    echo '</div>'; // end of form item
               
            }
       
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
            echo '<div class="form-grid-div">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
            echo '</div>'; // end of form item
            if ($partnerReg->paid !== '1') {
                  echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Attend Dinner</h4>";
                   if ($partnerReg->ddattenddinner === '1') {
                        echo "<input type='checkbox'  title='Meal Choice' id='ddattdin2' name='ddattdin2' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='ddattdin2' name='ddattdin2' >";
                  }
                  echo '</div>'; // end of form item                
            } else {
                echo "<input type='hidden'   id='ddattdin2' name='ddattdin2' value".$reg->ddattenddinner.">";
            }
    
              foreach ($mealChoices as $choice){
                     $mealChk2 = 'meal2'.$choice['id'];
                    echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";

                  if ($partnerReg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."'>";
                  }
                  echo '</div>';
               
            }
        
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               }
        
            echo '<button type="submit" name="submitModifyRegs">Modify Registration(s)</button>';
            echo '</div>'; // end of form container
              echo '</div>'; // end form container
              echo '</form>';
           }
        break;

        case "Dinner Dance":
               if (isset($_POST["$regChk"])) {
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
              
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
               }
              $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
              } // while $row
            } // rowcount
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventPt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<input type="hidden" name="eventproductid" value='.$event['eventproductid'].'>';
                echo '<input type="hidden" name="eventcost" value='.$event['eventcost'].'>';
                echo '<input type="hidden" name="eventname" value='.$event['eventname'].'>';
                echo '<input type="hidden" name="eventtype" value='.$event['eventtype'].'>';
                echo '<input type="hidden" name="eventdate" value='.$event['eventdate'].'>';
                echo '<input type="hidden" name="orgemail" value='.$event['orgemail'].'>';
                echo '<input type="hidden" name="priceid" value='.$event['eventmempriceid'].'>';  
                echo '<input type="hidden" name="guestpriceid" value='.$event['eventguestpriceid'].'>';     
                echo '<input type="hidden" name="eventguestcost" value='.$event['eventguestcost'].'>';


                if ($_SESSION['role'] === 'visitor') {
                  echo '<input type="hidden" name="visitor" value="1">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                } else {
                  echo '<input type="hidden" name="visitor" value="0">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                }

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                   echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                   echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                   echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                 }
                 echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Pay Online?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='payonline' name='payonline'>";
                echo '</div>';
               

                 if ($event['eventform']) {
                echo '<div class="form-item" id="eventform">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>VIEW or PRINT FORM</a></h4>";
                echo '</div>'; // end of form item   
                   }
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Message to Event Organizer</h4>';
                  echo "<textarea  title='Enter any message to event organizer' name='message' rows='1' cols='20'></textarea>";
                  echo '</div>'; // form item
                // echo '</div>'; // form grid 
                echo '<div class="form-grid-div" id="memMealChoice">';
                 if ($_SESSION['role'] === 'visitor') {
                       echo "<h4 class='form-title-left'>Meal Selection for for ".$_SESSION['visitorfirstname']." ".$_SESSION['userlastname'].":</h4>";
                     } else {
                       echo "<h4 class='form-title-left'>Meal Selection for for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname'].":</h4>";
                     }
            
                echo '<div class="form-grid">';

                foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
                  echo '<div class="form-item">';
           
                  echo "<h4 class='form-title-left'>".$choice['mealname']." <input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk ."'></h4>";
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
          
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr1' value='".$_SESSION['dietaryrestriction']."' >"; 
                  echo "</div>";
                echo '</div>'; // form grid div

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-grid-div" id="partMealChoice">';
                echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname'].":</h4>";
                echo '<div class="form-grid">';
  
                // echo '<div class="form-item">';
        
                // echo '</div>'; // end of form item
                foreach ($mealChoices as $choice){
                  $mealChk2 = 'meal2'.$choice['id'];
                  echo '<div class="form-item">';
                  echo "<h4 class='form-title-left'>".$choice['mealname']." <input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'></h4>";
                    echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  // echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice

                  echo '</div>'; // form grid
                  // echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr2' value='".$_SESSION['partnerdietaryrestriction']."' >"; 
                  // echo "</div>";
                  echo '</div>'; // form grid div

                }
                
               echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container
              }


              if (isset($_POST["$delChk"])) {
        
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
                   $remID2 = "rem".$partnerReg->id;
               }
               $remID1 = "rem".$reg->id;
            
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                  echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Remove Registration(s)?</h4>';
                echo "<input type='checkbox' title='Check to Remove Event Registration(s)' name='deleteRegs'> ";
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                if ($_SESSION['role'] === 'visitor') {
                    echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                } else {
                   echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                }
              
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>';
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                echo '</div>';
                 }
                echo  '</form>';
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo '</div>'; // end of form container
             } // end of delete check

            if (isset($_POST["$upChk"])) {
         
               if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
             if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);  
               }
           
             $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
              } // while $row
            } // rowcount
            echo '<div class="form-container"';
            echo "<h1 class='form-title'>Update Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
            echo '<form method="POST" name="MemberUpdateEventMeals" action="updateMealEventRegt.php">';  
            echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
            echo '<input type="hidden" name="ddattdin1" value="1">';
            echo '<input type="hidden" name="ddattdin2" value="1">';
            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                 }
             echo '<div class="form-grid-div">';
             
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                    echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
               } else {
                echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
               }
        
            echo '</div>'; // end of form item
           
     
            foreach ($mealChoices as $choice){
               $mealChk = 'meal'.$choice['id'];
            //    if ($reg->mealchoice !== '0') {
                     echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";
                  if ($reg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."'>";
                  }
                    echo '</div>'; // end of form item
               
            }
       
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
          echo '<div class="form-grid-div">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
            echo '</div>'; // end of form item
          
    
              foreach ($mealChoices as $choice){
                     $mealChk2 = 'meal2'.$choice['id'];
                    echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";
                  if ($partnerReg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."'>";
                  }
                  echo '</div>';
               
            }
        
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               }
        
            echo '<button type="submit" name="submitModifyRegs">Modify Registration(s)</button>';
            echo '</div>'; // end of form container
              echo '</div>'; // end form container
              echo '</form>';
           }
        break;

        case "Meeting":
              if (isset($_POST["$delChk"])) {
                if ($_SESSION['role'] === 'visitor') {
                     $reg->read_ByEventIdVisitor($event['id'],$_SESSION['username']);
                } else {
                     $reg->read_ByEventIdUser($event['id'],$_SESSION['userid']);
                }
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                $partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid']);
                   $remID2 = "rem".$partnerReg->id;
               }
               $remID1 = "rem".$reg->id;
            
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                  echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Remove Registration(s)?</h4>';
                echo "<input type='checkbox' title='Check to Remove Event Registration(s)' name='deleteRegs'> ";
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                      
                          echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                     } else {
                      
                      echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                     }
        
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>';
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                echo '</div>';
                 }
                echo  '</form>';
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo '</div>'; // end of form container
             } // end of delete check
        break;

        default:
        break;

        } // end switch

    } // eventid matches
} // foreach upcoming event


?>
</div>
</section>
<footer >
    <?php
    require '../footer.php';
   ?>
</footer>
</body>
</html>
  <script>
        function displayMeals() {

        // Select the element
        if (document.getElementById('ddattdin').checked) {
    
        var element1 = document.getElementById('memMealChoice');
        element1.classList.remove('hidden');
          var element1 = document.getElementById('partMealChoice');
        element1.classList.remove('hidden');
        }
        else {
          
            var element1 = document.getElementById('memMealChoice');
            element1.classList.add('hidden');
            var element1 = document.getElementById('partMealChoice');
            element1.classList.add('hidden');

            }
        }
        function displayForm() {

        // Select the element
        if (!(document.getElementById('payonline').checked)) {
    
        var element1 = document.getElementById('eventform');
        element1.classList.remove('hidden');
          var element1 = document.getElementById('eventform');
        element1.classList.remove('hidden');
        }
        else {
        
            var element1 = document.getElementById('eventform');
            element1.classList.add('hidden');
            var element1 = document.getElementById('eventform');
            element1.classList.add('hidden');

            }
        }

        function displayMeals2() {
        // Select the element
        if (document.getElementById('ddattdin2').checked) {
    
        var element1 = document.getElementById('partMealChoice');
        element1.classList.remove('hidden');
        }
        else {
            console.log('notchecked');
            var element1 = document.getElementById('partMealChoice');
            element1.classList.add('hidden');

            }
      
        }

        function displayMeals2U(regid) {
       
        var attenddinner = 'dddin'+regid;
        var formcontainer =  "fcu"+regid;

        // Select the element
        if (document.getElementById(attenddinner).checked) {
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
            console.log('notchecked');
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');
            }
        }

        function displayMeals3U(regid) {
 
        var update =  "upd"+regid; 
        var formcontainer =  "fcu2"+regid;

        // Select the element
        if (document.getElementById(update).checked) {
        console.log(formcontainer);
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
            console.log('notchecked');
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');

            }
        }
        
         
    </script>