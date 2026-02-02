<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
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

    foreach ($regs as $reg) {
        $updID = "upd".$reg['id'];

        $dddinID = "dddin".$reg['id'];
        $chID = "ch".$reg['id'];
        $sbID = "sb".$reg['id'];
                        $nhdID = "nhd".$reg['id'];
                $nhdbID = "nhdb".$reg['id'];
                $nhbID = "nhb".$reg['id'];
                $nhbbID = "nhbb".$reg['id'];
                $vegID = "veg".$reg['id'];
    
        if (isset($_POST["$updID"])) {

            $eventReg->id = $reg['id'];

    
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
               if (isset($_POST["$vegID"])) {
                $eventReg->vegetarian = 1;
             } else {
                 $eventReg->vegetarian = 0;
             }
               if (isset($_POST["$nhbID"])) {
                $eventReg->numhamburgers = $_POST["$nhbID"];
             } else {
                 $eventReg->numhamburgers = 0;
             }
               if (isset($_POST["$nhbbID"])) {
                $eventReg->numhbbuns = $_POST["$nhbbID"];
             } else {
                 $eventReg->numhbbuns = 0;
             }
                   if (isset($_POST["$nhdID"])) {
                $eventReg->numhotdogs = $_POST["$nhdID"];
             } else {
                 $eventReg->numhotdogs = 0;
             }
               if (isset($_POST["$nhdbID"])) {
                $eventReg->numhdbuns = $_POST["$nhdbID"];
             } else {
                 $eventReg->numhdbuns = 0;
             }



            $eventReg->updateBBQEventReg();
        }
    }
 
 
    // $redirect = "Location: ".$_SESSION['profileurl']."#events";
    // header($redirect);
    // exit;
}
?>