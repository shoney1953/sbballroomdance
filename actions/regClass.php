<?php
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';
session_start();
$classes = $_SESSION['upcoming_classes'];
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);
$user = new User($db);

$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following SBDC classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = "";
$replyTopic = "SBDC Class Registration";
$regId1 = 0;
$regId2 = 0;
$create_successful = 0;
$result = 0;
 
if (isset($_POST['submitRegClass'])) {
   
    $regFirstName1 = htmlentities($_POST['regFirstName1']);
    $regLastName1 = htmlentities($_POST['regLastName1']);
    $regEmail1 = htmlentities($_POST['regEmail1']);
    if ($user->getUserName($regEmail1)) {    

             $regId1 = $user->id;
        }
    $regEmail2 = '';
    if (isset($_POST['regFirstName2']) || isset($_POST['regEmail2'])) {

        $regFirstName2 = htmlentities($_POST['regFirstName2']);
        $regLastName2 = htmlentities($_POST['regLastName2']);
        $regEmail2 = htmlentities($_POST['regEmail2']);
        $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
        if ($user->getUserName($regEmail2)) {    
    
            $regId2 = $user->id;
       }  
    
        if (isset($_POST['message2ins'])) {
            $message2Ins = $_POST['message2ins'];
          }
    }

    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    
   
    foreach($classes as $class) {
       
        $chkboxID = "cb".$class['id'];
        $insEmail = $class['registrationemail'];
        
        if (isset($_POST["$chkboxID"])) {
            $numRegClasses++;
            $regSelected += [$chkboxID => $insEmail]; 
        } //end isset
     } // end foreach

      if ($numRegClasses === 0) {
       
            $redirect = "Location: ".$_SESSION['regclassurl'].'?error=No Classes Selected Please Check at least 1 class and resubmit.';
            header($redirect);
            exit; 
    }
     
    $emailSubject = "You have registered for selected Classes";
      
     foreach($regSelected as $key => $reg) {
      
        $id_int = (int)(substr($key,2));
      
        foreach ($classes as $class) {
            if ($class['id'] == $id_int) {
                $classId = $class['id'];
                $emailBody .= '**************************************';
                $emailBody .= 
                "<br>Class Level: ".$class['classlevel'].
                "<br>Class Name:  ".$class['classname'].
                "<br>Instructor(s):   ".$class['instructors'].
                "<br>Room:    ".$class['room'].
                "<br>Start Date:    ".date('M d Y',strtotime($class['date'])).
                "<br>Start Time: ".date('h:i:s A', strtotime($class['time']))."<br>"; 
                // do the insert(s)
                $classReg->firstname = $regFirstName1;
                $classReg->lastname = $regLastName1;
                $classReg->classid = $classId;
                $classReg->email = $regEmail1;
                if ($_SESSION['role'] != 'visitor') {
                    $classReg->registeredby = $_SESSION['username'];
                } else {
                    $classReg->registeredby = $_SESSION['visitorfirstname'];
                }

        
                if(isset($_SESSION['userid'])) {
                    $classReg->userid = $_SESSION['userid'];
                } else {
                    $classReg->userid = $regId1;
                }
                $result = $classReg->checkDuplicate($regEmail1, $classId);
                 if ($result) {
                        $redirect = "Location: ".$_SESSION['regclassurl'].'?error=Duplicate Registration Email1 Please check your profile.';
                        header($redirect);
                        exit; 
                }
     
                if (!$result) {

                $classReg->create();  
                $danceClass->addCount($classId);
                if ($_SESSION['role'] != 'visitor') {
                if ($regEmail2 != '') {
                if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                
                    $classReg->firstname = $regFirstName2;
                    $classReg->lastname = $regLastName2;
                    $classReg->classid = $classId;
                    $classReg->email = $regEmail2;
                    $classReg->userid = $regId2;
                    $classReg->registeredby = $_SESSION['username'];
                    if ($regId1 === 0) {
                        $emailBody .=
                     "<br> We didn't find your email. So please sign up, or you may attend only 1 class free before joining the club.<br>";
                    }
                    $result = $classReg->checkDuplicate($regEmail2,$classId);
                    if (!$result) {
                        $classReg->create();
                        $danceClass->addCount($classId);
                    }
                    if ($result) {
                        $redirect = "Location: ".$_SESSION['regclassurl'].'?error=Duplicate Registration Email 2 Please check your profile.';
                        header($redirect);
                        exit; 
                }

                     } // end regemail2
                    }
                }
                }
            } // end if classid
        } // end foreach

       
    } // end foreach

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
        echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
    }
 
  
    if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
      
        $regName2 = $regFirstName2.' '.$regLastName2;
  
        sendEmail(
            $regEmail2, 
            $regName2, 
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
    } 


    $emailSubject = "People have Signed up for your upcoming Class";


    foreach ($regSelected as $key => $reg) {
    
            $id_int = (int)(substr($key,2));
          
            foreach ($classes as $class) {
                $emailBody = "The following individuals have signed up for the class you are going to teach: <br>";
  
                if ($message2Ins) {
                    $emailBody .= "<br>Their Message to the instructor(s) is: ".$message2Ins."<br><br>";
                }
                $emailBody .= "NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
             
           
                $classString = '';
                $classString = "<br>Class: ".$class['classname']."<br>";

                if ($class['id'] == $id_int) {
                    $insEmail = $class['registrationemail'];   
                     $insEmail2 = "";
                     $insEmail3 = "";
                     $emailBody .= $classString;
                
                     $regname = " ";
  
                     sendEmail(
                         $insEmail, 
                         $regname, 
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
                     $classString = '';
                  
                }
            } // end foreach class
        } //end foreach regselected

   $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect);
 exit;
} // end submit




?>
