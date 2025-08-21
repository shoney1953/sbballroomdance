<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
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
$regs = $_SESSION['eventregistrations'];

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
 
            if (isset($_POST["$dddinID"])) {
               $eventReg->ddattenddinner = 1;
            } else {
                $eventReg->ddattenddinner = 0;
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