<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Options.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username'])) {
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
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
$option = new Options($db);
$allOptions = $_SESSION['allOptions'];

if (isset($_POST['submitUpdate'])) {
    
    foreach ($allOptions as $opt) {
    $opSelectChk = "opselect".$opt['id'];
    $oprenmoID = "oprenmo".$opt['id'];
    $opdismoID = "opdismo".$opt['id'];
    $opidID = "evid".$opt['id'];

        if (isset($_POST["$opSelectChk"])) {
    
            $option->id = $opt['id'];
            $option->renewalmonth = $_POST["$oprenmoID"];
            $option->discountmonth = $_POST["$opdismoID"];
           
            $option->update();
        }
    }
    
    
    $redirect = "Location: ".$_SESSION['optionurl'];
    header($redirect);
    exit;
}
?>