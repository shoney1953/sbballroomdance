<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
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

   
    $eventReg->eventid = $_POST['eventid'];
    $eventReg->firstname = $_POST['firstname'];
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->email = $_POST['email'];
    $eventReg->paid = $_POST['paid'];
    $eventReg->message = $_POST['message'];
    $eventReg->userid = 0;
 
    $eventReg->create();
    $event->addCount($eventReg->eventid);
    echo ' Registration was created <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>