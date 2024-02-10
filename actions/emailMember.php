<?php
session_start();
require_once '../includes/sendEmailArr.php';
require_once '../config/Database.php';
require_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);

date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') 
        && ($_SESSION['role'] != 'SUPERADMIN')
        && ($_SESSION['role'] != 'INSTRUCTOR')
        ) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}


$emailText = '';
$emailBody = "";
$emailSubject = 'A Message From the SaddleBrooke Dance Club';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'webmaster@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$regEmail1 = [];
$toCC2 = ''; 
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$replyTopic = "Message from SBDC";
$rowCount = 0;
$regEmail1 = [];

  
    $result = $user->readByHOA();
    $rowCount = $result->rowCount();

      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
            
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'hoa' => $hoa
              
            );
          
            if (isset($_POST['HOA']) && isset($_POST['emailByHOA'])){
               if ($_POST['HOA'] === $hoa) {
        
                if ($reg_item['email'] != '') {
                    $regEmail1[] = array('email' => $reg_item['email'],
                    'name' => $reg_item['firstname'].' '.$reg_item['lastname']);
               }
            }
            } else {
                if (isset($_POST['emailAll']))
                if ($reg_item['email'] != '') {
                    $regEmail1[] = array('email' => $reg_item['email'],
                    'name' => $reg_item['firstname'].' '.$reg_item['lastname']);
                   }
           
            }

        } // end while
     
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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
         
    } // end if rowcount
              
     
$redirect = "Location: ".$_SESSION['adminurl']."#members";
header($redirect);
exit;
?>