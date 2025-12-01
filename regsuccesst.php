<?php

require_once 'includes/sendEmail.php';
require_once 'includes/siteemails.php';
require_once 'config/Database.php';
require_once 'models/EventRegistration.php';
require_once 'models/Event.php';
require_once 'models/User.php';
require_once 'models/DinnerMealChoices.php';
require_once 'models/TempOnlineEventReg.php';

$database = new Database();
$db = $database->connect();
$event = new Event($db);
$eventReg = new EventRegistration($db);
$partnerEventReg = new EventRegistration($db);
$eventInst = new Event($db);
$tempReg = new TempOnlineEventReg($db);
$user = new User($db);

$tempRegID = $_GET['regid'];
unset($_GET['regid']);
$tempReg->id = $tempRegID;
$tempReg->read_single();

$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$mailAttachment2 = "";
$replyTopic = "SBDC Online Event Registration";

$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
if ($tempReg->firstname1 != '') {
$emailBody = "Thanks, ".$tempReg->firstname1." ".$tempReg->lastname1." for registering for the ".$tempReg->eventname." on ".$tempReg->eventdate.".<br>";
$emailSubject = $tempReg->firstname1." ".$tempReg->lastname1." registered for ".$tempReg->eventname." and paid online.";

$eventReg->eventid = $tempReg->eventid;
$eventReg->eventname = $tempReg->eventname;
$eventReg->eventtype = $tempReg->eventtype;
$eventReg->orgemail = $tempReg->orgemail;
$eventReg->registeredby = $tempReg->registeredby;
$eventReg->paidonline = 1;
$eventReg->paid = 1;
$eventReg->message = $tempReg->message;
$eventReg->firstname = $tempReg->firstname1;
$eventReg->lastname = $tempReg->lastname1;
$eventReg->email = $tempReg->email1;
$regFirstName1 = $eventReg->firstname;
$regLastName1 = $eventReg->lastname;
$regEmail1 = $eventReg->email;
if ($tempReg->visitor !== '1') {
    $user->getUserName($eventReg->email);

    $eventReg->userid = $user->id;
    if ($user->partnerId !== '0' ) {
        $eventReg->dwop = 0;
    } else {
         $eventReg->dwop = 1;
    }
} else {
     $eventReg->userid = 0;
     $eventReg->dwop = 2;
}

$regEmail1 = $eventReg->email;
$regName1 = $eventReg->firstname." ".$eventReg->lastname;
$eventReg->ddattenddinner = $tempReg->ddattenddinner;
$eventReg->ddattenddance = 1;
$eventReg->mealchoice = $tempReg->mealchoice1;
$eventReg->dietaryrestriction = $tempReg->dietaryrestriction1;

if ($tempReg->ddattenddinner === '1') {

    if ($tempReg->mealchoice1 !== '0') {
      

        $emailBody .= "You have chosen ".$tempReg->mealdesc1;
            if ($tempReg->dietaryrestriction1 != '') {
            $emailBody .= " with a dietary restriction of ".$tempReg->dietaryrestriction1.".<br>";
            } else 
            {
                $emailBody .= ".<br>";
            }
    }
} else {
    $emailBody .= "You have chosen not to attend dinner.<br>";
}

$eventReg->create();
$event->addCount($eventReg->eventid);
}
if ($tempReg->firstname2 != '') {
  $partnerEventReg->eventid = $tempReg->eventid;
$partnerEventReg->eventname = $tempReg->eventname;
$partnerEventReg->eventtype = $tempReg->eventtype;
$partnerEventReg->orgemail = $tempReg->orgemail;
$partnerEventReg->registeredby = $tempReg->registeredby;
$partnerEventReg->paidonline = 1;
$partnerEventReg->paid = 1;
$partnerEventReg->message = $tempReg->message;
$partnerEventReg->ddattenddinner = $tempReg->ddattenddinner;
$partnerEventReg->mealchoice = $tempReg->mealchoice2;
$partnerEventReg->dietaryrestriction = $tempReg->dietaryrestriction2;
$partnerEventReg->firstname = $tempReg->firstname2;
$partnerEventReg->lastname = $tempReg->lastname2;
$partnerEventReg->email = $tempReg->email2;
if ($tempReg->visitor !== '1') {
    $user->getUserName($partnerEventReg->email);
    $partnerEventReg->userid = $user->id;
   
       if ($user->partnerId !== '0' ) {
        $partnerEventReg->dwop = 0;
    } else {
         $partnerEventReg->dwop = 1;
    }
} else {
     $partnerEventReg->userid = 0;
     $partnerEventReg->dwop = 2;
}
if ($tempReg->firstname1 === '') {
  $regFirstName1 = $partnerEventReg->firstname;
  $regLastName1 = $partnerEventReg->lastname;
  $regEmail1 = $partnerEventReg->email;
} else {
   $toCC2 = $partnerEventReg->email;
}


$partnerEventReg->create();
$event->addCount($partnerEventReg->eventid);
    if ($tempReg->ddattenddinner === '1') {
        if ($tempReg->mealchoice2 !== '0') {
            $emailBody .= "Your Partner ".$partnerEventReg->firstname." ".$partnerEventReg->lastname." has chosen ".$tempReg->mealdesc2;
                if ($tempReg->dietaryrestriction2 != '') {
                $emailBody .= " with a dietary restriction of ".$tempReg->dietaryrestriction2.".<br>";
                } else 
                {
                    $emailBody .= ".<br>";
                }
        }
    } 
}
  $emailBody .= "The total amount you paid online was : $".number_format($tempReg->totalcost/100,2).".<br>";
  if ($tempReg->message != '') {
    $emailBody .= "Your message to the event coordinator is : ".$tempReg->message.".<br>";
  }
  $emailBody .= "We look forward to seeing you at the event!<br>";
$tempReg->delete();
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
            $mailAttachment2,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
    }
    if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
       $YOUR_DOMAIN = 'https://www.sbballroomdance.com'; 
    } else {
         $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
    }
       $redirect = "Location: ".$YOUR_DOMAIN;
       header($redirect); 
       exit;
 
?>