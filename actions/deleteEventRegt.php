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
$replyTopic = "SBDC Event Registration";
$regId1 = 0;
$regId2 = 0;
$create_successful = 0;
$result = 0;

if (isset($_POST['submitRemoveRegs'])) {
    $eventReg->read_ByEventIdUser( $_POST['eventid'],$_SESSION['userid']);
 
    if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
        $partnerEventReg->read_ByEventIdUser( $_POST['eventid'],$_SESSION['partnerid']);            
       }
            $remID1 = "rem".$eventReg->id;
            $remID2 = "rem".$partnerEventReg->id;
  if (isset($_POST['deleteRegs'])) {
   $regId1 = $_POST['regID1'];
    if (isset($_POST["$remID2"])) {
      $regId2 = $_POST['regID2'];
      }
           
    if (isset($_POST["$remID1"])) {
        $eventReg->id = $regId1;
        $eventid = $_POST['eventid'];
        if ($eventReg->orgemail != null) {
            $toCC2 = $eventReg->orgemail;
        }
        if (isset($_POST["$remID2"])) {
          $toCC3 = $_SESSION['partneremail'];
            $partnerEventReg->id = $regId2;
            $eventid = $_POST['eventid'];
        }
    $regFirstName1 = $eventReg->firstname;
    $regLastName1 = $eventReg->lastname;
    $regEmail1 = $_SESSION['useremail'];
    $emailBody .= "<br>NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
    $emailBody .= 
                "<br>Event Date:  ".$eventReg->eventdate.
                "<br>Event Type:  ".$eventReg->eventtype.
                "<br>Event Name:  ".$eventReg->eventname;
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
            echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
            //  $redirect = "Location: ".$_SESSION['returnurl'];
            // header($redirect);
           exit;
        }
        
   /*********************************************** */

           $eventReg->delete();

           $event->decrementCount($eventid);
          if (isset($_POST["$remID2"])) {
             
                  $partnerEventReg->delete();
                  $event->decrementCount($eventid);
          }
       
           }
          }
  }

         $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>