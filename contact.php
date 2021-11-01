<?php
//require 'includes/mailheader.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    /* if in local testing mode */
    $server = "localhost";
    $username = "root";
    $password = "2021Idiot";
    $db = "mywebsite"; 
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
      /*Get Heroku ClearDB connection information */
$url      = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
}
$conn = new mysqli($server, $username, $password, $db);


// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error."</p>");
} 



$name = 'Guest';


if (isset($_POST['submit'])) {
    $firstname = htmlentities($_POST['firstname']);
    $lastname = htmlentities($_POST['lastname']);
    $email = htmlentities($_POST['email']);
    $message = htmlentities($_POST['message']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);   

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendEmail($email, $firstname, $lastname);
    } else {
        echo 'Email is empty or Invalid. Please enter valid email.';
    }
    
  $sql = "INSERT INTO contacts (firstname, lastname, email, message)
     VALUES ('$firstname', '$lastname', '$email', '$message')";
   $result = $conn->query($sql);
   
}
$conn->close();
header('Location: ', $_SESSION['homeurl']);
exit;

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