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
$regs = $_SESSION['eventregistrations'];
$mChoices = new DinnerMealChoices($db);
$mealChoices = [];
$updID = '';
$fnamID = '';
$lnamID = '';
$emailID = '';
$useridID = '';
$messID = '';
$paidID = '';
$dddinID = '';

if (isset($_POST['submitUpdateBBQReg'])) {

      
         
           if (isset($_POST['regID1'])) {
      
            $dddinID = "dddin".$_POST['regID1'];
            $chID = "ch".$_POST['regID1'];
            $sbID = "sb".$_POST['regID1'];
            $eventReg->id = $_POST['regID1'];
            $eventReg->read_single();
            $eventReg->modifiedby =$_SESSION['username'];
            if (isset($_POST["$dddinID"])) {
                 $mealChoices = [];
              $result = $mChoices->read_ByEventId($eventReg->eventid);
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
                } //rowcount
               $eventReg->ddattenddinner = 1;
               $eventReg->mealchoice = 0;
                 foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
       
                 if (isset($_POST["$mealChk"])) {
                    
                    $eventReg->mealchoice = $choice['id'];
                    $eventReg->mealname = $choice['mealname'];
       
                 }  
                 } // for each mealchoice


            } else {
                $eventReg->ddattenddinner = 0;
                 $eventReg->mealchoice = 0;
            }
            if (isset($_POST["$chID"])) {
                $eventReg->cornhole = 1;
             } else {
                 $eventReg->cornhole = 0;
             }
             if (isset($_POST["$sbID"])) {
                $eventReg->softball = 1;
             } else {
                 $eventReg->softball = 0;
             }
         
            $eventReg->updateBBQEventReg();
           }

        
        // partner
          if (isset($_POST['regID2']))  {
            $dddinID2 = "dddin2".$_POST['regID2'];
            $chID2 = "ch2".$_POST['regID2'];
            $sbID2 = "sb2".$_POST['regID2'];
 
            $partnerEventReg->id = $_POST['regID2'];
            $partnerEventReg->read_single();
     
            if (isset($_POST["$dddinID2"])) {
               $partnerEventReg->ddattenddinner = 1;
                   $mealChoices = [];
              $result = $mChoices->read_ByEventId($partnerEventReg->eventid);
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
               $eventReg->mealchoice = 0;
                 foreach ($mealChoices as $choice){
                  $meal2Chk = 'meal2'.$choice['id'];
                 if (isset($meal2Chk)) {
                    $partnerEventReg->mealchoice = $choice['id'];
                    $partnerEventReg->mealname = $choice['mealname'];
                 } else {
                    $partnerEventReg->mealchoice = 0;
                    $partnerEventReg->mealname = ' ';
                 }
                 } // for each mealchoice
            } else {
                $partnerEventReg->ddattenddinner = 0;
            }
            if (isset($_POST["$chID2"])) {
                $partnerEventReg->cornhole = 1;
             } else {
                 $partnerEventReg->cornhole = 0;
             }
             if (isset($_POST["$sbID2"])) {
                $partnerEventReg->softball = 1;
             } else {
                 $partnerEventReg->softball = 0;
             }

            $partnerEventReg->updateBBQEventReg();
        } // partner set

    
 
 
    $redirect = "Location: ".$_SESSION['returnurl'];
    header($redirect);
    exit;
} // submit set
?>