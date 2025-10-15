<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/Event.php';
require_once '../models/Visitor.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
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
$mChoices = new DinnerMealChoices($db);
$emailBody = "Thanks for registering for the following SBDC events:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$mealChoices = [];
$id_int = 0;
$result = 0;
$fromCC = $webmaster;
$replyEmail = $secretary;
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = ''; 
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = '../img/SBDC Membership Form 2025.pdf'; 
$replyTopic = "SBDC Event Registration";


if (isset($_POST['submitAddVisitorReg'])) {

    $eventReg->eventid = $_POST['eventid'];
    $eventReg->firstname = $_POST['firstname1'];
    $eventReg->registeredby = $_SESSION['username'];
    $regFirstName1 = $eventReg->firstname;
    $eventReg->lastname = $_POST['lastname1'];
    $eventReg->email = $_POST['email1'];
    $regEmail1 = $eventReg->email;
    $eventReg->paid = 0;
    if (isset($_POST['attdin1'])) {
        $eventReg->ddattenddinner = 1;
    } else {
        $eventReg->ddattenddinner = 0; 
    }
    if (isset($_POST['pddinn1'])) {
        $eventReg->paid = 1;
    }
   
    $eventReg->userid = 0;
  
    $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);

    if ($result) {
        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
    }

    if (!$result) {

  
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email1'], FILTER_SANITIZE_EMAIL); 
    $regEmail1 =  $visitor->email;
    $visitor->firstname = $_POST['firstname1'];
    $regFirstName1 = $visitor->firstname;
    $visitor->lastname = $_POST['lastname1'];
    $visitor->notes = $_POST['notes1'];
    $regLastName1 =  $visitor->lastname;
    if ($visitor->read_ByEmail($visitor->email)) {
        $visitor->firstname = $_POST['firstname1'];
        $visitor->lastname = $_POST['lastname1'];
        $visitor->notes = $_POST['notes1'];
        $visitor->update($visitor->email);
    } else {
    
        $visitor->create();
    }

    $event->id = $eventReg->eventid;
    $event->read_single();
    $emailBody .= '<br>************************************';
    $emailSubject = "The SBDC administrator has registered you as a Visitor for ".$event->eventname."";
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
        $toCC2 = $treasurer;
        if ($event->eventform) {
          if (substr($event->eventform,0,4) === 'http') {
                       $actLink= "<a href='".$event->eventform."'>
            Click to view event Form</a>";
            $emailBody .= 'There is a form to submit registration details and payment.<br>';
            $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                on the website to open the form. Or<br>$actLink";
            $toCC2 = $treasurer; 
          } else {
           $mailAttachment = '..uploads/forms/'.$event->eventform;
            $emailBody .= 'There is an attached form to submit registration details and payment.<br>';

          }

        }
    }
            echo '</tr>';
      
       
             if (($event->eventtype === 'Dinner Dance') || ($event->eventtype === 'Dance Party')) {

              $result = $mChoices->read_ByEventId($event->id);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
                    } // while
              
                  
                        foreach ($mealChoices as $choice) {
                          $mcID = "mcVisitor1".$choice['id'];
                          if (isset($_POST["$mcID"])) {
                            $eventReg->mealchoice = $choice['id'];
                            $emailBody .= "<br>You have selected ".$choice['mealchoice']." at a cost of ".number_format($choice['memberprice']/100,2).".";
                          }
                         } // foreach

                       $drID = "drVisitor1";
                       if (isset($_POST["$drID"])) {
                        $eventReg->dietaryrestriction = $_POST["$drID"];
                        $emailBody .= "<br>You have specified a dietary restriction of ".$_POST["$drID"];
                       }
                     
                  
                }   // row count 
             } // eventtype
   
    
    $eventReg->create();
    $event->addCount($eventReg->eventid);
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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
        $emailBody = "Thanks for registering for the following events:<br>";  
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
   }                    
    }
    
    /*      2nd visitor */
    $eventReg->eventid = $_POST['eventid'];
    if (isset($_POST['email2'])) {
        if ($_POST['lastname2'] != '') {

    
    $eventReg->firstname = $_POST['firstname2'];
    $regFirstName1 = $eventReg->firstname;
    $eventReg->lastname = $_POST['lastname2'];
    $eventReg->email = $_POST['email2'];
    $eventReg->registeredby = $_SESSION['username'];
    $regEmail1 = $eventReg->email;
    $eventReg->paid = 0;
    if (isset($_POST['attdin2'])) {
        $eventReg->ddattenddinner = 1;
    } else {
        $eventReg->ddattenddinner = 0; 
    }
    if (isset($_POST['pddinn2'])) {
        $eventReg->paid = 1;
    }
   
    $eventReg->userid = 0;
    $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
    if ($result) {
        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
    }
    if (!$result) {

   
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email2'], FILTER_SANITIZE_EMAIL); 
    $regEmail1 =  $visitor->email;
    $visitor->firstname = $_POST['firstname2'];
    $regFirstName1 = $visitor->firstname;
    $visitor->lastname = $_POST['lastname2'];
    $visitor->notes = $_POST['notes2'];
    $regLastName1 =  $visitor->lastname;
    if ($visitor->read_ByEmail($visitor->email)) {
        $visitor->firstname = $_POST['firstname2'];
        $visitor->lastname = $_POST['lastname2'];
        $visitor->notes = $_POST['notes2'];
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
        $toCC2 = $treasurer;
        if ($event->eventform) {
          if (substr($event->eventform,0,4) === 'http') {
            $actLink= "<a href='".$event->eventform."'>
            Click to view event Form</a>";
            $emailBody .= 'There is a form to submit registration details and payment.<br>';
            $emailBody .= "Click on <em>VIEW</em> in the Form column of the event listing
                on the website to open the form. Or<br>$actLink";
            $toCC2 = $treasurer;
          } else {
            $mailAttachment = '../uploads/forms/'.$event->eventform;
            $emailBody .= 'There is an attached form to submit registration details and payment.<br>';

          }

        }
         foreach ($mealChoices as $choice) {
            $mcID = "mcVisitor2".$choice['id'];
            if (isset($_POST["$mcID"])) {
                    $eventReg->mealchoice = $choice['id'];
                    $emailBody .= "<br>You have selected ".$choice['mealchoice']." at a cost of ".number_format($choice['memberprice']/100,2).".";
                 }
                } // foreach

                $drID = "drVisitor2";
                if (isset($_POST["$drID"])) {
                $eventReg->dietaryrestriction = $_POST["$drID"];
                $emailBody .= "<br>You have specified a dietary restriction of ".$_POST["$drID"];
        }
      $eventReg->create();
      $event->addCount($eventReg->eventid);                
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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
        $emailBody = "Thanks for registering for the following events:<br>";  
    } else {
        echo 'Registrant Email 2 is empty or Invalid. Please enter valid email.';
   }                    
    }
}
}
}  
   

$redirect = "Location: ".$_SESSION['returnurl'];
header($redirect);
exit;

?>