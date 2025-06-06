<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
  
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y");  
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

if (isset($_POST['thisyear'])) {
    $memberStatus = $_SESSION['memberStatus1'];
}
if (isset($_POST['nextyear'])) {
    $memberStatus = $_SESSION['memberStatus2'];

}



$database = new Database();
$db = $database->connect();
$memberPaid = new MemberPaid($db);
$user = new User($db);
$userEmail = '';


if (isset($_POST['updateMemPaid'])) {

    foreach ($memberStatus as $memStat) {
  
        $ckboxId = "pd".$memStat['id'];
        if (isset($_POST["$ckboxId"])) {
            $memberPaid->update_paid($memStat['id']);
            $user->id = $memStat['userid'];
            $user->read_single();
            $userEmail = $user->email;
            sendThanks($memStat,$userEmail,$secretary,$president,$webmaster);
          
        }
       
    }


  $redirect = "Location: ".$_SESSION['adminurl']."#membership";
header($redirect);
exit; 
}
function sendThanks($memStat,$userEmail,$secretary,$president,$webmaster) {

    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $memStat['firstname']." ".$memStat['lastname'] ;
    $mailSubject = 'Thanks for Renewing your membership at SBDC Ballroom Dance Club!';
    $fromCC = $secretary;
    $toCC2 = $president;
    $toCC3 = $webmaster;
    $toCC4 = '';
    $toCC5 = null;

    $replyTopic = "Thanks for your renewal!";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = ""; 

       $emailBody = "<br>Welcome renewing member <b>$toName </b>
       to the SaddleBrooke Ballroom Dance Club.<br><br>";
       $emailBody .= "Thanks for renewing your membership. We hope you'll 
       continue to enjoy our activites.<br>";
       $emailBody .= "Just as a reminder please click the link to our 
         activity calendar to view the latest updates to events and classes:<br> $actLink";
       $emailBody .= "Our Website address is https://sbballroomdance.com.<br><br>";
       $emailBody .= "<br>We hope to see you soon!<br>";
       require '../includes/emailSignature.php';

       sendEmail(
        $userEmail, 
        $toName, 
        $fromCC,
        $fromEmailName,
        $emailBody,
        $mailSubject,
        $replyEmail,
        $replyTopic,
        $mailAttachment,
        $toCC2,
        $toCC3,
        $toCC4,
        $toCC5
    );
}
?>