<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
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

if (isset($_POST['submitUpdateReg'])) {

    $eventReg->id = $_POST['id'];
    $eventReg->firstname = $_POST['firstname'];
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->eventid = $_POST['eventid'];
    $eventReg->email = $_POST['email'];
    $eventReg->userid = $_POST['userid'];
    $eventReg->paid = $_POST['paid'];

    $eventReg->update();
    echo ' Registration was updated  <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>