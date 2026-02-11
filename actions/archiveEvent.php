<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/EventRegistrationArch.php';
require_once '../models/Event.php';
require_once '../models/EventArch.php';

$allEvents = $_SESSION['allEvents'];

if (!isset($_SESSION['username']))
{
   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
         if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
        }
       } else {
   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
       }
}

$database = new Database();
$db = $database->connect();

$eventReg = new EventRegistration($db);
$eventRegArch = new EventRegistrationArch($db);

$event = new Event($db);
$eventArch = new EventArch($db);


if (isset($_POST['submitArchive'])) {
    foreach ($allEvents as $ea) {
    $evSelectChk = "evselect".$ea['id'];
    if (isset($_POST["$evSelectChk"])) {
   
      $eventArch->previd = $ea['id'];
      $eventArch->eventtype = $ea['eventtype'];
      $eventArch->eventname = $ea['eventname'];
      $eventArch->eventroom = $ea['eventroom'];
      $eventArch->eventdate = $ea['eventdate'];
      $eventArch->eventregopen = $ea['eventregopen'];
      $eventArch->eventregend = $ea['eventregend'];
      $eventArch->eventdj = $ea['eventdj'];
      $eventArch->orgemail = $ea['orgemail'];
      $eventArch->eventdesc = $ea['eventdesc'];
      $eventArch->eventnumregistered = $ea['eventnumregistered'];
      $eventArch->eventcost = $ea['eventcost'];
      $eventArch->eventform = $ea['eventform'];
      $eventArch->eventproductid = $ea['eventproductid'];
      $eventArch->eventmempriceid = $ea['eventmempriceid'];
      $eventArch->eventguestpriceid = $ea['eventguestpriceid'];
      $eventArch->eventguestcost = $ea['eventguestcost'];
      $eventArch->eventdwopcount = $ea['eventdwopcount'];
      $eventArch->eventdinnerregend = $ea['eventdinnerregend'];
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
                'dwop' => $dwop,
                'guest' => $guest,
                'ddattenddinner' => $ddattenddinner,
                'cornhole' => $cornhole,
                'softball' => $softball,
                'ddattenddance' => $ddattenddance,
                'mealchoice' => $mealchoice,
                'dietaryrestriction' => $dietaryrestriction,
                'paidonline' => $paidonline,
                'registeredby' => $registeredby,
                'modifiedby' => $modifiedby,
                'modifieddate' => $modifieddate,
                'dateregistered' => $dateregistered,
                'numhotdogs' => $numhotdogs,
                'numhdbuns' => $numhdbuns,
                'numhamburgers' => $numhamburgers,
                'numhbbuns' => $numhbbuns,
                'vegetarian' => $vegetarian
            );

            $eventRegArch->preveventid = $reg_item['eventid'];
            $eventRegArch->firstname = $reg_item['firstname'];
            $eventRegArch->lastname = $reg_item['lastname'];
            $eventRegArch->email = $reg_item['email'];
            $eventRegArch->userid = $reg_item['userid'];
            $eventRegArch->dateregistered = $reg_item['dateregistered'];
            $eventRegArch->registeredby = $reg_item['registeredby'];
            $eventRegArch->modifiedby = $reg_item['modifiedby'];
            $eventRegArch->modifieddate = $reg_item['modifieddate'];
            $eventRegArch->eventname = $reg_item['eventname'];
            $eventRegArch->eventdate = $reg_item['eventdate'];
            $eventRegArch->paid = $reg_item['paid'];
            $eventRegArch->cornhole = $reg_item['cornhole'];
            $eventRegArch->softball = $reg_item['softball'];
            $eventRegArch->message = $reg_item['message'];
            $eventRegArch->ddattenddance = $reg_item['ddattenddance'];
            $eventRegArch->ddattenddinner = $reg_item['ddattenddinner'];
            $eventRegArch->dwop = $reg_item['dwop'];
            if ($reg_item['mealchoice'] === NULL) {
                 $eventRegArch->mealchoice = 0;
            } else {
                $eventRegArch->mealchoice = $reg_item['mealchoice'];
            }
            $eventRegArch->numhotdogs = $reg_item['numhotdogs'];
            $eventRegArch->numhdbuns = $reg_item['numhdbuns'];
            $eventRegArch->numhamburgers = $reg_item['numhamburgers'];
            $eventRegArch->numhbbuns = $reg_item['numhbbuns'];
            $eventRegArch->vegetarian = $reg_item['vegetarian'];
            $eventRegArch->paidonline = $reg_item['paidonline'];
            $eventRegArch->dietaryrestriction = $reg_item['dietaryrestriction'];
            $eventRegArch->guest = $reg_item['guest'];
            $eventRegArch->create();
      
        }
  
        $eventReg->deleteEventid($ea['id']);
     
       
  }
  $event->id = $ea['id'];
  $event->delete();
}
    }
}

   
$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit;

?>