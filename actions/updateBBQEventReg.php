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
$regs = $_SESSION['eventregistrations'];

$updID = '';
$fnamID = '';
$lnamID = '';
$emailID = '';
$useridID = '';
$messID = '';
$paidID = '';
$dddinID = '';

if (isset($_POST['submitUpdateReg'])) {
 

    foreach ($regs as $reg) {
        $updID = "upd".$reg['id'];

        $dddinID = "dddin".$reg['id'];
        $chID = "ch".$reg['id'];
        $sbID = "sb".$reg['id'];
    
        if (isset($_POST["$updID"])) {

            $eventReg->id = $reg['id'];

    
            if (isset($_POST["$dddinID"])) {
               $eventReg->ddattenddinner = $_POST["$dddinID"];
            } else {
                $eventReg->ddattenddinner = $reg['ddattenddinner'];
            }
            if (isset($_POST["$chID"])) {
                $eventReg->cornhole = $_POST["$chID"];
             } else {
                 $eventReg->cornhole = $reg['cornhole'];
             }
             if (isset($_POST["$sbID"])) {
                $eventReg->softball = $_POST["$sbID"];
             } else {
                 $eventReg->softball = $reg['softball'];
             }

            $eventReg->updateBBQEventReg();
        }
    }
 
 
    $redirect = "Location: ".$_SESSION['profileurl']."#events";
    header($redirect);
    exit;
}
?>