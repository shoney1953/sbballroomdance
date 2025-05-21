<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';
require_once '../models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username'])) {

    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
$user = new User($db);
$userArchive = new UserArchive($db);

$toCC2 = $president;
$toCC3 = $webmaster;
// 

$toCC4 = $volunteerDirector; 

// $toCC4 = 'richardschroeder50@gmail.com';
$toCC5 = $vicePresident;
$fromCC = $ggWebmaster;

if (isset($_POST['submitAddUser'])) {

    $user->firstname = $_POST['firstname'];
    $user->lastname = $_POST['lastname'];
    $user->username = $_POST['username'];
    $user->password = $_POST['initPass'];
    $pass2 = $_POST['initPass2'];
    $user->email = $_POST['email'];
    $user->role = $_POST['role'];
    $user->streetAddress = $_POST['streetaddress'];
    $user->city = $_POST['city'];
    $user->state = $_POST['state'];
    $user->zip = $_POST['zip'];
    $user->phone1 = $_POST['phone1'];
    $user->phone2 = $_POST['phone2'];

    
    $user->notes = $_POST['notes'];

    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    } else {
        $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailInvalid';
        header($redirect);
        exit;
    }
  
    if (!$user->password == $pass2) {
        $redirect = "Location: ".$_SESSION['userurl'].'?error=PasswordMatch';
        header($redirect);
        exit;
    }


    if ($user->validate_user($user->username)) {
      
      $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
        header($redirect);
        exit;  
    } 
       
    

    if ($user->validate_email($user->email)) {
          
         $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailExists';
        header($redirect);
        exit;  
    }

       $passHash = password_hash($user->password, PASSWORD_DEFAULT);
       $user->password = $passHash;
       $user->firstname = htmlentities($_POST['firstname']);
       $user->lastname = htmlentities($_POST['lastname']);
       $user->email = htmlentities($_POST['email']);
       $user->username = htmlentities($_POST['username']);
       $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL); 
       $user->streetAddress = htmlentities($_POST['streetaddress']); 
       $user->city = htmlentities($_POST['city']); 
       $user->state = htmlentities($_POST['state']); 
       $user->zip = htmlentities($_POST['zip']); 
       $user->notes = htmlentities($_POST['notes']); 
       $user->phone1 = htmlentities($_POST['phone1']);
       $user->phone2 = htmlentities($_POST['phone2']);
       $user->fulltime = htmlentities($_POST['fulltime']);
       $user->directorylist = 1;
       $formerUser = "no";
       if ($userArchive->getUserName($user->username, $user->email)) {
          $formerUser = "yes";
          $userArchive->deleteUser($user->username, $user->email);
       }
       
       if ($_POST['hoa'] === "1") {
          $user->hoa = 1;
       } else {
           $user->hoa = 2;
       }

       $fromEmailName = 'SBDC Ballroom Dance Club';
       $toName = $user->firstname.' '.$user->lastname; 
       if ($formerUser === 'yes') {
        $mailSubject = 'Thanks '.$toName. ' for Returning to us at SBDC Ballroom Dance Club!';
       } else {
        $mailSubject = 'Thanks '.$toName.' for Joining us at SBDC Ballroom Dance Club!';
       }

       $replyTopic = "Welcome";
       $replyEmail = 'sheila_honey_5@hotmail.com';
/
   require '../includes/welcomeText.php';
   require '../includes/emailSignature.php';




        sendEmail(
            $user->email, 
            $toName, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $mailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    
    $user->create();
    // create a membership record for current year
    $memberPaid = new MemberPaid($db);
    $memberPaid->userid = $db->lastInsertId();
    $memberPaid->paid = 1; // mark paid
    $memberPaid->year = date("Y");
    $memberPaid->create();
    // create a membership record for next year
    $memberPaid->year = date('Y', strtotime('+1 year'));
    $memberPaid->paid = 0; // mark not paid
    $memberPaid->create();
    
 
 $redirect = "Location: ".$_SESSION['adminurl']."#users";
    header($redirect);
    exit;
    
} 
?>