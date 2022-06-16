<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/Visitor.php';

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
$event = new Event($db);
$visitor = new Visitor($db);
var_dump($_POST);

if (isset($_POST['submitAddVisitorReg'])) {
     $eventReg->eventid = $_POST['eventid'];
    $eventReg->firstname = $_POST['firstname'];
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->email = $_POST['email'];
    $eventReg->paid = 0;
    $eventReg->userid = 0;
 
    $eventReg->create();
    $event->addCount($eventReg->eventid);
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
    $visitor->firstname = $_POST['firstname'];
    $visitor->lastname = $_POST['lastname'];
    $visitor->create();

}  
   


    $redirect = "Location: ".$_SESSION['adminurl']."#eventregistrations";
header($redirect);
exit;

?>