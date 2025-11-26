<?php
session_start();
require_once '../config/Database.php';
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
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'EVENTADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
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
       } else {
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
}

$database = new Database();
$db = $database->connect();
$dinnermealchoices = new DinnerMealChoices($db);
$rowCount = 0;
$eventId = 0;
$mealChoices = [];
$mealChoices = $_SESSION['mealChoices'];


if (isset($_POST['updateMealChoices'])) {
    if (isset($_POST['eventid'])) {
       foreach($mealChoices as $mealchoice)  {
        $chkboxID = "mc".$mealchoice['id'];
        $mchoiceNM = "mcname".$mealchoice['id'];
        $mcdescNM = "mcdesc".$mealchoice['id'];
        $mPrice = "mprice".$mealchoice['id'];
        $gPrice = "gprice".$mealchoice['id'];

        if (isset($_POST[$chkboxID])) {
          
            $dinnermealchoices->id = (int)$mealchoice['id'];
            $dinnermealchoices->mealname = $_POST[$mchoiceNM];
            $dinnermealchoices->mealdescription = $_POST[$mdescNM];
            $dinnermealchoices->guestprice = $_POST[$gPrice];
            $dinnermealchoices->memberprice = $_POST[$mPrice];

            $dinnermealchoices->update();
        }
       }

    }
}



$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit; 


?>
