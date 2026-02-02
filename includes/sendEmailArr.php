<?php 
session_start();
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require_once '../config/env.php';
$_SESSION['mailpwd'] = '@20254SbdcDancers';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



function sendEmailArray(
    $toArray, 
    $toCC,
    $fromEmailName,
    $emailBody, 
    $emailSubject,
    $replyEmail,
    $replyTopic,
    $emailAttach,
    $emailAttach2,
    $toCC2,
    $toCC3,
    $toCC4,
    $toCC5
)
{
  
    $mailHost       = 'chi210.greengeeks.net' ;                  //Set the SMTP server to send through
    $mailUsername   = 'sbdcmailer@sbballroomdance.com';                     //SMTP username
    $mailPassword   = $_SESSION['mailpwd'];
    $mailPort       = "587"; 
    // $mailPort       = "465"; 
    $mail = new PHPMailer(true);
    if ($_SERVER['SERVER_NAME'] === 'localhost') {  
    $mail->SMTPOptions = [
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        ]
      ];
    }
   
    try {
        //Server settings
        //  $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $mailHost;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mailUsername;                     //SMTP username
        $mail->Password   = $mailPassword   ;                           //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
        $mail->Port       = $mailPort;   
        // $mail->SMTPDebug = true;  
        $mail->SMTPAutoTLS = false;                                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($mailUsername, $fromEmailName);
       foreach($toArray as $to) {
        // $mail->addAddress($to['email'], $to['name']);     //Add all recipents in array
         if (!stripos($to['email'],'@xxx')) {
          $mail->addBCC($to['email'], $to['name']);     //Add all recipents in array
           }
       }
      
 
        $mail->addReplyTo($replyEmail, $replyTopic);
        if ($toCC) {
            if (!stripos($toCC,'@xxx')) {
                $mail->addCC($toCC);
            }
       
        }
        if ($toCC2) {
         if (!stripos($toCC2,'@xxx')) {
            $mail->addCC($toCC2);
              }
        }
        if ($toCC3) {
            if (!stripos($toCC3,'@xxx')) {
            $mail->addCC($toCC3);
            }
        }
        if ($toCC4) {
            if (!stripos($toCC4,'@xxx')) {
            $mail->addCC($toCC4);
            }
        }
        if ($toCC5) {
              if (!stripos($toCC5,'@xxx')) {
            $mail->addCC($toCC5);
        }
        }
        
       // $mail->addBCC('webmaster@sbballroomdance.com');

        //Attachments
        if ($emailAttach) {
            $mail->addAttachment($emailAttach);         //Add attachments
        }
         if ($emailAttach2) {
            $mail->addAttachment($emailAttach2);         //Add attachments
        } 

        $mail->isHTML(true);   
        
        $mail->Subject = $emailSubject; 
  
        $mail->Body    = $emailBody;
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();

     
     
    } catch (Exception $e) {
        $errMsg = "Message could not be sent: Mailer Error".$mail->ErrorInfo."<br>";
        echo "$errMsg";
        
    }
    $mail->smtpClose();
}

?>