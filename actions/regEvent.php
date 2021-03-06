<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");

$events = $_SESSION['upcoming_events'];

$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'sbbdcschedule@gmail.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$replyTopic = "Event Registration";
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);
$user = new User($db);
$message = '';

$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following event(s):<br>";
$emailSubject = '';
$eventNum = 0;
$regUserid1 = 0;
$regUserid2 = 0;
$toCC2 = '';

$id_int = 0;

if (isset($_POST['submitEventReg'])) {
  
    $regFirstName1 = htmlentities($_POST['regFirstName1']);
    $regLastName1 = htmlentities($_POST['regLastName1']);
    $regEmail1 = htmlentities($_POST['regEmail1']);  
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail1)) {    
        $regUserid1 = $user->id;
   }
   if (isset($_POST['message'])) {
       $message = htmlentities($_POST['message']); 
   }
   if (isset($_POST['regEmail2'])) {
    $regFirstName2 = htmlentities($_POST['regFirstName2']);
    $regLastName2 = htmlentities($_POST['regLastName2']);
    $regEmail2 = htmlentities($_POST['regEmail2']);  
    $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail2)) {    
        $regUserid2 = $user->id;
      } else {
          $regUserid2 = 0;
      }
      
    }

    
    $emailSubject = "You have registered for SBDC event(s)";
    foreach ($events as $event) {
        $chkboxID = "ev".$event['id'];
       if (isset($_POST["$chkboxID"])) {
        $eventNum = (int)substr($chkboxID,2);
            if ($event['id'] == $eventNum) {
                $eventId = $event['id'];
                $emailBody .= "<br> <br> <strong>".$event['eventname'].
                "    room:    ".$event['eventroom'].
                "  on date:    ".date('M d Y',strtotime($event['eventdate']))."</strong><br>"; 
               
                if ($event['eventform']) {
                    $actLink= "<a href='".$event['eventform']."'>
       Click to view event Form</a><br>";
                   $emailBody .= '<strong>There is a form to submit registration details and payment.<br>';
                   $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                    on the website to open the form. Or</strong><br>$actLink";
                   $toCC2 = 'treasurer@sbballroomdance.com';
                }
                if ($event['eventcost'] > 0) {
                    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $coststr =  "<strong> Member Event Cost is approximately: "
                          .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>
                          Check the form for specific costs.</strong><br>";
                    $emailBody .= $coststr;
                    $toCC2 = 'treasurer@sbballroomdance.com';
                 }
                if ($message) {
                    $emailBody .= '<br> MESSAGE from Registrant: <br>';
                    $emailBody .= $message;
                    $emailBody .= '<br> <br>';
                }
          
                // do the insert(s)
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->userid = $regUserid1;
                $eventReg->message = $message;
                $eventReg->paid = 0;
                $eventReg->create();
                $eventInst->addCount($eventReg->eventid);
                if (isset($regFirstName2)) {
                       // do the insert(s)
                    $eventReg->firstname = $regFirstName2;
                    $eventReg->lastname = $regLastName2;
                    $eventReg->eventid = $eventId;
                    $eventReg->email = $regEmail2;
                    $eventReg->userid = $regUserid2;
                    $eventReg->message = $message;
                    $eventReg->paid = 0;
                    $eventReg->create();
                    $eventInst->addCount($eventReg->eventid);
                }
             

            } // end if eventid            
       } //end isset
      } // end foreach
        
    if (filter_var($regEmail1, FILTER_VALIDATE_EMAIL)) {
      
        $regName1 = $regFirstName1.' '.$regLastName1;
   
        sendEmail(
            $regEmail1, 
            $regName1, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $emailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $toCC2
        );
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
    }

    if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {

        $regName2 = $regFirstName2.' '.$regLastName2;
   
        sendEmail(
            $regEmail2, 
            $regName2, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $emailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $toCC2
        );
             
    }

   $redirect = "Location: ".$_SESSION['homeurl'];
   header($redirect); 
   exit;
}  else { 
     $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect); 
     exit;
} // end submit
