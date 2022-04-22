<?php 
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require_once '../config/env.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



function sendEmail($toEmail, 
    $toName,  
    $toCC,
    $fromEmailName,
    $emailBody, 
    $emailSubject,
    $replyEmail,
    $replyTopic,
    $emailAttach,
    $toCC2
)
{
  
    $mailHost       = 'chi120.greengeeks.net' ;                  //Set the SMTP server to send through
    $mailUsername   = 'sbdcmailer@sbballroomdance.com';                     //SMTP username
    $mailPassword   = $_SESSION['mailpwd'];
    $mailPort       = "587"; 
    $mail = new PHPMailer(true);
    try {
        //Server settings
        /* $mail->SMTPDebug = SMTP::DEBUG_SERVER;   */                   //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $mailHost;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mailUsername;                     //SMTP username
        $mail->Password   = $mailPassword   ;                           //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = $mailPort;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($mailUsername, $fromEmailName);
       
        $mail->addAddress($toEmail, $toName);     //Add a recipient
 
        $mail->addReplyTo($replyEmail, $replyTopic);
        if ($toCC) {
            $mail->addCC($toCC);
        }
        if ($toCC2) {
            $mail->addCC($toCC2);
        }
        
       // $mail->addBCC('webmaster@sbballroomdance.com');

        //Attachments
        if ($emailAttach) {
            $mail->addAttachment($emailAttach);         //Add attachments
        }
           

        $mail->isHTML(true);   
        
        $mail->Subject = $emailSubject; 
  
        $mail->Body    = $emailBody;
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();

        echo "Message has been sent<br>";
     
     
    } catch (Exception $e) {
        $errMsg = "Message could not be sent: Mailer Error".$mail->ErrorInfo."<br>";
        echo "$errMsg";
        
    }
    $mail->smtpClose();
}

?>