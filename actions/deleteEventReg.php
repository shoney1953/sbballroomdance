<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';



$regs = $_SESSION['eventregistrations'];

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
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
var_dump($_POST);
    foreach ($regs as $reg) {
       $delId = 'del'.$reg['id'];

    if (isset($_POST["$delId"])) {
    
        $eventReg->id = $reg['id'];
        $eventid = $_POST['eventid'];

        if ($reg['orgemail'] != null) {
            $toCC2 = $reg['orgemail'];
        }
    $regFirstName1 = $reg['firstname'];
    $regLastName1 = $reg['lastname'];
    $regEmail1 = $reg['email'];;
    $emailBody .= "<br>NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
    $emailBody .= 
                "<br>Event Date:  ".$reg['eventdate'].
                "<br>Event Type:  ".$reg['eventtype'].
                "<br>Event Name:  ".$reg['eventname'];
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
        }
   /*********************************************** */

  
           $eventReg->delete();
           var_dump($eventid);
           $event->decrementCount($eventid);
       }
    }

        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
 

?>