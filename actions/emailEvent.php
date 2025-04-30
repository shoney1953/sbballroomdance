<?php
session_start();
require_once '../includes/sendEmailArr.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
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
$emailText = '';
$emailBody = "A Message About Your SBDC Ballroom Dance Event:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$regEmail1 = [];
$toCC2 = ''; 
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$replyTopic = "Event Message";
$emailSubject = "A Message about Your SBDC Ballroom Dance Club Event";
$preface = '';
$rowCount = 0;
if (isset($_POST['submitEventEmail'])) {
    $eventReg->eventid = $_POST['eventId'];
    $result = $eventReg->read_ByEventId($eventReg->eventid);
    $rowCount = $result->rowCount();
  
      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'eventid' => $eventid,
                'eventname' => $eventname,
                'eventdate' => $eventdate,
                'message' => $message,
                'userid' => $userid,
                'email' => $email,
                'paid' => $paid,
                'registeredby' => $registeredby,
                'dateregistered' => $dateregistered
              
            );
            $preface = "Event: ".$reg_item['eventname'].
                    "\n Date: ".$reg_item['eventdate']."\n\r";
            if ($reg_item['email'] != '') {
                $regEmail1[] = array('email' => $reg_item['email'],
                'name' => $reg_item['firstname'].' '.$reg_item['lastname']);
            }
           
           
        } // end while
 
      $replyEmail = htmlentities($_POST['replyEmail']);
      $toCC2 = htmlentities($_POST['replyEmail']); 

      $emailText = strip_tags($_POST['emailBody']);
      $emailText = htmlentities($emailText);
    
      $emailBody = $preface.$emailText;
      $replaceArr  = array("\r\n", "\n", "\r");
      $htmlEmail = str_replace($replaceArr, "<br>", $emailBody);
      
    
        sendEmailArray(
            $regEmail1,  
            $fromCC,
            $fromEmailName,
            $htmlEmail,
            $emailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
         
    } // end if rowcount
}    // end ifset                

$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit;
?>