<?php
//require 'includes/mailheader.php';
require '../includes/PHPMailer.php';
require '../includes/SMTP.php';
require '../includes/Exception.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
include_once '../config/Database.php';
include_once '../models/Contact.php';
$database = new Database();
$db = $database->connect();
$contact = new Contact($db);


if (isset($_POST['submit'])) {
    $contact->firstname = htmlentities($_POST['firstname']);
    $contact->lastname = htmlentities($_POST['lastname']);
    $contact->email = htmlentities($_POST['email']);
    $contact->message = htmlentities($_POST['message']);
    $contact->danceExperience = $_POST['danceexperience'];
    $contact->danceFavorite = $_POST['dancefavorite'];
    $contact->email = filter_var($contact->email, FILTER_SANITIZE_EMAIL);   

    if (filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
        sendEmail($contact->email, $contact->firstname, $contact->lastname);
    } else {
        echo 'Email is empty or Invalid. Please enter valid email.';
    }

   $contact->create();

$redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;
}




function sendEmail($toEmail, $toFirstName, $toLastName)
{
    $toName = $toFirstName.' '.$toLastName;
    $actLink = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
Click to view Activites Calendar</a><br>";
    $mail = new PHPMailer(true);

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
        $mail->addReplyTo('sbdcemailer@gmail.com', 'Information');
        // $mail->addCC('sheila_honey_5@hotmail.com');
        $mail->addBCC('sheila_honey_5@hotmail.com');

        //Attachments
        $mail->addAttachment('img/Membership Form 2022 Dance Club.pdf');         //Add attachments
    

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Thanks for Contacting us at SBDC Ballroom Dance Club!';
        $mail->Body    = "We'd love to have <b>you, $toName </b>, as a new member to our club.<br>
         Please see attached membership form if you are interested.<br>".
         $actLink.
         "<br>Thanks!
         <br>SBDC Ballroom Dance Club";
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    $mail->smtpClose();
   
}

?>