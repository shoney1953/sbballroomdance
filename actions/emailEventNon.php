<?php
session_start();
require_once '../includes/sendEmailArr.php';
require_once '../includes/siteemails.php';
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
$comEmail1 = '';
$comEmail2 = '';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$event = new Event($db);
$user = new User($db);
$emailText = '';
$emailBody = "A Message About an Upcoming SBDC Ballroom Dance Event:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$regEmail1 = [];
$regArray = [];
$userArray = [];
$resultArray = [];
$toCC2 = ''; 
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$replyTopic = "Event Message";
$emailSubject = "A Message about an Upcoming SBDC Ballroom Dance Club Event";
$preface = '';
$rowCount = 0;
if (isset($_POST['submitEventEmail'])) {
  if (isset($_POST['subject'])) {
    $emailSubject = $_POST['subject'];
  }
    $event->id = $_POST['eventId'];
    $event->read_single();

      $result = $user->read();
     $rowCount = $result->rowCount();

      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email
             
            );
            array_push($userArray, $user_item);

        } // end while
      }

      foreach ($userArray as $us) {
     
           if ($eventReg->read_ByEventIdUser($_POST['eventId'], $us['id'])) {
             // person is registered
         
           } else {
      
            $preface = "Event: ".$event->eventname.
                    "\n Date: ".$event->eventdate."\n\r";
            if ($us['email'] != '') {
                $regEmail1[] = array('email' => $us['email'],
                'name' => $us['firstname'].' '.$us['lastname']);
           }

        }
      }
      // var_dump($regEmail1);
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
         
 
}    // end ifset                

$redirect = "Location: ".$_SESSION['returnurl'];
header($redirect);
exit;
?>