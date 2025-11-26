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

            $eventReg->updateBBQEventReg();
        }
    }
 
 
    $redirect = "Location: ".$_SESSION['profileurl']."#events";
    header($redirect);
    exit;
}
?>