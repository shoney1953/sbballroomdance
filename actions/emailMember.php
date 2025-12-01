<?php

require_once '../includes/sendEmailArr.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);

date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') 
        && ($_SESSION['role'] != 'SUPERADMIN')
        && ($_SESSION['role'] != 'INSTRUCTOR')
        ) {
           if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit; 
        }
       } else {
           if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
       }
}


$emailText = '';
$emailBody = "";
$emailSubject = 'A Message From the SaddleBrooke Dance Club';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$regEmail1 = [];
$toCC2 = ''; 
$toCC3 = 'sbbdcschedule@gmail.com';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$mailAttachment2 = ''; 
$replyTopic = "Message Members from SBDC";
$rowCount = 0;
$regEmail1 = [];

if (isset($_POST['submitEmailMember'])) {
     if (($_POST['emailgroup'] === 'emailByHOA') && (isset($_POST['HOA']))) {
        $result = $user->readByHOAS($_POST['HOA']);
        $rowCount = $result->rowCount();
     } else {
        $result = $user->read();
        $rowCount = $result->rowCount();
     }


      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
            
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'hoa' => $hoa
              
            );
          
             if ($reg_item['email'] != '') {
                    $regEmail1[] = array('email' => $reg_item['email'],
                    'name' => $reg_item['firstname'].' '.$reg_item['lastname']);
               }
           

        } // end while
      if (isset($_POST['emailSubject'])) {
        $emailSubject = $_POST['emailSubject'];
      }

      $replyEmail = htmlentities($_POST['replyEmail']);
      $toCC2 = htmlentities($_POST['replyEmail']); 
      $emailText = strip_tags($_POST['emailBody']);
      $emailText = htmlentities($emailText);
      $emailBody = $emailText;
      $replaceArr  = array("\r\n", "\n", "\r");
      $htmlEmail = str_replace($replaceArr, "<br>", $emailBody);
     
  
        sendEmailArray(
            $regEmail1,  
            $fromCC,
            $fromEmailName,
            $htmlEmail,
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
         
    } // end if rowcount
              
 }   // if submit
$redirect = "Location: ".$_SESSION['returnurl'];
header($redirect);
exit;
?>