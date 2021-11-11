<?php
session_start();

include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$event = new Event($db);

   
    $eventReg->id = $_POST['id'];
    $eventid = $_POST['eventid'];
   
    $eventReg->delete();
    $event->decrementCount($eventid);
    echo ' Registration was deleted <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>