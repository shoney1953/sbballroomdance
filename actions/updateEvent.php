<?php
session_start();
require_once '../config/Database.php';
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
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>