<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/Visitor.php';

date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$event = new Event($db);
$visitor = new Visitor($db);
$emailBody = "Thanks for registering for the following events:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$result = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'secretary@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = ''; 
$mailAttachment = '../img/Membership Form 2023 Dance Club.pdf'; 
$replyTopic = "Event Registration";
$emailSubject = "The SBDC administrator has registered you as a Visitor for an Event";

if (isset($_POST['submitAddVisitorReg'])) {

     $eventReg->eventid = $_POST['eventid'];
    $eventReg->firstname = $_POST['firstname'];
    $regFirstName1 = $eventReg->firstname;
    $eventReg->lastname = $_POST['lastname'];
    $eventReg->email = $_POST['email'];
    $regEmail1 = $eventReg->email;
    $eventReg->paid = 0;
    $eventReg->userid = 0;
    $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
    if (!$result) {

    $eventReg->create();
    $event->addCount($eventReg->eventid);
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
    $regEmail1 =  $visitor->email;
    $visitor->firstname = $_POST['firstname'];
    $regFirstName1 = $visitor->firstname;
    $visitor->lastname = $_POST['lastname'];
    $visitor->notes = $_POST['notes'];
    $regLastName1 =  $visitor->lastname;
    if ($visitor->read_ByEmail($visitor->email)) {
        $visitor->firstname = $_POST['firstname'];
        $visitor->lastname = $_POST['lastname'];
        $visitor->notes = $_POST['notes'];
        $visitor->update($visitor->email);
    } else {
    
        $visitor->create();
    }

    $event->id = $eventReg->eventid;
    $event->read_single();
    $emailBody .= '<br>************************************';
    $emailBody .= "<br> <strong>Event: ".$event->eventname.
    "<br>Type:    ".$event->eventtype.
    "<br>DJ  :    ".$event->eventdj.
    "<br>Room:    ".$event->eventroom.
    "<br>Date:    ".date('M d Y',strtotime($event->eventdate))."</strong><br>"; 
                    

    if ($event->eventcost > 0) {
        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $coststr =  "<br> Member Event Cost is approximately: "
        .$fmt->formatCurrency($event->eventcost, 'USD')."<br>
            Check the form for specific costs. <br>Non-member cost will be slightly higher.";
        $emailBody .= $coststr;
        $toCC2 = 'shamburgrog23@gmail.com';
        if ($event->eventform) {

            $actLink= "<a href='".$event->eventform."'>
            Click to view event Form</a>";
            $emailBody .= 'There is a form to submit registration details and payment.<br>';
            $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                on the website to open the form. Or<br>$actLink";
            $toCC2 = 'shamburgrog23@gmail.com';
        }
    }
    $emailBody .= '<br>We hope you enjoy the event and consider joining our club.';
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
        $emailBody = "Thanks for registering for the following events:<br>";  
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
   }                    
    }
}  
   

$redirect = "Location: ".$_SESSION['adminurl']."#eventregistrations";
header($redirect);
exit;

?>