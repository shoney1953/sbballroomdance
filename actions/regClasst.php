<?php
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';

$classes = $_SESSION['upcoming_classes'];
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$partnerclassReg = new ClassRegistration($db);
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
$mailAttachment2 = "";
$replyTopic = "SBDC Class Registration";
$regId1 = 0;
$regId2 = 0;
$create_successful = 0;
$result = 0;
$regEmail1 = '';
$regEmail2 = '';
$regFirstName1 = '';
$regFirstName2 = '';
$regLastName1 = '';
$regLastName2 = '';
$classId = 0;
$reg1Dup = 'N';
$reg2Dup = "N";


if (!isset($_POST['submitAddRegs'])) {
       $redirect = "Location: ".$_SESSION['homeurl'];
       header($redirect);
        exit;
}
   $classId = $_POST['classid'];
   $danceClass->id = $_POST['classid'];
   $danceClass->read_single();

   if (isset($_POST['mem1Chk'])) {
    $regFirstName1 = htmlentities($_POST['firstname1']);
    $regLastName1 = htmlentities($_POST['lastname1']);
    $regEmail1 = htmlentities($_POST['email1']); 
    if ($user->getUserName($_POST['email1'])) {    
             $regId1 = $user->id;
        }
   
   }

    $regEmail2 = '';
      if (isset($_POST['mem2Chk'])) {
      
        $regFirstName2 = htmlentities($_POST['firstname2']);
        $regLastName2 = htmlentities($_POST['lastname2']);
        $regEmail2 = htmlentities($_POST['email2']);
        $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
        if ($user->getUserName($_POST['email2'])) {    
            $regId2 = $user->id;
       }  

    }
    
    if (isset($_POST['message2ins'])) {
            $message2Ins = $_POST['message2ins'];
          }


    $emailSubject = "You have registered SBDC dance class: ".$danceClass->classname;
      
                $classId = $danceClass->id;
                $emailBody .= '**************************************';
                $emailBody .= 
                "<br>Class Level: ".$danceClass->classlevel.
                "<br>Class Name:  ".$danceClass->classname.
                "<br>Instructor(s):   ".$danceClass->instructors.
                "<br>Room:    ".$danceClass->room.
                "<br>Start Date:    ".date('M d Y',strtotime($danceClass->date)).
                "<br>Start Time: ".date('h:i:s A', strtotime($danceClass->time))."<br>"; 
                // do the insert(s)
               if (isset($_POST['mem1Chk'])) {
                $classReg->firstname = $regFirstName1;
                $classReg->lastname = $regLastName1;
                $classReg->classid = $classId;
                $classReg->email = $regEmail1;
                if ($_SESSION['role'] !== 'visitor') {
                   $classReg->userid = $_SESSION['userid'];
                } else {
                   $classReg->userid = 0;
                }
  
                $classReg->registeredby = $_SESSION['username'];
                 if (!$classReg->checkDuplicate($regEmail1, $classID))  {
                  $classReg->create();  
                  $danceClass->addCount($classId);
                      } else {
                        $emailBody .= "<br>".$regFirstName1." ".$regLastName1." with email ".$regEmail1." was previously registered!<br>";
                        $reg1Dup = 'Y';
                      }

               }

               if (isset($_POST['mem2Chk'])) {
          
                    $partnerclassReg->firstname = $regFirstName2;
                    $partnerclassReg->lastname = $regLastName2;
                    $partnerclassReg->classid = $classId;
                    $partnerclassReg->email = $regEmail2;
                    $partnerclassReg->userid = $_SESSION['partnerid'];
                    $partnerclassReg->registeredby = $_SESSION['username'];
                   if (!$classReg->checkDuplicate($regEmail2, $classID))  {
                   $partnerclassReg->create();
                    $danceClass->addCount($classId);
                      } else {
                        $emailBody .= "<br>".$regFirstName2." ".$regLastName2." with email ".$regEmail2." was previously registered!<br>";
                        $reg2Dup = 'Y';
                      }
 
                }

            
                
 
    if (isset($_POST['mem1Chk'])) {
      if (isset($_POST['mem2Chk'])) {
        $toCC2 = $regEmail2;
             
      }
          
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
              $mailAttachment2,
              $toCC2,
              $toCC3,
              $toCC4,
              $toCC5
          );
    } 
  } elseif (isset($_POST['mem2Chk'])) {
   
        $toCC2 = $_SESSION['useremail'];
     
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
            $mailAttachment2,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    } 
  }
  
  

   $toCC2 = $danceClass->registrationemail;

    $emailSubject = "People have Signed up for your upcoming Class";


            $emailBody = "The following individuals have signed up for the class you are going to teach: ".$danceClass->classname."<br>";

            if ($message2Ins) {
                $emailBody .= "<br>Their Message to the instructor(s) is: ".$message2Ins."<br><br>";
            }
            if (isset($_POST['mem1Chk'])) {
              $emailBody .= "NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
              if ($reg1Dup === 'Y') {
                 $emailBody .= "NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1." was previously registered. <br>";
              }
            }
            if (isset($_POST['mem2Chk'])) {
              $emailBody .= "NAME: ".$regFirstName2." ".$regLastName2."<br>    EMAIL:  ".$regEmail2."<br>";
                 if ($reg2Dup === 'Y') {
                     $emailBody .= "NAME: ".$regFirstName2." ".$regLastName2."<br>    EMAIL:  ".$regEmail2." was previously registered.<br>";
                 }
            }

            $classString = '';
            $classString = "<br>Class: ".$danceClasslass->classname."<br>";

                $insEmail = $danceClass->registrationemail;   
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
                      $mailAttachment2,
                      $toCC2,
                      $toCC3,
                      $toCC4,
                      $toCC5
                  );
                  $classString = '';
              

   $redirect = "Location: ".$_SESSION['returnurl'];
     header($redirect);
 exit;



?>
