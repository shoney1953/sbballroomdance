<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';


$database = new Database();
$db = $database->connect();
$reg = new ClassRegistration($db);
$partnerReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);

$regs = $_SESSION['classregistrations'];
$regSelected = [];
$regAll = '';
$emailBody = "Your Class Registration has been removed:<br>";
$emailSubject = 'SBDC Class Registration Removed';
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
  $gotClassReg = 0;
  $gotPartnerClassReg = 0;

if (isset($_POST['submitRemoveRegs'])) {
   $danceClass->id = $_POST['classid'];
   $danceClass->read_single();

  if ($reg->read_ByClassIdUser($_POST['classid'],$_SESSION['userid'])) {
      $gotClassReg = 1;
               
    }
                
    if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
      if ($partnerReg->read_ByClassIdUser($_POST['classid'],$_SESSION['partnerid'])) {
          $gotPartnerClassReg = 1;
      }
    }

   if (isset($_POST['remID1'])) {
      if (isset($_POST['remid2'])) {
        $toCC2 = $_SESSION['partneremail'];
      }
    $regFirstName1 = $reg->firstname;
    $regLastName1 = $reg->lastname;
    $regEmail1 = $reg->email;
    $emailBody .= "<br>NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
    if (isset($_POST['remid2'])) {
        $toCC2 = $_SESSION['partneremail'];
         $emailBody .= "<br>PARTNER NAME: ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']."<br>    EMAIL:  ".$_SESSION['partneremail']."<br>";
      }
    $emailBody .= 
                "<br>Class Date:  ".$danceClass->date.
                "<br>Class Level: ".$danceClass->classlevel.
                "<br>Class Name:  ".$danceClass->classname;
            
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
        } else {
            echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
        }
   /*********************************************** */

    $reg->delete(); 
    $danceClass->decrementCount($_POST['classid']);
    if (isset($_POST['remID2'])) {
 
       $partnerReg->delete(); 
       $danceClass->decrementCount($_POST['classid']); 
    }
   } else if (isset($_POST['remID2'])) {
    $regFirstName1 = $partnerReg->firstname;
    $regLastName1 = $partnerReg->lastname;
    $regEmail1 = $partnerReg->email;
    $emailBody .= "<br>NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
    $toCC2 = $_SESSION['useremail'];

    $emailBody .= 
                "<br>Class Date:  ".$danceClass->date.
                "<br>Class Level: ".$danceClass->classlevel.
                "<br>Class Name:  ".$danceClass->classname;
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
        } else {
            echo 'Registrant 2 Email is empty or Invalid. Please enter valid email.';
        }
   /*********************************************** */
    $partnerReg->delete(); 
    $danceClass->decrementCount($_POST['classid']);
   }
  }


        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
  
   

?>