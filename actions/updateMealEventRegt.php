<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
       if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
} 

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnerEventReg = new EventRegistration($db);
$guestEventReg = new EventRegistration($db);
$mChoices = new DinnerMealChoices($db);

$mealChoices = [];
$mealChk = '';
$mealChk2 = '';

if (isset($_POST['submitModifyRegs'])) {
   $result = $mChoices->read_ByEventId($_POST['eventid']);

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
                        array_push($mealChoices, $meal_item);
              } // while
            } // rowcount

          if (isset($_POST['mem1update'])) {
            if (isset($_POST['regID1'])) {
              $eventReg->id = $_POST['regID1'];
              $eventReg->read_single();
            }
             if (isset($_POST['ddattdin1'])) {
               $eventReg->ddattenddinner = 1;
            } else {
              $eventReg->ddattenddinner = 0;
              $eventReg->mealchoice = 0;
            }
            if ($eventReg->mealchoice !== 0) {
            foreach ($mealChoices as $meal_item) {
                  $mealChk = 'meal'.$meal_item['id'];
                if (isset($_POST["$mealChk"])) {
                  $eventReg->mealchoice = $meal_item['id'];
                } 
             } // foreach end
            } // mealchoice != 0
            
             $eventReg->updateMealEventReg();      
          } // end mem1 update

          if (isset($_POST['mem2update'])) {
                    // partner info below
             if (isset($_POST['regID2'])) {
              $partnerEventReg->id = $_POST['regID2'];
              $partnerEventReg->read_single();
            }

            if (isset($_POST['ddattdin2'])) {
               $partnerEventReg->ddattenddinner = 1;
            } else {
                $partnerEventReg->ddattenddinner = 0;
                $partnerEventReg->mealchoice = 0;
            }
            if ($partnerEventReg->mealchoice !== 0) {
            foreach ($mealChoices as $meal_item) {
                  $mealChk2 = 'meal2'.$meal_item['id'];
                if (isset($_POST["$mealChk2"])) { 
                  $partnerEventReg->mealchoice = $meal_item['id'];
                } 
             } // foreach end
            } // mealchoice != 0
             
                 $partnerEventReg->updateMealEventReg();
              
          } // end partner update

              if ((isset($_SESSION['guests'])) && (count($_SESSION['guests']) > 0)) {
                $guests = $_SESSION['guests'];
                foreach ($guests as $guest) {
          
                  $regGuestID = "regGuestId".$guest['id'];
                  $mealChoiceGuest = "mcg".$guest['id'];
                  $guestAttDinner = "gad".$guest['id'];
                  $guestUpd = 'gup'.$guest['id'];
                  if (isset($_POST["$guestUpd"])) {
                    $guestEventReg->id = $guest['id'];
                    $guestEventReg->read_single();

                    if (isset($_POST["$guestAttDinner"])) {
                      $guestEventReg->ddattenddinner = 1;
                    } else {
                      $guestEventReg->ddattenddinner = 0;
                      $guestEventReg->mealchoice = 0;
                    }

                if ($guestEventReg->mealchoice !== 0) {
         
                 foreach ($mealChoices as $meal_item) {
                  
                  $guestMeal = 'guestmeal'.$guest['id'].$meal_item['id'];
                
                  if (isset($_POST["$guestMeal"])) { 
          
                     $guestEventReg->mealchoice = $meal_item['id'];
                     } // meal set

            
                    } // foreach  mealchoice end
                  } // mealchoice != 0

                     $guestEventReg->updateMealEventReg();
                  } // end guest update set
                 

                 } // for each guest
              } // guests found
} // submit
            
        
$redirect = "Location: ".$_SESSION['returnurl'];
header($redirect);
exit; 
 
 
 
   
?>