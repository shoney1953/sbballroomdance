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

   
    $event->eventname = $_POST['eventname'];
    $event->eventtype = $_POST['eventtype'];
    $event->eventdesc = $_POST['eventdesc'];
    $event->eventdj = $_POST['eventdj'];
    $event->eventform = '';
    $event->eventroom = $_POST['eventroom'];
    $event->eventdate = $_POST['eventdate'];
    $event->eventregend = $_POST['eventregend'];
    $event->eventcost = $_POST['eventcost'];
    $event->orgemail = $_POST['orgemail'];
    $event->eventnumregistered = 0;
    $event->create();


    $redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit;

?>