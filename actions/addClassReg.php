<?php

require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';

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

$upcomingClasses = [];
$upcomingClasses = $_SESSION['upcoming_classes'] ;
$users = [];
$users = $_SESSION['regUsers'] ;
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);
$emailBody = "Thanks for registering for the following SBDC classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$result = 0;
$fromCC = 'sbbdcschedule@gmail.com';
$replyEmail = 'sbbdcschedule@gmail.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$replyEmail = 'annzabinski@gmail.com';
$replyTopic = "SBDC Class Registration";
$emailSubject = "Your instructor has registered you for selected SBDC Classes";
$usersSelected = 0;


if (isset($_POST['submitAddReg'])) {
    if (isset($_POST['classid'])) {
        $danceClass->id = $_POST['classid'];
        $danceClass->read_single();

            foreach($users as $usr) {
             $usrID = "us".$usr['id'];     
             if (isset($_POST["$usrID"])) {                         
                    $classReg->classid = $danceClass->id ;      
                    $classReg->firstname = $usr['firstname'];
                    $classReg->lastname = $usr['lastname'];
                    $classReg->email = $usr['email'];
                    $classReg->userid = $usr['id'];
                    $classReg->classname = $danceClass->classname;
                    $classReg->classtime = $danceClass->time;
                    $classReg->classdate = $danceClass->date;
                    $classReg->registeredby = $_SESSION['username'];
             
                    
                    $result = $classReg->checkDuplicate($classReg->email, $classReg->classid);
                 if (!$result) {
                    $classReg->create();
                    $danceClass->addCount($classReg->classid);
                    $danceClass->id = $classReg->classid;
                    $danceClass->read_single();
                    
                    $emailBody .= "<br> ".$danceClass->classlevel."  ".$danceClass->classname.
                    "<br>   Instructor(s):   ".$danceClass->instructors.
                    "<br>   Registration Email:   ".$danceClass->registrationemail.
                    "<br>   Room:    ".$danceClass->room.
                    "<br>   Beginning on date:    ".date('M d Y',strtotime($danceClass->date)).
                    "<br>   Time: ".date('h:i:s A', strtotime($danceClass->time))."<br>"; 
                    $regFirstName1 = $usr['firstname'];
                    $regLastName1 = $usr['lastname'];
                    $regEmail1 = $usr['email'];
                       

           $emailBody .= '<br>If you need to correspond with the instructor(s), 
           please use the registration email for the appropriate class.';
           $emailBody .= '<br>Note: You can also see these classes from your profile on the website.';
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

            } else {
                echo 'Member Email is empty or Invalid. Please enter valid email.';
            }
            $emailBody = "Thanks for registering for the following classes:<br>";
        } // check duplicate
        } //end isset usrid
     } // end foreach user
       

}
}
$redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;

?>