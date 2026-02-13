<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';

// $regs = $_SESSION['eventregistrations'];

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$guestEventReg = new EventRegistration($db);
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
$mailAttachment2 = "";
$replyTopic = "SBDC Event Registration Removal";
$regId1 = 0;
$regId2 = 0;
$create_successful = 0;
$result = 0;
$numregistered = 0;
    $gotEventRec = 0;
    $gotPartnerEventRec = 0;
    $guests = [];
    if (isset($_SESSION['guests']) && (count($_SESSION['guests']) > 0)) {
        $guests = $_SESSION['guests'];
    }

if (isset($_POST['submitRemoveRegs'])) {

    $event->id = $_POST['eventid'];
    $eventid = $_POST['eventid'];
    $event->read_single();

    $numregistered = $event->eventnumregistered;
    $gotEventRec = 0;
    $gotPartnerEventRec = 0;
     $emailBody .=
                "<br>Event Date:  ".$event->eventdate."
                 <br>Event Type:  ".$event->eventtype."
                 <br>Event Name:  ".$event->eventname."<br>";
   $emailBody .= "The following registrations were removed: <br>";
    if ($_SESSION['role'] === 'visitor') {
        $_SESSION['userid'] = '0';
        $regName = $_SESSION['visitorfirstname'].' '.$_SESSION['visitorlastname'];

    }
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
    
   if ($_SESSION['role'] !== 'visitor') {
       if (isset($_POST["$remID1"])) {
        $emailBody .= "<br>MEMBER NAME: ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']."<br>    EMAIL:  ".$_SESSION['useremail']."<br>";
        $regName = $_SESSION['userfirstname'].' '.$_SESSION['userlastname'];
        $regEmail = $_SESSION['useremail'];

         if ($gotEventRec) {
               $eventReg->delete();

                if ($numregistered > 0) {
                  $numregistered = $numregistered - 1;
       
                  $event->decrementCount($eventid, $numregistered);
                }

            }
      }
   } else {
       if (isset($_POST["$remID1"])) {
        $emailBody .= "<br>VISITOR NAME: ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']."<br>    EMAIL:  ".$_SESSION['visitoremail']."<br>";
        $regName = $_SESSION['visitorfirstname'].' '.$_SESSION['visitorlastname'];
        $regEmail = $_SESSION['visitoremail'];
     
         if ($gotEventRec) {
               $eventReg->delete();
                 if ($numregistered > 0) {
                  $numregistered = $numregistered - 1;
            
                  $event->decrementCount($eventid, $numregistered);
                }
            }
        }
   }
  

       if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
            if (isset($_POST["$remID2"])) {
              $toCC2 = $_SESSION['partneremail'];
              $emailBody .= "<br>PARTNER NAME: ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']."<br>    EMAIL:  ".$_SESSION['partneremail']."<br>";
              if ($gotPartnerEventRec) {
                 $partnerEventReg->delete();

                 if ($numregistered > 0) {
                  $numregistered = $numregistered - 1;
         
                  $event->decrementCount($eventid, $numregistered);
                 }
                }   
            }
        
         }



      foreach ($guests as $guest) {
        $remGuestID = "remguest".$guest['id'];   
        $guestID = 'guestid'.$guest['id']  ;   
        if (isset($_POST["$remGuestID"])) {
            $guestEventReg->id = $_POST["$guestID"];
            $emailBody .= "<br>Guest: ".$guest['firstname']." ".$guest['lastname']." removed.<br>";
           $guestEventReg->delete();
            if ($numregistered > 0) {
                  $numregistered = $numregistered - 1;
       
                  $event->decrementCount($eventid, $numregistered);
                }
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
                $mailAttachment2,
                $toCC2,
                $toCC3,
                $toCC4,
                $toCC5
            );
        } else {
            echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
             $redirect = "Location: ".$_SESSION['returnurl'];
            header($redirect);
           exit;
        }
        

  
    }

         $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>