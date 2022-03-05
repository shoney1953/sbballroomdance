<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';
require_once '../models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username']))
{
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
       $formerUser = "no";
       if ($userArchive->getUserName($user->username, $user->email))
        {
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
        $mailSubject = 'Thanks for Returning to   us at SBDC Ballroom Dance Club!';
       } else {
        $mailSubject = 'Thanks for Joining us at SBDC Ballroom Dance Club!';
       }
      $toCC2 = 'president@sbballroomdance.com';
       $replyTopic = "Welcome";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = "../img/Intro.pdf"; 
       $fromCC = "sbbdcschedule@gmail.com";
       if ($formerUser === 'yes') {
        $emailBody = "<br>Welcome back<b> $toName </b> as a returning member 
        to the SaddleBrooke Ballroom Dance Club.<br><br>";
       } else {
        $emailBody = "<br>Welcome<b> $toName </b>to the SaddleBrooke Ballroom Dance Club.<br><br>";
       }
 
       $emailBody .= "We hope you will participate in and enjoy the following club activities: <br><ul>";
       $emailBody .= "<li><strong>Dance Classes</strong> - Classes are normally held at the Mariposa Room
       at DesertView on Mondays and Thursdays at 6 - 8pm. Emails will go out regarding the content, 
       format, instructors and dates; registration is also available on the website.</li>";
       $emailBody .= "<li><strong>Dinner Dances</strong> - These are provided most months during the snowbird
       season. They include a plated dinner with several entree choices followed by dancing to music provided
       by one of our DJs in the MountainView Ballroom. You will receive an email with a sign-up form
       stating the date, price and where to send the form and the check. The information will also
       be available on the website.</li>";
       $emailBody .= "<li><strong>Dine and Dance or TGIFs</strong> - These are dances with the option of having
       dinner with the group in the MountainView Bistro.  Dine and Dances has the dinner at approximately 4:30pm
       followed by dancing in the MountainView Ballroom. TGIFs are dancing first from 3 - 5pm followed by dinner. 
       You will also receive emails about these actvities. You can register for these on the website.</li>";
       $emailBody .= "<li><strong>Open Dance</strong> - These are slots open for practice dancing. They are not 
       exclusively for members, so you can bring friends. Often a DJ provides requested music. If no DJ is
       specified, you may bring your own music. Currently we have 3 different slots:<ul>
       <li>Tuesday 4 - 5:30pm in the HOA1 Vermillion Room</li>
       <li>Friday 3 - 5pm in the HOA1 Vermillion Room</li>
       <li>Sunday 3 - 5pm in the MountainView Ballroom</li>
       </ul></li>";

     $emailBody .= "</ul>
     <strong>At Times we have have room changes or cancellations, so it is important to check the Activities
     Calendar on the website to verify the schedule.<br>$actLink<br></strong>";

     $emailBody .= "
   We have attached a PDF that is an introduction to the website.
    The website shows Classes, Dances, and other events. 
   Once you logon, you can register for many actvities from there.$webLink<br>";

   $emailBody .= "We hope to see you soon!<br>";


     $emailBody .= "----------------------------<br>";
     $emailBody .= "<em>SaddleBrooke Ballroom Dance Club Board Members</em><br>";
     $emailBody .= "Brian Hand, President<br>";
     $emailBody .= "Rich Adinolfi, Vice President<br>"; 
     $emailBody .= "Dottie Adams, Treasurer<br>";
     $emailBody .= "Wanda Ross, Secretary<br>";
     $emailBody .= "Roger Shamburg, Chair, Dance Instructors<br>";
     $emailBody .= "Rick Baumgartner, Chair, DJs<br>";


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
            $toCC2
        );
    
    $user->create();
    // create a membership record for current year
    $memberPaid = new MemberPaid($db);
    $memberPaid->userid = $db->lastInsertId();
    $memberPaid->paid = 0;
    $memberPaid->year = date("Y");
    $memberPaid->create();
    // create a membership record for next year
    $memberPaid->year = date('Y', strtotime('+1 year'));
    $memberPaid->create();
    
 
 $redirect = "Location: ".$_SESSION['adminurl']."#users";
    header($redirect);
    exit;
    
} 
?>