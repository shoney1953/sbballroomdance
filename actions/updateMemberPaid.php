<?php
session_start();
require_once '../includes/sendEmail.php';
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
            sendThanks($memStat,$userEmail);
          
        }
       
    }


  $redirect = "Location: ".$_SESSION['adminurl']."#membership";
header($redirect);
exit; 
}
function sendThanks($memStat,$userEmail) {

    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $memStat['firstname']." ".$memStat['lastname'] ;
    $mailSubject = 'Thanks for Renewing your membership at SBDC Ballroom Dance Club!';
    $fromCC = "calamitywjs@gmail.com";
    $toCC2 = 'dancedirector@sbballroomdance.com';
    $toCC3 = "webmaster@sbballroomdance.com";
    $toCC4 = '';

    $replyTopic = "Thanks for your renewal!";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = "../img/Member Guide to Website Version 2.pdf"; 

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
       $emailBody .= "Rich Adinolfi, President<br>";
       $emailBody .= "Nan Kartsonis, Vice President<br>"; 
       $emailBody .= "Roger Shamburg, Treasurer<br>";
       $emailBody .= "Jane Sims, Secretary<br>";
       $emailBody .= "Ann Pizzitola, Chair, Dance Instructors<br>";
       $emailBody .= "Vivian Herman, Chair, Volunteer Coordinator<br>";


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
        $toCC4
    );
}
?>