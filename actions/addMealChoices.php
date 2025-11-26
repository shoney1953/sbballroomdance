<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
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
$dinnerdancechoice = new DinnerMealChoices($db);

  if (isset($_POST['addChoice1'])) {
    $dinnerdancechoice->mealchoice = $_POST['mealchoice1'];
    $dinnerdancechoice->memberprice = $_POST['memberPrice1'];
    $dinnerdancechoice->guestprice = $_POST['guestPrice1'];
    $dinnerdancechoice->eventid = $_POST['eventid'];
    $dinnerdancechoice->create();

  }
  if (isset($_POST['addChoice2'])) {
    $dinnerdancechoice->mealchoice = $_POST['mealchoice2'];
    $dinnerdancechoice->memberprice = $_POST['memberPrice2'];
    $dinnerdancechoice->guestprice = $_POST['guestPrice2'];
    $dinnerdancechoice->eventid = $_POST['eventid'];
    $dinnerdancechoice->create();

  }
  if (isset($_POST['addChoice3'])) {
    $dinnerdancechoice->mealchoice = $_POST['mealchoice3'];
    $dinnerdancechoice->memberprice = $_POST['memberPrice3'];
    $dinnerdancechoice->guestprice = $_POST['guestPrice3'];
    $dinnerdancechoice->eventid = $_POST['eventid'];
    $dinnerdancechoice->create();

  }
  if (isset($_POST['addChoice4'])) {
    $dinnerdancechoice->mealchoice = $_POST['mealchoice4'];
    $dinnerdancechoice->memberprice = $_POST['memberPrice4'];
    $dinnerdancechoice->guestprice = $_POST['guestPrice4'];
    $dinnerdancechoice->eventid = $_POST['eventid'];
    $dinnerdancechoice->create();

  }
   



    $redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit; 

?>