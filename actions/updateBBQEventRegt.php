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
            $nhdID = "nhd".$_POST['regID1'];
            $nhdbID = "nhdb".$_POST['regID1'];
            $nhbID = "nhb".$_POST['regID1'];
            $nhbbID = "nhbb".$_POST['regID1'];
            $vegID = "veg".$_POST['regID1'];
            $eventReg->id = $_POST['regID1'];
            $eventReg->read_single();
            $eventReg->modifiedby =$_SESSION['username'];
            $eventReg->mealchoice = 0;
            if (isset($_POST["$dddinID"])) {
               
              $eventReg->ddattenddinner = 1;
                if (isset($_POST["$vegID"])) {
                    $eventReg->vegetarian = 1;
                } else {
                    $eventReg->vegetarian = 0;
                }
                if (isset($_POST["$nhbID"])) {
                    $eventReg->numhamburgers = $_POST["$nhbID"];
                } 
                if (isset($_POST["$nhbbID"])) {
                    $eventReg->numhbbuns = $_POST["$nhbbID"];
                } 
                if (isset($_POST["$nhdID"])) {
                    $eventReg->numhotdogs = $_POST["$nhdID"];
                }
                if (isset($_POST["$nhdbID"])) {
                    $eventReg->numhdbuns = $_POST["$nhdbID"];
                } 
              

            } else {
                $eventReg->ddattenddinner = 0;
                $eventReg->numhamburgers = 0;
                $eventReg->numhbbuns = 0;
                $eventReg->numhotdogs = 0;
                $eventReg->numhdbuns = 0;
                $eventReg->vegetarian = 0;
              
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
            if (isset($_POST['dietaryr1'])) {
                $eventReg->dietaryrestriction = $_POST['dietaryr1'];
            }
      
                     $eventReg->updateBBQEventReg();
           }

        
        // partner
          if (isset($_POST['regID2']))  {
            $dddinID2 = "dddin2".$_POST['regID2'];
            $chID2 = "ch2".$_POST['regID2'];
            $sbID2 = "sb2".$_POST['regID2'];
            $nhdID2 = "nhd2".$_POST['regID2'];
            $nhdbID2 = "nhdb2".$_POST['regID2'];
            $nhbID2 = "nhb2".$_POST['regID2'];
            $nhbbID2 = "nhbb2".$_POST['regID2'];
            $vegID2 = "veg2".$_POST['regID2'];
            $partnerEventReg->id = $_POST['regID2'];
            $partnerEventReg->read_single();

             $eventReg->mealchoice = 0;
            if (isset($_POST["$dddinID2"])) {
               $partnerEventReg->ddattenddinner = 1;
               if (isset($_POST["$vegID2"])) {
                    $partnerEventReg->vegetarian = 1;
                } 
                if (isset($_POST["$nhbID2"])) {
                    $partnerEventReg->numhamburgers = $_POST["$nhbID2"];
                } 
                if (isset($_POST["$nhbbID2"])) {
                    $partnerEventReg->numhbbuns = $_POST["$nhbbID2"];
                }
                 if (isset($_POST["$nhdID2"])) {
                    $partnerEventReg->numhotdogs = $_POST["$nhdID2"];
                } 
                if (isset($_POST["$nhdbID2"])) {
                    $partnerEventReg->numhdbuns = $_POST["$nhdbID2"];
                } 
           

            } else {
                $partnerEventReg->ddattenddinner = 0;
                $partnerEventReg->numhamburgers = 0;
                $partnerEventReg->numhbbuns = 0;
                $partnerEventReg->numhotdogs = 0;
                $partnerEventReg->numhdbuns = 0;
                $partnerEventReg->vegetarian = 0;
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
            if (isset($_POST['dietaryr2'])) {
                $partnerEventReg->dietaryrestriction = $_POST['dietaryr2'];
            }
  
            $partnerEventReg->updateBBQEventReg();
        } // partner set

    
 
 
    $redirect = "Location: ".$_SESSION['returnurl'];
    header($redirect);
    exit;
} // submit set
?>