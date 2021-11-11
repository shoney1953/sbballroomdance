<?php
session_start();
include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);

if (isset($_POST['submitUpdateReg'])) {

    $eventReg->id = $_POST['id'];
    $eventReg->firstname = $_POST['firstname'];
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->eventid = $_POST['eventid'];
    $eventReg->email = $_POST['email'];
    $eventReg->userid = $_POST['userid'];

    $eventReg->update();
    echo ' Registration was updated  <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>