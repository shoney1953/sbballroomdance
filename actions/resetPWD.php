<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/PwdReset.php';
date_default_timezone_set("America/Phoenix");
$homeUrl = $_SESSION['homeurl'];

$database = new Database();
$db = $database->connect();
$user = new User($db);
$pwdReset = new PwdReset($db);
$selector = null;
$validator = null;
$token = null;
$url = null;
$expirationDate = null;
$emailBody = null;

$fromCC = 'webmaster@sbballroomdance.com';

if (isset($_POST['SubmitResetPwd'])) {
  
  if ($user->validate_email($_POST['email'])) {
    $pwdReset->pwdResetEmail = $_POST['email'];
    $pwdReset->deleteByEmail();
    //  first remove anything hanging around in the file with this email
  $selector = bin2hex(random_bytes(8));
  // now set up to enter new record
  $token = random_bytes(32);
  $validator = bin2hex($token);
  $hashedToken = password_hash($token,PASSWORD_DEFAULT);
  if ($_SERVER['SERVER_NAME'] === 'localhost' ) {
    $url = "http://localhost/sbdcballroomdance/";
    $url .= "createNewPassword.php?selector=".$selector."&validator=".$validator."";
  } 
  if (($_SERVER['SERVER_NAME'] === "www.sbballroomdance.com")
      || ($_SERVER['SERVER_NAME'] === "sbballroomdance.com") ) {
        $url = "https://sbballroomdance.com/";
        $url .= "createNewPassword.php?selector=".$selector."&validator=".$validator."";
      }
 

  $currentDate = new DateTime();
  $expirationDate = $currentDate->format('U') + 1800;

  $pwdReset->pwdResetEmail = $_POST['email'];
  $pwdReset->pwdResetToken = $hashedToken;
  $pwdReset->pwdResetSelector = $selector;
  $pwdReset->pwdResetExpiration = $expirationDate;

  $pwdReset->create();
  // set up for email
  $fromEmailName = 'SBDC Ballroom Dance Club';
  $toName = $user->email;
  $mailSubject = 'SBDC Forgotten Password';
  $mailAttachment = null;
  $toCC2 = null;
  $replyTopic = "Forgotten Password";
  $replyEmail = 'webmaster@sbballroomdance.com';
  $emailBody = "<p>We received a request to reset your password on the SaddleBrooke Ballroom Dance Club site. Please ignore this email if you did not make the request.</p><br>";
  $emailBody .= "<p>Please click the following link to reset your password:</p><br>";
  $emailBody .= "<a href=".$url.">".$url."</a><br>";
  $emailBody .= "<p><em>This link will only be valid for 1 hour.</em></p><br>";
  sendEmail(
    $pwdReset->pwdResetEmail, 
    $toName, 
    $fromCC,
    $fromEmailName,
    $emailBody,
    $mailSubject,
    $replyEmail,
    $replyTopic,
    $mailAttachment,
    $toCC2
  );

  $redirect = "Location: ../forgotPassword.php?reset=success";
  header($redirect);
  exit;  
  } else {
    $redirect = "Location: ../forgotPassword.php?reset=noemail";
    header($redirect);
    exit;  
  }
} else {
  $redirect = "Location: ../index.php";
  header($redirect);
  exit; 
}
$redirect = "Location: ../index.php";
header($redirect);
exit; 
?>