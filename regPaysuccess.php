<?php
session_start();
require_once 'includes/sendEmail.php';
require_once 'includes/siteemails.php';
require_once 'config/Database.php';
require_once 'models/EventRegistration.php';
require_once 'models/Event.php';
require_once 'models/User.php';require_once 'models/TempOnlineRegPay.php';

$database = new Database();
$db = $database->connect();
$event = new Event($db);
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);
$tempReg = new TempOnlineRegPay($db);

$tempRegID = $_GET['regid'];
unset($_GET['regid']);
$tempReg->id = $tempRegID;
$tempReg->read_single();

$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$replyTopic = "SBDC Event Registration";
$emailBody = "Thanks for paying online for the ".$tempReg->eventname." on ".$tempReg->eventdate.".<br>";
$emailSubject = "You paid onlne for ".$tempReg->eventname.".";
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$regFirstName1 = $tempReg->firstname1;
$regLastName1 = $tempReg->lastname1;

$eventReg->eventid = $tempReg->eventid;
$eventInst->id = $tempReg->eventid;
$eventInst->read_single();
$eventReg->id = $tempReg->regid1;
$eventReg->paid = 1;
$eventReg->paidonline = 1;
$eventReg->updatePaid();

if ($tempReg->ddattenddinner === '1') {

    if ($tempReg->mealchoice1 !== '0') {
     
        $emailBody .= "You have chosen ".$tempReg->mealdesc1." at a cost of $".number_format($tempReg->cost1/100,2).".<br>";         
    }
} else {
    $emailBody .= "You have chosen attend the dance only at a cost of $".number_format($eventInst->eventcost).".<br>";
}


if ($tempReg->firstname2 != '') {

$toCC2 = $eventReg->email;
$eventReg->id = $tempReg->regid2;
$eventReg->paid = 1;
$eventReg->paidonline = 1;
$eventReg->updatePaid();

    if ($tempReg->ddattenddinner === '1') {
        if ($tempReg->mealchoice2 !== '0') {
            $emailBody .= "Your Partner ".$eventReg->firstname." ".$eventReg->lastname." has chosen ".$tempReg->mealdesc2." at a cost of $".number_format($tempReg->cost2/100,2).".<br>";   
        }
    } else {
       $emailBody .= "Your partner has chosen attend the dance only at a cost of $".number_format($eventInst->eventcost).".<br>";
    }
}
  $emailBody .= "The total amount you paid online was : $".number_format($tempReg->totalcost/100,2).".<br>";
  $emailBody .= "You can always check your profile on the website to see the status of event registration.<br>";
  $emailBody .= "We look forward to seeing you at the event!<br>";
$tempReg->delete();

if (filter_var($tempReg->email1, FILTER_VALIDATE_EMAIL)) {
      
        $regName1 = $regFirstName1.' '.$regLastName1;
   
        sendEmail(
            $tempReg->email1, 
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