<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/Visitor.php';

date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && 
            ($_SESSION['role'] != 'SUPERADMIN') &&
            ($_SESSION['role'] != 'INSTRUCTOR')
        ) {
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
$classReg = new ClassRegistration($db);
$class = new DanceClass($db);
$visitor = new Visitor($db);

$emailBody = "Thanks for registering for the following classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'webmaster@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$mailAttachment = '../img/Membership Form 2023 Dance Club.pdf'; 
$replyTopic = "Class Registration";
$emailSubject = "Your instructor has registered you for selected Classes";
$actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
$webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";

if (isset($_POST['submitAddVisitorReg'])) {
 
    $classReg->classid = $_POST['classid'];
    $classReg->firstname = $_POST['firstname'];
    $classReg->lastname = $_POST['lastname'];
    $classReg->email = $_POST['email'];
    $classReg->paid = 0;
    $classReg->userid = 0;
 
    $classReg->create();
    $class->addCount($classReg->classid);
    $class->id = $classReg->classid;
    $class->read_single();
    $emailBody .= "You are registered as a visitor for: <br>";
    $emailBody .= "<br> ".$class->classlevel."  ".$class->classname.
                "<br>   Instructor(s):   ".$class->instructors.
                "<br>   Registration Email:   ".$class->registrationemail.
                "<br>   Room:    ".$class->room.
                "<br>   Beginning on date:    ".date('M d Y',strtotime($class->date)).
                "<br>  Time: ".date('h:i:s A', strtotime($class->time))."<br>"; 
    $emailBody .= "<br>If you need to communicate with the instructor(s), please use the registration email.<br>";
    $emailBody .= "<br>We hope you'll enjoy your experience and consider joining our club.<br>";
    $replyEmail = $class->registrationemail;
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
    $visitor->firstname = $_POST['firstname'];
    $visitor->lastname = $_POST['lastname'];
    $visitor->notes = $_POST['notes'];
    if ($visitor->read_ByEmail($visitor->email)) {
        $visitor->firstname = $_POST['firstname'];
        $visitor->lastname = $_POST['lastname'];
        $visitor->notes = $_POST['notes'];
        $visitor->update($visitor->email);
    } else {
        echo 'visitor not found',$visitor->email,"<br>>";
        $visitor->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
        $visitor->firstname = $_POST['firstname'];
        $visitor->lastname = $_POST['lastname'];
        $visitor->notes = $_POST['notes'];
        $visitor->create();
    }
 
    /* send email */
    $regFirstName1 = htmlentities($_POST['firstname']);
    $regLastName1 = htmlentities($_POST['lastname']);
    $regEmail1 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  
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
    } else {
        echo 'Visitor Email is empty or Invalid. Please enter valid email.';
    }

}  
   


$redirect = "Location: ".$_SESSION['adminurl']."#classregistrations";
header($redirect);
exit;

?>