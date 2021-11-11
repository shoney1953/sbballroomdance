<?php
session_start();

include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$event = new Event($db);

   
    $eventReg->eventid = $_POST['eventid'];
    $eventReg->firstname = $_POST['firstname'];
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->email = $_POST['email'];
    $eventReg->userid = 0;
 
    $eventReg->create();
    $event->addCount($eventReg->eventid);
    echo ' Registration was created <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>