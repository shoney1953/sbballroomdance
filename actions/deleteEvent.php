<?php
session_start();

include_once '../config/Database.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$event = new Event($db);

   
$event->id = $_POST['id'];
   
$event->delete();
echo ' Event was deleted <br>';
$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>