<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username'])) {
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
    $evidID = "evid".$event['id'];
        if (isset($_POST["$evSelectChk"])) {

            $eventRec->id = $event['id'];
            $eventRec->eventname = $_POST["$evnamID"];
            $eventRec->eventtype = $_POST["$evtypeID"];
            $eventRec->eventdesc = $_POST["$evdescID"];
            $eventRec->eventdj = $_POST["$evdjID"];
            $eventRec->eventform = $_POST["$evformID"];
            $eventRec->eventroom = $_POST["$evroomID"];
            $eventRec->eventdate = $_POST["$evdateID"];
            $eventRec->eventcost = $_POST["$evcostID"];
            $eventRec->eventregend = $_POST["$evrendID"];
            $eventRec->eventnumregistered = $_POST["$evnumregID"];
            $eventRec->update();
        }
    }
    
    
    $redirect = "Location: ".$_SESSION['adminurl']."#events";
    header($redirect);
    exit;
}
?>