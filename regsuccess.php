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
$replyTopic = "SBDC Event Registration";
$emailBody = "Thanks for registering for the ".$tempReg->eventname." on ".$tempReg->eventdate.".<br>";
$emailSubject = "You have registered for ".$tempReg->eventname." and paid online.";
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';

if ($tempReg->firstname1 != '') {
$eventReg->eventid = $tempReg->eventid;
$eventReg->eventname = $tempReg->eventname;
$eventReg->eventtype = $tempReg->eventtype;
$eventReg->orgemail = $tempReg->orgemail;
$eventReg->registeredby = $tempReg->registeredby;
$eventReg->paidonline = 1;
$eventReg->paid = 1;
$eventReg->guest = 0;
$eventReg->message = $tempReg->message;
$eventReg->firstname = $tempReg->firstname1;
if ($tempReg->eventtype !== 'BBQ Picnic') {
    $eventReg->numhamburgers = 0;
    $eventReg->numhbbuns = 0;
    $eventReg->numhotdogs = 0;
    $eventReg->numhdbuns = 0;
    $eventReg->vegetarian = 0;
}
$eventReg->lastname = $tempReg->lastname1;
$eventReg->email = $tempReg->email1;
$regFirstName1 = $eventReg->firstname;
$regLastName1 = $eventReg->lastname;

$regEmail1 = $eventReg->email;
if ($tempReg->visitor !== '1') {
    $user->getUserName($eventReg->email);
    $eventReg->userid = $user->id;
    if ($user->partnerId !== '0') {
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
$eventReg->ddattenddinner = $tempReg->ddattenddinner1;
$eventReg->ddattenddance = 1;
$eventReg->mealchoice = $tempReg->mealchoice1;
$eventReg->dietaryrestriction = $tempReg->dietaryrestriction1;

if ($tempReg->ddattenddinner1 === '1') {

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
} // end member 1

if ($tempReg->firstname2 != '') {
    $eventReg->eventid = $tempReg->eventid;
$eventReg->eventname = $tempReg->eventname;
$eventReg->eventtype = $tempReg->eventtype;
$eventReg->orgemail = $tempReg->orgemail;
$eventReg->registeredby = $tempReg->registeredby;
$eventReg->paidonline = 1;
$eventReg->mealchoice = $tempReg->mealchoice2;
$eventReg->dietaryrestriction = $tempReg->dietaryrestriction2;
$eventReg->firstname = $tempReg->firstname2;
$eventReg->lastname = $tempReg->lastname2;
$eventReg->email = $tempReg->email2;
$eventReg->guest = 0;
$eventReg->paid = 1;
$eventReg->ddattenddinner = $tempReg->ddattenddinner2;
$toCC3 = $eventReg->email;
if ($tempReg->eventtype !== 'BBQ Picnic') {
    $eventReg->numhamburgers = 0;
    $eventReg->numhbbuns = 0;
    $eventReg->numhotdogs = 0;
    $eventReg->numhdbuns = 0;
    $eventReg->vegetarian = 0;
}
if ($tempReg->visitor !== '1') {
    $user->getUserName($eventReg->email);

    $eventReg->userid = $user->id;
    if ($user->partnerId !== '0') {
       $eventReg->dwop = 0;  
    } else {
        $eventReg->dwop = 1;
    }
} else {
     $eventReg->userid = 0;
}
$eventReg->create();
$event->addCount($eventReg->eventid);

    if ($tempReg->ddattenddinner2 === '1') {
        if ($tempReg->mealchoice2 !== '0') {
            $emailBody .= "Your Partner ".$eventReg->firstname." ".$eventReg->lastname." has chosen ".$tempReg->mealdesc2;
                if ($tempReg->dietaryrestriction2 != '') {
                $emailBody .= " with a dietary restriction of ".$tempReg->dietaryrestriction2.".<br>";
                } else 
                {
                    $emailBody .= ".<br>";
                }
        }
    } else {
    $emailBody .= "Your Partner has  chosen not to attend dinner.<br>";
}

} // end partner
    // var_dump($tempReg);
if ($tempReg->guest1firstname != '') {
    $eventReg->eventid = $tempReg->eventid;
$eventReg->eventname = $tempReg->eventname;
$eventReg->eventtype = $tempReg->eventtype;
$eventReg->orgemail = $tempReg->orgemail;
$eventReg->registeredby = $tempReg->registeredby;
$eventReg->paidonline = 1;
$eventReg->mealchoice = $tempReg->guest1mealchoice;
$eventReg->dietaryrestriction = $tempReg->guest1dr;
$eventReg->firstname = $tempReg->guest1firstname;
$eventReg->lastname = $tempReg->guest1lastname;
$eventReg->email = $tempReg->guest1email;
$eventReg->guest = 1;
$eventReg->paid = 1;
$eventReg->userid = 0;
$eventReg->ddattenddinner = $tempReg->guest1attenddinner;
$toCC4 = $eventReg->email;
if ($tempReg->eventtype !== 'BBQ Picnic') {
    $eventReg->numhamburgers = 0;
    $eventReg->numhbbuns = 0;
    $eventReg->numhotdogs = 0;
    $eventReg->numhdbuns = 0;
    $eventReg->vegetarian = 0;
}
     $eventReg->dwop = 2;
     $eventReg->userid = 0;


$eventReg->create();
$event->addCount($eventReg->eventid);
    if ($tempReg->guest1attenddinner === '1') {
      
        if ($tempReg->guest1mealchoice !== '0') {
            $emailBody .= "Guest 1 ".$eventReg->firstname." ".$eventReg->lastname." has chosen ".$tempReg->guest1mealdesc;
                if ($tempReg->guest1dr != '') {
                $emailBody .= " with a dietary restriction of ".$tempReg->guest1dr.".<br>";
                } else 
                {
                    $emailBody .= ".<br>";
                }
        }
    } else {
    $emailBody .= "Guest  ".$eventReg->firstname." ".$eventReg->lastname." has chosen not to attend dinner.<br>";
}
}  // end guest 1


if ($tempReg->guest2firstname != '') {
$eventReg->eventid = $tempReg->eventid;
$eventReg->eventname = $tempReg->eventname;
$eventReg->eventtype = $tempReg->eventtype;
$eventReg->orgemail = $tempReg->orgemail;
$eventReg->registeredby = $tempReg->registeredby;
$eventReg->paidonline = 1;
$eventReg->mealchoice = $tempReg->guest2mealchoice;
$eventReg->dietaryrestriction = $tempReg->guest2dr;
$eventReg->firstname = $tempReg->guest2firstname;
$eventReg->lastname = $tempReg->guest2lastname;
$eventReg->email = $tempReg->guest2email;
$eventReg->userid = 0;
$eventReg->paid = 1;
$toCC5 = $eventReg->email;
$eventReg->guest = 1;
$eventReg->ddattenddinner = $tempReg->guest2attenddinner;
if ($tempReg->eventtype !== 'BBQ Picnic') {
    $eventReg->numhamburgers = 0;
    $eventReg->numhbbuns = 0;
    $eventReg->numhotdogs = 0;
    $eventReg->numhdbuns = 0;
    $eventReg->vegetarian = 0;
}
     $eventReg->dwop = 2;
     $eventReg->userid = 0;


$eventReg->create();
$event->addCount($eventReg->eventid);

    if ($tempReg->guest2attenddinner === '1') {
         
        if ($tempReg->mealchoice2 !== '0') {
            $emailBody .= "Guest 2 ".$eventReg->firstname." ".$eventReg->lastname." has chosen ".$tempReg->guest2mealdesc;
                if ($tempReg->guest2dr != '') {
                $emailBody .= " with a dietary restriction of ".$tempReg->guest2dr.".<br>";
                } else 
                {
                    $emailBody .= ".<br>";
                }
        }
    } else {
    $emailBody .= "Guest  ".$eventReg->firstname." ".$eventReg->lastname." has chosen not to attend dinner.<br>";
}
}
  $emailBody .= "The total amount you paid online was : $".number_format($tempReg->totalcost/100,2).".<br>";
  if ($tempReg->message != '') {
    $emailBody .= "Your message to the event coordinator is : ".$tempReg->message.".<br>";
  }
  $emailBody .= "We look forward to seeing you at the event!<br>";


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
      $tempReg->id = $tempRegID;
      $tempReg->delete();
       $redirect = "Location: ".$YOUR_DOMAIN;
       header($redirect); 
       exit;
 
?>