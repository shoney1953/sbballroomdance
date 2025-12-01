<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
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

if (isset($_POST['submitDeleteReg'])) {
  foreach ($regs as $reg) {

    $delID = "del".$reg['id'];

   if (isset($_POST["$delID"])) {
    $regFirstName1 = $reg['firstname'];
    $regLastName1 = $reg['lastname'];
    $regEmail1 = $reg['email'];;
    $emailBody .= "<br>NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
    $emailBody .= 
                "<br>Class Level: ".$reg['classdate'].
                "<br>Class Name:  ".$reg['classname'];
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
    $classReg->id = $reg['id'];
    $classid = $reg['classid'];
    $classReg->delete();
    
    $danceClass->decrementCount($classid);
   }
  }
}
  

        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
  
   

?>