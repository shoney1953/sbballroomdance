<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
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
$allEvents = $_SESSION['allEvents'];
$database = new Database();
$db = $database->connect();
$eventRec = new Event($db);
$eventReg = new EventRegistration($db);

if (isset($_POST['submitDelete'])) {
    foreach ($allEvents as $event) {
        $evSelectChk = "evselect".$event['id'];
        if (isset($_POST["$evSelectChk"])) {
            $eventRec->id = $event['id'];
            $eventRec->delete();
            $eventReg->deleteEventid($event['id']);
        }
    }
}
   
$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit;

?>