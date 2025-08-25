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

    if ((isset($_POST["$remID1"])) && (isset($_POST["$remID2"]))) {
        
    
        if ($eventReg->orgemail != null) {
            $toCC2 = $eventReg->orgemail;
            if (isset($_SESSION['partneremail'])) {
                 $toCC3 = $_SESSION['partneremail'];
            }
         
        } else {
             $toCC2 = $_SESSION['partneremail'];
        }

         $emailBody .= "<br>MEMBER NAME: ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']."<br>    EMAIL:  ".$_SESSION['useremail']."<br>";
         $emailBody .= "<br>PARTNER NAME: ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']."<br>    EMAIL:  ".$_SESSION['partneremail']."<br>";
         $regName = $_SESSION['userfirstname'].' '.$_SESSION['userlastname'];
         $regEmail = $_SESSION['useremail'];
    }
       if ((isset($_POST["$remID1"])) && (!isset($_POST["$remID2"]))) {
    
                    $emailBody .= "<br>MEMBER NAME: ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']."<br>    EMAIL:  ".$_SESSION['useremail']."<br>";
                     $regName = $_SESSION['userfirstname'].' '.$_SESSION['userlastname'];
                     $regEmail = $_SESSION['useremail'];
       }
       if ((isset($_POST["$remID2"])) && (!isset($_POST["$remID1"]))) {

                     $emailBody .= "<br>PARTNER NAME: ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']."<br>    EMAIL:  ".$_SESSION['partneremail']."<br>";
                     $regName = $_SESSION['partnerfirstname'].' '.$_SESSION['partnerlastname'];
                     $regEmail = $_SESSION['partneremail'];
                      if ($event->orgemail != null) {
                        $toCC2 = $event->orgemail;
                        $toCC3 = $_SESSION['useremail'];
                      } else {
                          $toCC2 = $_SESSION['useremail'];
                      }
                         
        }  
    

        if (filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
       
            sendEmail(
                $regEmail, 
                $regName, 
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
         if (isset($_POST["$remID1"])) {
           $eventReg->delete();
           $event->decrementCount($eventid);
         }
        if (isset($_POST["$remID2"])) {
           $partnerEventReg->delete();
           $event->decrementCount($eventid);
         }
  
    }

         $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>