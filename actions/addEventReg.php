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
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'secretary@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$mailAttachment = ''; 
$replyTopic = "Event Registration";
$emailSubject = "The SBDC administrator has registered you for selected Events";

if (isset($_POST['submitAddReg'])) {        
         
        foreach($users as $usr) {
         $usrID = "us".$usr['id'];
          
            if (isset($_POST["$usrID"])) {
                foreach($upcomingEvents as $ev) {
       
                $chkboxID = "ev".$ev['id'];   
                        
                if (isset($_POST["$chkboxID"])) {
           
                    $eventReg->eventid = $ev['id'];
                    $eventReg->firstname = $usr['firstname'];
                    $regFirstName1 = $eventReg->firstname;
                    $eventReg->lastname = $usr['lastname'];
                    $regLastName1 = $eventReg->lastname;
                    $eventReg->email = $usr['email'];
                    $regEmail1 = $eventReg->email;
                    $eventReg->userid = $usr['id'];
                    if ($ev['eventtype'] === 'Dinner Dance') {
            
                        $eventReg->paid = 1;
                    } else {
          
                        $eventReg->paid = 0;
                    }
    
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
                   
                    if ($event->eventform) {

                        $actLink= "<a href='".$event->eventform."'>
                        Click to view event Form</a>";
                       $emailBody .= 'There is a form to submit registration details and payment.<br>';
                       $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                        on the website to open the form. Or<br>$actLink";
                       $toCC2 = 'treasurer@sbballroomdance.com';
                    }
                    if ($event->eventcost > 0) {
                        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                        $coststr =  "<br> Member Event Cost is approximately: "
                              .$fmt->formatCurrency($event->eventcost, 'USD')."<br>
                              Check the form for specific costs.";
                        $emailBody .= $coststr;
                        $toCC2 = 'treasurer@sbballroomdance.com';
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
   


    $redirect = "Location: ".$_SESSION['adminurl']."#eventregistrations";
header($redirect);
exit;

?>