<?php
require_once '../includes/sendEmail.php';
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
$emailBody = "Thanks for registering for the following classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'webmaster@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = '';
$mailAttachment = "";
$replyTopic = "Class Registration";
$regId1 = 0;
$regId2 = 0;

if (isset($_POST['submitRegClass'])) {
    $regFirstName1 = htmlentities($_POST['regFirstName1']);
    $regLastName1 = htmlentities($_POST['regLastName1']);
    $regEmail1 = htmlentities($_POST['regEmail1']);
    if ($user->getUserName($regEmail1)) {    

             $regId1 = $user->id;
        }

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

    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    
   
    foreach($classes as $class) {
       
        $chkboxID = "cb".$class['id'];
        $insEmail = $class['registrationemail'];
        
        if (isset($_POST["$chkboxID"])) {
            $numRegClasses++;
            $regSelected += [$chkboxID => $insEmail]; 
        } //end isset
     } // end foreach

  
     
    $emailSubject = "You have registered for selected Classes";
      
     foreach($regSelected as $key => $reg) {
      
        $id_int = (int)(substr($key,2));
      
        foreach ($classes as $class) {
            if ($class['id'] == $id_int) {
                $classId = $class['id'];
                $emailBody .= "<br> ".$class['classlevel']."  ".$class['classname']."    Instructor(s):   ".
                $class['instructors']."    room:    ".$class['room'].
                "   beginning on date:    ".date('M d Y',strtotime($class['date']))."  time: ".$class['time']."<br>"; 
                // do the insert(s)
                $classReg->firstname = $regFirstName1;
                $classReg->lastname = $regLastName1;
                $classReg->classid = $classId;
                $classReg->email = $regEmail1;
                if(isset($_SESSION['userid'])) {
                    $classReg->userid = $_SESSION['userid'];
                } else {
                    $classReg->userid = $regId1;
                }
               
               
            
                $classReg->create();
                $danceClass->addCount($classId);
                if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                
                    $classReg->firstname = $regFirstName2;
                    $classReg->lastname = $regLastName2;
                    $classReg->classid = $classId;
                    $classReg->email = $regEmail2;
                    $classReg->userid = $regId2;
                    if ($regId1 === 0) {
                        $emailBody .=
                     "<br> We didn't find your email. So please sign up, or you may attend only 1 class free before joining the club.<br>";
                    }
                    $classReg->create();
                    $danceClass->addCount($classId);
                     } // end regemail2
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
            $toCC2
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
            $toCC2

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
                         $toCC2
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