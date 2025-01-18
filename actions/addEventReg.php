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
$emailBody = "Thanks for registering for the following SBDC events:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$result = 0;
$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$fromCC = 'sheila_honey_5@hotmail.com';
$replyEmail = 'peggyalbrecht@gmail.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$replyTopic = "SBDC Event Registration";
$emailSubject = "The SBDC administrator has registered you for selected SBDC Events";

if (isset($_POST['submitAddReg'])) {        
    if (isset($_POST['eventid'])) {
        $event->id = $_POST['eventid'];
        $event->read_single();
     
        foreach($users as $usr) {
         $usrID = "us".$usr['id'];
         $attDin = "datt".$usr['id']; 
         $pdDinn = "dpaid".$usr['id'];
         $playCornhole = "ch".$usr['id'];
         $playSoftball = "sb".$usr['id'];
   
            if (isset($_POST["$usrID"])) {
                   
                    $eventReg->eventid = $_POST['eventid'];
                    $eventReg->firstname = $usr['firstname'];
                    $regFirstName1 = $eventReg->firstname;
                    $eventReg->lastname = $usr['lastname'];
                    $regLastName1 = $eventReg->lastname;
                    $eventReg->email = $usr['email'];
                    $regEmail1 = $eventReg->email;
                    $eventReg->userid = $usr['id'];
                    $eventReg->paid = 0;
                    $eventReg->registeredby = $_SESSION['username'];
                    if ($event->eventtype === 'BBQ Picnic') {
                        if (isset($_POST["$attDin"])) {
                            $eventReg->ddattenddinner = 1;
                        }
                        if (isset($_POST["$playCornhole"])) {
                            $eventReg->cornhole = 1;
                        }
                        if (isset($_POST["$playSoftball"])) {
                            $eventReg->softball = 1;
                        }
                    }
                    if ($event->eventtype === 'Dine and Dance') {
                    
                        if (isset($_POST["$attDin"])) {
                            $eventReg->ddattenddinner = 1;
                        }
                        
                    } else {
                        $eventReg->ddattenddinner = 0;
                    }
                    if (isset($_POST["$pdDinn"])) {
                        $eventReg->paid = 1;
                    }
                    if (($event->eventtype === 'Dance Party') || ($event->eventtype === 'Dinner Dance')) {
                        if (isset($_POST["$attDin"])) {
                            $eventReg->ddattenddinner = 1;
                           
                        } else {
                        $eventReg->ddattenddinner = 0;
                        }
                      
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
                    "<br>Cost:    ".$fmt->formatCurrency($event->eventcost, 'USD').
                    "<br>Date:    ".date('M d Y',strtotime($event->eventdate))."</strong><br>"; 
                    if ($event->eventtype === 'BBQ Picnic') {
                        if (isset($_POST["$attDin"])) {
                            $emailBody .= "<br>You have chosen to attend the meal.";
                        }
                        if (isset($_POST["$playCornhole"])) {
                            $emailBody .= "<br>You have chosen to play Cornhole.";
                        }
                        if (isset($_POST["$playSoftball"])) {
                            $emailBody .= "<br>You have chosen to play Softball.";
                        }
                        $emailBody .= "<br>You may go to Your Profile on the Website to change any of these options.";
                    }
                    if (($event->eventtype === 'Dine and Dance') || ($event->eventtype === 'Dance Party') ){
                        if (isset($_POST["$attDin"])) {
                            $emailBody .= "<br>You have chosen to attend dinner before the dance.";

                        } else {
                            $emailBody .= "<br>You have chosen NOT to attend dinner before the dance.";
                            $emailBody .= "<br>As of 2025, there is now a charge of $5 per person for the dance only.";
                            $emailBody .= "<br>Please submit your fee prior to the dance as indicated on the form.";
                        }
                    }
                   

                    if ($event->eventform) {
                        $actLink= "<a href='".$event->eventform."'>
                        Click to view event Form</a><br>";
                       $emailBody .= '<br>There is a flyer associated with the dance.<br>';
                       $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                        on the website to open the form. Or<br>$actLink";
     
                    }
                  
                 }
                 if ($event->orgemail != null) {
                    $toCC2 = $event->orgemail;
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
                        $toCC2,
                        $toCC3,
                        $toCC4,
                        $toCC5
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