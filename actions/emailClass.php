<?php
session_start();
require_once '../includes/sendEmailArr.php';
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
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
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$class = new DanceClass($db);
$emailText = '';
$emailBody = "";
$emailSubject = 'A Message About Your SBDC Ballroom Dance Class';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;
$fromCC = 'webmaster@sbballroomdance.com';
$replyEmail = 'dancedirector@sbballroomdance.com';
$fromEmailName = 'SBDC Ballroom Dance Club';
$regEmail1 = [];
$toCC2 = ''; 
$mailAttachment = ''; 
$replyTopic = "Class Message";
$rowCount = 0;
$preface = '';
    $classReg->classid = $_POST['classId'];
    $result = $classReg->read_ByClassId($classReg->classid);
    $rowCount = $result->rowCount();

      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'classid' => $classid,
                'classname' => $classname,
                'classdate' => $classdate,
                'classtime' => $classtime,
                'userid' => $userid,
                'email' => $email
              
            );
            $preface = "Class: ".$reg_item['classname'].
            "\n Date: ".$reg_item['classdate'].
            "\n Time: ".date('h:i:s A', strtotime($reg_item['classtime']))."\n\r"
            ;
            $regEmail1[] = array('email' => $reg_item['email'],
             'name' => $reg_item['firstname'].' '.$reg_item['lastname']);
        } // end while
      
      $replyEmail = htmlentities($_POST['replyEmail']);
      $toCC2 = htmlentities($_POST['replyEmail']); 
      $emailText = strip_tags($_POST['emailBody']);
      $emailText = htmlentities($emailText);
      $emailBody = $preface.$emailText;
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
            $toCC2
        );
         
    } // end if rowcount
              

$redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;
?>