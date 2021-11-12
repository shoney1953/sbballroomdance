<?php
// include("includes/mailheader.php");
require '../includes/PHPMailer.php';
require '../includes/SMTP.php';
require '../includes/Exception.php';
//Import PHPMailer into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
$events = $_SESSION['upcoming_events'];
include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);


$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following event(s):<br>";
$emailSubject = '';
$eventNum = 0;

$id_int = 0;

if (isset($_POST['submitEventReg'])) {
    $regFirstName = htmlentities($_POST['regFirstName']);
    $regLastName = htmlentities($_POST['regLastName']);
    $regEmail = htmlentities($_POST['regEmail']);  
    $regEmail = filter_var($regEmail, FILTER_SANITIZE_EMAIL); 
    
    $emailSubject = "You have registered for SBDC event(s)";
    foreach($events as $event) {
        $chkboxID = "ev".$event['id'];
       if (isset($_POST["$chkboxID"])) {
        $eventNum = (int)substr($chkboxID,2);
            if ($event['id'] == $eventNum) {
                $eventId = $event['id'];
                $emailBody .= "<br> ".$event['eventname'].
                "    room:    ".$event['eventroom'].
                "on date:    ".$event['eventdate']."<br>"; 
                if ($event['eventcost'] > 0) {
                    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $coststr =  "<br> Member Event Cost is: "
                          .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>";
                    $emailBody .= $coststr;
                }
              
                if ($event['eventform']) {
                   $emailBody .= '<br>There is a form to submit registration details and payment.<br>';
                   $emailBody .= '<br>Please click the form on the website under events.<br>';
                }
                // do the insert(s)
                $eventReg->firstname = $regFirstName;
                $eventReg->lastname = $regLastName;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail;
                if(isset($_SESSION['userid'])) {
                    $eventReg->userid = $_SESSION['userid'];
                } else {
                    $eventReg->userid = 0;
                }
               
                $eventReg->create();
               
                $eventInst->addCount($eventReg->eventid);
            } // end if eventid            
       } //end isset
      } // end foreach
        
    if (filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
       sendEmail($regEmail, $regFirstName,  $regLastName, $emailBody, $emailSubject);
    } else {
        echo 'Registrant Email is empty or Invalid. Please enter valid email.';
    }

   $redirect = "Location: ".$_SESSION['homeurl'];
   header($redirect); 
 exit;
}  else { 
    $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect); 

}// end submit


function sendEmail($toEmail, $toFirstName, $toLastName, $emailBody, $emailSubject)
{
    $mail = new PHPMailer(true);
    $toName = $toFirstName."  ".$toLastName;
    try {
        //Server settings
        /* $mail->SMTPDebug = SMTP::DEBUG_SERVER;   */                   //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'sbdcemailer@gmail.com';                     //SMTP username
        $mail->Password   = '2021SendEmail';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = "587";                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('sbdcemailer@gmail.com', 'SBDC Ballroom Dance Club');
       
        $mail->addAddress($toEmail, $toName);     //Add a recipient
        /*$mail->addAddress('ellen@example.com');               //Name is optional */
        $mail->addReplyTo('sbdcemailer@gmail.com', 'event Registration');
        // $mail->addCC('sheila_honey_5@hotmail.com');
        // $mail->addBCC('sheila_honey_5@hotmail.com');

        //Attachments
        // $mail->addAttachment('img/Membership Form 2022 Dance Club.pdf');         //Add attachments
    

        //Content
          //Set email format to HTML
         

        $mail->isHTML(true);   
        
        $mail->Subject = $emailSubject; 
                      
        
        $mail->Body    = $emailBody;
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();
        echo '<br>Message has been sent<br>';
    } catch (Exception $e) {
        echo "<br>Message could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
    }
    $mail->smtpClose();
}

?>