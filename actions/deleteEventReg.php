<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';


$regs = $_SESSION['eventregistrations'];


$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$event = new Event($db);

   
    foreach ($regs as $reg) {
       $delId = 'del'.$reg['id'];

       if (isset($_POST["$delId"])) {
           $eventReg->id = $reg['id'];
           $eventid = $reg['eventid'];
  
           $eventReg->delete();
           $event->decrementCount($eventid);
       }
    }

        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>