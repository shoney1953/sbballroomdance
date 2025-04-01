<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");

$events = $_SESSION['upcoming_events'];

$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$replyTopic = "SBDC Event Registration";
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);
$user = new User($db);
$message = '';
$result = 0;
$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following SBDC event(s):<br>";
$emailSubject = '';
$eventNum = 0;
$regUserid1 = 0;
$regUserid2 = 0;
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';

$id_int = 0;
$num_registered = 0;
$currentDate = new DateTime();


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
        $chkboxID2 = "dd".$event['id'];
        $chkboxID3 = "ch".$event['id'];
        $chkboxID4 = "sb".$event['id'];
        $messID = "mess".$event['id'];
        $message = '';
        if (isset($_POST["$messID"])) {            
            $message = $_POST["$messID"];
        }
       if (isset($_POST["$chkboxID"])) {
      
        $eventNum = (int)substr($chkboxID,2);

            if ($event['id'] == $eventNum) {
                $num_registered++;
                $eventId = $event['id'];
                $emailBody .= "<br><strong> Event: ".$event['eventname'].
                "<br>Type:    ".$event['eventtype'].
                "<br>DJ  :    ".$event['eventdj'].
                "<br>Room:    ".$event['eventroom'].
                "<br>Date:    ".date('M d Y',strtotime($event['eventdate']))."</strong><br>"; 
                if ($event['orgemail'] != null) {
                    $toCC2 = $event['orgemail'];
                }
                else {        
                        $toCC2 = '';                    
                }
                if ($event['eventtype'] === 'BBQ Picnic') {
                    if (isset($_POST["$chkboxID2"])) {
                        $emailBody .= "You have chosen to attend dinner.<br>";
                    }
                    if (isset($_POST["$chkboxID3"])) {
                        $emailBody .= "You have chosen to play cornhole.<br>";
                    }
                    if (isset($_POST["$chkboxID4"])) {
                        $emailBody .= "You have chosen to play softball.<br>";
                    }
                    $emailBody .= "If your choices differ from your partner's, please contact the event coordinator or reply to this email.<br>";
                    $emailBody .= "You may also change your or your partner's choices via your profile.<br>";
                
                }
                if ($event['eventtype'] === 'Dance Party') {
                    if (isset($_POST["$chkboxID2"])) {
                        $emailBody .= "You have chosen to attend dinner.<br>";
                        if ($event['eventcost'] > 0) {
                            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                            $coststr =  "Member Event Cost for the meal is approximately: "
                                  .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>
                                  Check the form for specific costs.<br>";
                   
                            $emailBody .= $coststr;
                            $toCC2 = $treasurer;
                            if (!$event['eventform']) {
                                $emailBody .= '<br>The signup form with meal choices and specific costs
                                is not currently available, but
                                you will receive an email when it is. The email will have the signup form
                                attached.<br>';
                            }
                            if ($event['eventform']) {
                                $actLink= "<a href='".$event['eventform']."'>
                                Click to view event Form</a><br>";
                               $emailBody .= 'There is a signup form to submit registration details and payment.<br>';
                               $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                                on the website to open the form. Or<br>$actLink";
             
                            }
                         }
                    } else {
                        $emailBody .= "You have chosen not to attend dinner before the dance.<br>";
                   
                         $emailBody .= "As of 2025, there is now a charge of $5 per person for the dance only.<br>";
                         $emailBody .= "Please submit your fee prior to the dance as indicated on the form.<br>";
                         if ($event['eventform']) {
                            $actLink= "<a href='".$event['eventform']."'>
                            Click to view event Form</a><br>";
                           $emailBody .= 'There is a signup form to submit registration details and payment.<br>';
                           $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                            on the website to open the form. Or<br>$actLink";
                           }
                        
                        
                    }
                }
                if ($event['eventtype'] === 'Dinner Dance') {
                if ($event['eventcost'] > 0) {
                    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $coststr =  "Member Event Cost is approximately: "
                          .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>
                          Check the form for specific costs.<br>";
           
                    $emailBody .= $coststr;
                    $toCC2 = $treasurer;
                    if (!$event['eventform']) {
                        $emailBody .= '<br>The signup form with meal choices and specific costs
                        is not currently available, but
                        you will receive an email when it is. The email will have the signup form
                        attached.<br>';
                    }
                    if ($event['eventform']) {
                        $actLink= "<a href='".$event['eventform']."'>
                        Click to view event Form</a><br>";
                       $emailBody .= 'There is a signup form to submit registration details and payment.<br>';
                       $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                        on the website to open the form. Or<br>$actLink";
    
                    }
                 }
                }
                if ($message) {
                    $emailBody .= '<br> MESSAGE from Registrant: <br>';
                    $emailBody .= $message;
                    $emailBody .= '<br> <br>';
                }
            
                $emailBody .= '<br>You may login to the website and look at 
                <strong>Your Profile</strong> to see which events and
                classes you have registered for, 
                and whether we have received payment for those that require it.';
                $emailBody .= '<br> <br>';
          
                // do the insert(s)
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->registeredby = $_SESSION['username'];
                $eventReg->userid = $regUserid1;
                if (isset($_POST["$chkboxID2"])) {
                    $eventReg->ddattenddinner = 1;
                } else {
                    $eventReg->ddattenddinner = 0;
                }
                if (isset($_POST["$chkboxID3"])) {
                    $eventReg->cornhole = 1;
                } else {
                    $eventReg->cornhole = 0;
                }
                if (isset($_POST["$chkboxID4"])) {
                    $eventReg->softball = 1;
                } else {
                    $eventReg->softball = 0;
                }
         
                $eventReg->message = $message;
                
            
                $eventReg->paid = 0;
                $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
            if (!$result) {
                $eventReg->create();
                $eventInst->addCount($eventReg->eventid);
             }
             if (isset($regFirstName2)) {
                       // do the insert(s)
                    $eventReg->firstname = $regFirstName2;
                    $eventReg->lastname = $regLastName2;
                    $eventReg->eventid = $eventId;
                    $eventReg->email = $regEmail2;
                    $eventReg->userid = $regUserid2;
                    $eventReg->message = $message;
                    $eventReg->registeredby = $_SESSION['username'];
                    $eventReg->paid = 0;
                    $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
                    if (!$result) {

                        $eventReg->create();
                        $eventInst->addCount($eventReg->eventid);
                    }
                }
             

            } // end if eventid            
       } //end isset
      } // end foreach
    if ($num_registered === 0) {
        $emailSubject = "Your event registration was invalid!";
        $emailBody = "You did not select any events to register for. Please return to the website to register for events and be sure to check a box for the event for which you would like to register.<br>"; 
    }
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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
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
