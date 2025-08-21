<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} 

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnerEventReg = new EventRegistration($db);
$mChoices = new DinnerMealChoices($db);

$mealChoices = [];
$mealChk = '';
$mealChk2 = '';

if (isset($_POST['submitModifyRegs'])) {
     
            if (isset($_POST['regID1'])) {
              $eventReg->id = $_POST['regID1'];
              $eventReg->read_single();
            }
             if (isset($_POST['regID2'])) {
              $partnerEventReg->id = $_POST['regID2'];
              $partnerEventReg->read_single();
            }
         
              $result = $mChoices->read_ByEventId($eventReg->eventid);

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
            // partner info below
       
            if (isset($_POST['regID2'])) {
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
              }
            
        }

 
 
    $redirect = "Location: ".$_SESSION['returnurl'];
    header($redirect);
    exit;
   
?>