<?php
// include("includes/mailheader.php");
include_once '../includes/sendEmail.php';

session_start();
$events = $_SESSION['upcoming_events'];
include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/Event.php';
$fromCC = 'sheila_honey_5@hotmail.com';
$replyEmail = 'sheilahoney53@gmail.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$replyTopic = "Event Registration";
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);


$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following event(s):<br>";
$emailSubject = '';
$eventNum = 0;

$id_int = 0;

if (isset($_POST['submitEventReg'])) {
    $regFirstName = htmlentities($_POST['regFirstName']);
    $regLastName = htmlentities($_POST['regLastName']);
    $regEmail = htmlentities($_POST['regEmail']);  
    $regEmail = filter_var($regEmail, FILTER_SANITIZE_EMAIL); 
    
    $emailSubject = "You have registered for SBDC event(s)";
    foreach($events as $event) {
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
                  // $formLink = "<br><a href='".$event['eventform']."'>Event Form</a><br>";
                  // $emailBody .= $formLink;

                }
                var_dump($emailBody);
                // do the insert(s)
                $eventReg->firstname = $regFirstName;
                $eventReg->lastname = $regLastName;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail;
                if(isset($_SESSION['userid'])) {
                    $eventReg->userid = $_SESSION['userid'];
                } else {
                    $eventReg->userid = 0;
                }
               
                $eventReg->create();
               
                $eventInst->addCount($eventReg->eventid);
            } // end if eventid            
       } //end isset
      } // end foreach
        
    if (filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
      
       $regName = $regFirstName.' '.$regLastName;
   
       sendEmail(
           $regEmail, 
           $regName, 
           $fromCC,
           $fromEmailName,
           $emailBody,
           $emailSubject,
           $replyEmail,
           $replyTopic,
           $mailAttachment
       );
    } else {
        echo 'Registrant Email is empty or Invalid. Please enter valid email.';
    }

   $redirect = "Location: ".$_SESSION['homeurl'];
   header($redirect); 
 exit;
}  else { 
   $redirect = "Location: ".$_SESSION['homeurl'];
  header($redirect); 

}// end submit
