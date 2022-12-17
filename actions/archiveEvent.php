<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/EventRegistrationArch.php';
require_once '../models/Event.php';
require_once '../models/EventArch.php';

$eventsArch = $_SESSION['eventsArch'];


if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
$eventRegArch = new EventRegistrationArch($db);

$event = new Event($db);
$eventArch = new EventArch($db);


if (isset($_POST['submitArchive'])) {
  foreach($eventsArch as $ea) {
 
      $eventArch->previd = $ea['id'];
      $eventArch->eventtype = $ea['eventtype'];
      $eventArch->eventname = $ea['eventname'];
      $eventArch->eventroom = $ea['eventroom'];
      $eventArch->eventdate = $ea['eventdate'];
      $eventArch->eventdj = $ea['eventdj'];
      $eventArch->eventdesc = $ea['eventdesc'];
      $eventArch->eventnumregistered = $ea['eventnumregistered'];
      $eventArch->eventcost = $ea['eventcost'];
      $eventArch->eventform = $ea['eventform'];
      $eventArch->create();
      $eventRegArch->eventid = $db->lastInsertId();
      $eventReg->eventid = $ea['id'];
      $result = $eventReg->read_ByEventid($ea['id']);
      $rowCount = $result->rowCount();

      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'eventid' => $eventid,
                'eventname' => $eventname,
                'eventdate' => $eventdate,
                'message' => $message,
                'userid' => $userid,
                'email' => $email,
                'paid' => $paid,
                'ddattenddinner' => $ddattenddinner,
                'ddattenddance' => $ddattenddance,
                'mealchoice' => $mealchoice,
                'dietaryrestriction' => $dietaryrestriction,
                'dateregistered' => $dateregistered
            );
          
            $eventRegArch->preveventid = $reg_item['eventid'];
            $eventRegArch->firstname = $reg_item['firstname'];
            $eventRegArch->lastname = $reg_item['lastname'];
            $eventRegArch->email = $reg_item['email'];
            $eventRegArch->userid = $reg_item['userid'];
            $eventRegArch->dateregistered = $reg_item['dateregistered'];
            $eventRegArch->eventname = $reg_item['eventname'];
            $eventRegArch->eventdate = $reg_item['eventdate'];
            $eventRegArch->paid = $reg_item['paid'];
            $eventRegArch->message = $reg_item['message'];
            $eventRegArch->ddattenddance = $reg_item['ddattenddance'];
            $eventRegArch->ddattenddinner = $reg_item['ddattenddinner'];
            $eventRegArch->mealchoice = $reg_item['mealchoice'];
            $eventRegArch->dietaryrestriction = $reg_item['dietaryrestriction'];
            $eventRegArch->create();
      
        }
  
        $eventReg->deleteEventid($ea['id']);
       
       
  }
  $event->id = $ea['id'];
  $event->delete();
}
}

   
   
    
$redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;

?>