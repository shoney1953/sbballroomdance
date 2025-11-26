<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
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