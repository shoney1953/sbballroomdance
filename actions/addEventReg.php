<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';

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
$user = new User($db);
$upcomingEvents = [];
$upcomingEvents = $_SESSION['upcoming_events'] ;
$users = [];
$users = $_SESSION['regUsers'] ;
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
$mailAttachment = ''; 
$replyTopic = "Event Registration";
$emailSubject = "The SBDC administrator has registered you for selected Events";

if (isset($_POST['submitAddReg'])) {        
    if (isset($_POST['eventid'])) {
        $event->id = $_POST['eventid'];
        $event->read_single();
    
        foreach($users as $usr) {
         $usrID = "us".$usr['id'];
         $attDin = "datt".$usr['id']; 
   
            if (isset($_POST["$usrID"])) {
                   
                    $eventReg->eventid = $_POST['eventid'];
                    $eventReg->firstname = $usr['firstname'];
                    $regFirstName1 = $eventReg->firstname;
                    $eventReg->lastname = $usr['lastname'];
                    $regLastName1 = $eventReg->lastname;
                    $eventReg->email = $usr['email'];
                    $regEmail1 = $eventReg->email;
                    $eventReg->userid = $usr['id'];

                    if ($event->eventtype === 'Dine and Dance') {
                    
                        if (isset($_POST["$attDin"])) {
                            $eventReg->ddattenddinner = 1;
                        }
                        
                    } else {
                        $eventReg->ddattenddinner = 0;
                    }
                    if ($event->eventtype === 'Dinner Dance') {
            
                        $eventReg->paid = 1;
                    } else {
          
                        $eventReg->paid = 0;
                    }
                 $result = $eventReg->checkDuplicate($regEmail1, $eventReg->eventid);
                 if (!$result) {

                    $eventReg->create();
                    $event->addCount($eventReg->eventid);
                    $event->id = $eventReg->eventid;
                    $event->read_single();
                    $emailBody .= '<br>************************************';
                    $emailBody .= "<br> <strong>Event: ".$event->eventname.
                    "<br>Type:    ".$event->eventtype.
                    "<br>DJ  :    ".$event->eventdj.
                    "<br>Room:    ".$event->eventroom.
                    "<br>Date:    ".date('M d Y',strtotime($event->eventdate))."</strong><br>"; 
                    if ($event->eventtype === 'Dine and Dance') {
                        if (isset($_POST["$attDin"])) {
                            $emailBody .= "<br>You have chosen to attend dinner before the dance.";
                        } else {
                            $emailBody .= "<br>You have chosen not to attend dinner before the dance.";
                        }
                    }
                  
                  
                 }
                
                $emailBody .= '<br>Note: You can also see these events from your profile on the website.';
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
        
        } //end isset
     } // end foreach
    }
       
}
   

$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit;

?>