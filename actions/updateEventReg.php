<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$regs = $_SESSION['registrations'];
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
        $fnamID = "fnam".$reg['id'];
        $lnamID = "lnam".$reg['id'];
        $emailID = "email".$reg['id'];
        $useridID = "userid".$reg['id'];
        $messID = "mess".$reg['id'];
        $paidID = "paid".$reg['id'];
        $dddinID = "dddin".$reg['id'];
        $chID = "ch".$reg['id'];
        $sbID = "sb".$reg['id'];
    
        if (isset($_POST["$updID"])) {

            $eventReg->id = $reg['id'];
            $eventReg->firstname = $_POST["$fnamID"];
            $eventReg->lastname = $_POST["$lnamID"];
            $eventReg->eventid = $_POST['eventid'];
            $eventReg->email = $_POST["$emailID"];
            $eventReg->userid = $_POST["$useridID"];
            
            if (isset($_POST["$paidID"])) {

                $eventReg->paid = $_POST["$paidID"];
            } else {
                $eventReg->paid = $reg['paid'];
            }
            $eventReg->message = $_POST["$messID"];
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
             if (isset($_POST["$messID"])) {
                $eventReg->message = $_POST["$messID"];
             }
             else {
                $eventReg->message = $reg['message'];
             }
            $eventReg->ddattenddance = $reg['ddattenddance'];
            $eventReg->dateregistered = $reg['dateregistered'];


            $eventReg->update();
        }
    }
 
 
    $redirect = "Location: ".$_SESSION['adminurl']."#events";
    header($redirect);
    exit;
}
?>