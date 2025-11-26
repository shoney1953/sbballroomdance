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
$mChoices = new DinnerMealChoices($db);
$regs = $_SESSION['eventregistrations'];
$mealChoices = [];
$updID = '';
$fnamID = '';
$lnamID = '';
$emailID = '';
$useridID = '';
$messID = '';
$paidID = '';
$dddinID = '';

if (isset($_POST['submitUpdateMealReg'])) {
 
    foreach ($regs as $reg) {
      
        $updID = "upd".$reg['id'];
        $chID = "ch".$reg['id'];
        $sbID = "sb".$reg['id'];
        $updID = "upd".$reg['id'];
        $dddinID = "dddin".$reg['id'];
        $drID = "dr".$reg['id'];
    
        if (isset($_POST["$updID"])) {
         
          $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($reg['eventid']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
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
            $eventReg->id = $reg['id'];
       
            if (isset($_POST["$drID"])) {
                $eventReg->dietaryrestriction = $_POST["$drID"];
            } else {
                $eventReg->dietaryrestriction = " ";
            }
            if (isset($_POST["$dddinID"])) {
               $eventReg->ddattenddinner = 1;
            } else {
                $eventReg->ddattenddinner = 0;
            }
            foreach ($mealChoices as $meal_item) {
                $mcID = "mc".$meal_item['id'].$reg['id'];

                if (isset($_POST["$mcID"])) {
                  
                  $eventReg->mealchoice = $meal_item['id'];
                } 
             }

            $eventReg->updateMealEventReg();
        }
    }
 
 
    $redirect = "Location: ".$_SESSION['profileurl']."#events";
    header($redirect);
    exit;
   }
?>