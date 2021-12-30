<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';

$events = $_SESSION['upcoming_events'];

$fromCC = 'sbbdcschedule@gmail.com';
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
                $emailBody .= "<br> ".$event['eventname'].
                "    room:    ".$event['eventroom'].
                "  on date:    ".$event['eventdate']."<br>"; 
                if ($event['eventcost'] > 0) {
                    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $coststr =  "<br> Member Event Cost is: "
                          .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>";
                    $emailBody .= $coststr;
                  }
                 
                if ($event['eventform']) {
                   $emailBody .= '<br>There is a form to submit registration details and payment.<br>';
                   $emailBody .= '<br>Please VIEW the form on the website under events.<br>';

                  }
                if ($message) {
                    $emailBody .= '<br> MESSAGE: <br>';
                    $emailBody .= $message;
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
            $mailAttachment
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
            $mailAttachment
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
