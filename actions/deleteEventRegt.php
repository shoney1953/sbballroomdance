<?php
// session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';

$regs = $_SESSION['eventregistrations'];

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnerEventReg = new EventRegistration($db);
$event = new Event($db);
$regSelected = [];
$eventid = 0;
$regAll = '';
$emailBody = "Your Event Registration has been removed:<br>";
$emailSubject = 'SBDC Event Registration Removed';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = "";
$replyTopic = "SBDC Event Registration Removal";
$regId1 = 0;
$regId2 = 0;
$create_successful = 0;
$result = 0;
    $gotEventRec = 0;
    $gotPartnerEventRec = 0;

if (isset($_POST['submitRemoveRegs'])) {
    $event->id = $_POST['eventid'];
    $event->read_single();

    $gotEventRec = 0;
    $gotPartnerEventRec = 0;
    if ($eventReg->read_ByEventIdUser( $_POST['eventid'],$_SESSION['userid'])) {
           $gotEventRec = 1;
           $remID1 = "rem".$eventReg->id;
 
    }
 
    if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
        if ($partnerEventReg->read_ByEventIdUser( $_POST['eventid'],$_SESSION['partnerid'])) {
            $gotPartnerEventRec = 1;
            $remID2 = "rem".$partnerEventReg->id; 
                
    }              
 }
         $emailBody .=
                 "<br>Event Date:  ".$event->eventdate."
                 <br>Event Type:  ".$event->eventtype."
                 <br>Event Name:  ".$event->eventname."<br>";

    if (isset($_POST["$remID1"])) {
  
        $eventid = $_POST['eventid'];
    
        if ($eventReg->orgemail != null) {
            $toCC2 = $eventReg->orgemail;
            if (isset($_SESSION['partneremail'])) {
                 $toCC3 = $_SESSION['partneremail'];
            }
         
        } else {
             $toCC2 = $_SESSION['partneremail'];
        }

    $emailBody .= "<br>NAME: ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']."<br>    EMAIL:  ".$_SESSION['useremail']."<br>";
 

        if (filter_var($_SESSION['useremail'], FILTER_VALIDATE_EMAIL)) {
            $regName1 = $_SESSION['userfirstname'].' '.$_SESSION['userlastname'];
            sendEmail(
                $_SESSION['useremail'], 
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
            echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
            //  $redirect = "Location: ".$_SESSION['returnurl'];
            // header($redirect);
           exit;
        }

           $eventReg->delete();
           $event->decrementCount($eventid);
        
        }  // end of member 1

            if (isset($_POST["$remID2"])) {
        
             $eventid = $_POST['eventid'];

             if ($partnerEventReg->orgemail != null) {
               $toCC2 = $partnerEventReg->orgemail;
               $toCC3 = $_SESSION['useremail'];
             } else {
                 $toCC2 = $_SESSION['useremail'];
             }
                $emailBody .= "<br>PARTNER NAME: ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']."<br>    EMAIL:  ".$_SESSION['partneremail']."<br>";

               if (filter_var($_SESSION['partneremail'], FILTER_VALIDATE_EMAIL)) {
            $regName2 = $_SESSION['partnerfirstname'].' '.$_SESSION['partnerlastname'];
            sendEmail(
                $_SESSION['partneremail'], 
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
        } else {
            echo 'Registrant 2 Email is empty or Invalid. Please enter valid email.';
            //  $redirect = "Location: ".$_SESSION['returnurl'];
            // header($redirect);
           exit;
        }

                  $partnerEventReg->delete();
                  $event->decrementCount($eventid);
            }
       
    }

         $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>