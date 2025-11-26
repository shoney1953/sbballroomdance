<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
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
$eventRec = new Event($db);
$allEvents = $_SESSION['allEvents'];

if (isset($_POST['submitUpdate'])) {

    foreach ($allEvents as $event) {
        $evSelectChk = "evselect".$event['id'];
    $evnamID = "evnam".$event['id'];
    $evtypeID = "evtype".$event['id'];
    $evdescID = "evdesc".$event['id'];
    $evdjID = "evdj".$event['id'];
    $evroomID = "evroom".$event['id'];
    $evdateID = "evdate".$event['id'];
    $evcostID = "evcost".$event['id'];
    $evnumregID = "evnumreg".$event['id'];
    $evformID = "evform".$event['id'];
    $evrendID = "evrend".$event['id'];
    $evropenID = "evropen".$event['id'];
    $evidID = "evid".$event['id'];
    $evoeID = "evoe".$event['id'];
    $evprodID = "evprod".$event['id'];
    $evgcostID = "evgcost".$event['id'];
    $evgpriceID = "evgprice".$event['id'];
    $evmpriceID = "evmprice".$event['id'];
    $evdwopct = "evdwopct".$event['id'];
        if (isset($_POST["$evSelectChk"])) {

            $eventRec->id = $event['id'];
            $eventRec->eventname = $_POST["$evnamID"];
            $eventRec->eventtype = $_POST["$evtypeID"];
            $eventRec->eventdesc = $_POST["$evdescID"];
            $eventRec->eventdj = $_POST["$evdjID"];
            $eventRec->orgemail = $_POST["$evoeID"];
            $eventRec->eventform = $_POST["$evformID"];
            $eventRec->eventroom = $_POST["$evroomID"];
            $eventRec->eventdate = $_POST["$evdateID"];
            $eventRec->eventcost = $_POST["$evcostID"];
            $eventRec->eventregopen = $_POST["$evropenID"];
            $eventRec->eventregend = $_POST["$evrendID"];
            $eventRec->eventnumregistered = $_POST["$evnumregID"];
            $eventRec->eventguestcost = $_POST["$evgcostID"];
            $eventRec->eventguestpriceid = $_POST["$evgpriceID"];
            $eventRec->eventmempriceid = $_POST["$evmpriceID"];
            $eventRec->eventproductid = $_POST["$evprodID"];
            $eventRec->eventdwopcount = $_POST["$evdwopct"];

            $eventRec->update();
        }
    }
    
    
    $redirect = "Location: ".$_SESSION['adminurl']."#events";
    header($redirect);
    exit;
}
?>