<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
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


if (isset($_POST['updateMemPaid'])) {

    foreach ($memberStatus as $memStat) {
  
        $ckboxId = "pd".$memStat['id'];
        if (isset($_POST["$ckboxId"])) {
            $memberPaid->update_paid($memStat['id']);
            if (isset($_POST['nextyear'])) {
                sendThanks($memStat);
            }
        }
       
    }


 $redirect = "Location: ".$_SESSION['adminurl']."#membership";
header($redirect);
exit;
}
function sendThanks($memStat) {
    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $memStat['firstname']." ".$memStat['lastname'] ;
    $mailSubject = 'Thanks for Renewing your membership at SBDC Ballroom Dance Club!';
    $toCC2 = ' ';
    $replyTopic = "Welcome";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = "../img/Intro.pdf"; 
       $fromCC = "sbbdcschedule@gmail.com";
       $emailBody = "<br>Welcome renewing member <b>$toName </b>
       to the SaddleBrooke Ballroom Dance Club.<br><br>";
       $emailBody .= "Thanks for renewing your membership. We hope you'll 
       continue to enjoy our activites.<br>";
       $emailBody .= "Just as a reminder please check the link to our 
         activity calendar:<br> $actLink";
       $emailBody .= "Our Website address is https://sbballroomdance.com.<br><br>";
       $emailBody .= "<br>We hope to see you soon!<br>";


       $emailBody .= "----------------------------<br>";
       $emailBody .= "<em>SaddleBrooke Ballroom Dance Club Board Members</em><br>";
       $emailBody .= "Brian Hand, President<br>";
       $emailBody .= "Rich Adinolfi, Vice President<br>"; 
       $emailBody .= "Dottie Adams, Treasurer<br>";
       $emailBody .= "Wanda Ross, Secretary<br>";
       $emailBody .= "Roger Shamburg, Chair, Dance Instructors<br>";
       $emailBody .= "Rick Baumgartner, Chair, DJs<br>";


       sendEmail(
        $memStat['email'], 
        $toName, 
        $fromCC,
        $fromEmailName,
        $emailBody,
        $mailSubject,
        $replyEmail,
        $replyTopic,
        $mailAttachment,
        $toCC2
    );
}
?>