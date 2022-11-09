<?php
session_start();
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
$emailBody = "Thanks for registering for the following classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'webmaster@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$mailAttachment = ''; 
$replyEmail = 'dancedirector@sbballroomdance.com';
$replyTopic = "Class Registration";
$emailSubject = "Your instructor has registered you for selected Classes";
$usersSelected = 0;

if (isset($_POST['submitAddReg'])) {
   
         
            foreach($users as $usr) {
                $usrID = "us".$usr['id'];
          
                if (isset($_POST["$usrID"])) {
                foreach($upcomingClasses as $class) {
       
                        $chkboxID = "cb".$class['id'];
                     
                        
                     if (isset($_POST["$chkboxID"])) {
                    $classReg->classid = $class['id'];       
                    $classReg->firstname = $usr['firstname'];
                    $classReg->lastname = $usr['lastname'];
                    $classReg->email = $usr['email'];
                    $classReg->userid = $usr['id'];
                    $classReg->classname = $class['classname'];
                    $classReg->classtime = $class['time'];
                    $classReg->classdate = $class['date'];
                    
                    $classReg->create();
                    $danceClass->addCount($classReg->classid);
                    $danceClass->id = $classReg->classid;
                    $danceClass->read_single();
                    
                    $emailBody .= "<br> ".$danceClass->classlevel."  ".$danceClass->classname.
                    "<br>   Instructor(s):   ".$danceClass->instructors.
                    "<br>   Registration Email:   ".$danceClass->registrationemail.
                    "<br>   Room:    ".$danceClass->room.
                    "<br>   Beginning on date:    ".date('M d Y',strtotime($danceClass->date)).
                    "<br>  Time: ".date('h:i:s A', strtotime($danceClass->time))."<br>"; 
                    $regFirstName1 = $usr['firstname'];
                    $regLastName1 = $usr['lastname'];
                    $regEmail1 = $usr['email'];
                       
                }
             
            } // end foreach class
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
                    $toCC2
                    
                );

            } else {
                echo 'Member Email is empty or Invalid. Please enter valid email.';
            }
            $emailBody = "Thanks for registering for the following classes:<br>";
        } //end isset
     } // end foreach user
       
}
 /*   $redirect = "Location: ".$_SESSION['adminurl']."#classregistrations";
   header($redirect);
exit;
 */
?>