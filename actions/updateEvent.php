<?php

session_start();
include_once '../config/Database.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$event = new Event($db);

if (isset($_POST['submitUpdate'])) {
    $event->id = $_POST['id'];
    $event->eventname = $_POST['eventname'];
    $event->eventtype = $_POST['eventtype'];
    $event->eventdesc = $_POST['eventdesc'];
    $event->eventdj = $_POST['eventdj'];
    $event->eventform = $_POST['eventform'];
    $event->eventroom = $_POST['eventroom'];
    $event->eventdate = $_POST['eventdate'];
    $event->eventcost = $_POST['eventcost'];
    $event->eventnumregistered = $_POST['eventnumregistered'];
    $event->update();
    echo ' Event was updated  <br>';
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
    exit;
}
?>