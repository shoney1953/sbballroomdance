<?php
session_start();

include_once '../config/Database.php';
include_once '../models/Event.php';
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

   
$event->id = $_POST['id'];
   
$event->delete();
echo ' Event was deleted <br>';
$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>