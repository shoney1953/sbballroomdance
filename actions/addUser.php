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

$toCC2 = $danceDirector;
$toCC3 = $webmaster;
// 

$toCC4 = $volunteerDirector; 

// $toCC4 = 'richardschroeder50@gmail.com';
$toCC5 = '';
$fromCC = $secretary;

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
        $mailSubject = 'Thanks for Returning to us at SBDC Ballroom Dance Club!';
       } else {
        $mailSubject = 'Thanks for Joining us at SBDC Ballroom Dance Club!';
       }

       $replyTopic = "Welcome";
       $replyEmail = 'sheila_honey_5@hotmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = "../img/Member Guide to Website Version 2.pdf"; 
 
       if ($formerUser === 'yes') {
        $emailBody = "<br>Welcome back<b> $toName </b> as a returning member 
        to the SaddleBrooke Ballroom Dance Club.<br><br>";
        $toCC4 = '';
       } else {
        $emailBody = "<br>Welcome<b> $toName </b>to the SaddleBrooke Ballroom Dance Club.<br><br>";
       }
 
       $emailBody .= "We hope you will participate in and enjoy the following club activities: <br><ul>";
       $emailBody .= "<li><strong>Novice Events</strong> - For anyone that has little dance experience, we have a new 
       program of Novice events: Novice classes twice a month, and then a Novice practice party. These are designed to
       help you learn a few steps of the most common dances, and help you practice with other Novices. 
       You will also receive emails about these actvities. You can register for these on the website.</li>";
       $emailBody .= "<li><strong>Dance Classes</strong> - Classes are normally held at the MountainView Ballroom
       at the MountainView clubhouse on all the Sundays from 3pm to 5pm and Tuesdays from 4pm to 6pm of each month.
       Emails will go out regarding the content, format, instructors and dates; registration is also available on the website.</li>";
       $emailBody .= "<li><strong>Dance Parties</strong> - These are provided on most months during the year.
       They include a dance with a no host bar in the MountainView Ballroom, and may or not have some food served. 
       If there is a charge for attendance, there will be a form associated with the event and you may print it and
       send in meal selections with your payment to the treasurer. You may click on the VIEW tab of the event to get the form,
       but it will also be sent out by email and available at events and classes prior to the Dance Party.</li>";     
       $emailBody .= "<li><strong>Open Practice</strong> - These are slots open for practice dancing. They are not 
       exclusively for members, so you can bring friends. Often a DJ provides requested music. If no DJ is
       specified, you may bring your own music. Currently we have 3 different slots:
         <ul>
         <li>Fourth Monday of each month 4 - 6pm in the HOA1 Vermilion Room</li>
         <li>Friday  4 - 6pm in the HOA1 Vermilion Room (Used once a month for Novice Parties)</li>
         <li>Wednesday from 4pm to 6pm in the Mariposa Room at DesertView</li>
         </ul></li><br>";
       
       $emailBody .= "<li>Your name, email, phone and address will be listed in our directory by default unless
       you indicated otherwise on your membership form. But if you
       wish not to list your information in the directory, you can go to your profile on the website
       and set the option off or contact us and we will set it off.</li>";

     $emailBody .= "</ul><strong>At Times we have have room changes or cancellations, so it is important to check the Activities
     Calendar on the website to verify the schedule.<br>$actLink<br></strong>";

     $emailBody .= "
   We have attached a PDF that is an guide to the website.
   The website shows Classes, Dances, and other events. Your login credentials will be either: 
    <b>your email
     <em>or</em>  
     your firstname and last initial with the first letter of your first name capitalized and your last initial capitalized</b>.
   The initial password is <b>test1234</b>. You should change your password when you first logon from your profile.
   Once you logon to the website, you can register for most classes and events from there. 
   $webLink<br>";

   $emailBody .= "We hope to see you soon!<br>";


     $emailBody .= "----------------------------<br>";
     $emailBody .= "<em>SaddleBrooke Ballroom Dance Club Board Members</em><br>";
     $emailBody .= "Rich Adinolfi, President<br>";
     $emailBody .= "Nan Kartsonis, Vice President<br>"; 
     $emailBody .= "Roger Shamburg, Treasurer<br>";
     $emailBody .= "Peggy Albrecht, Secretary<br>";
    //  $emailBody .= "Peggy Albrect, Secretary<br>";
     $emailBody .= "Ann and Dale Pizzitola, Directors of Dance Instruction <br>";
     $emailBody .= "Vivian Herman, Director of Volunteers<br>";


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