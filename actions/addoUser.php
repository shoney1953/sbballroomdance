<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';
require_once '../models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");


$database = new Database();
$db = $database->connect();
$user = new User($db);
$userArchive = new UserArchive($db);
$member1ID = 0;
$member2ID = 0;
// $toCC2 = $danceDirector;
$toCC3 = $webmaster;
// 

// $toCC4 = $volunteerDirector; 
$toCC2 = '';

// $toCC4 = 'richardschroeder50@gmail.com';
$toCC4 = '';
$toCC5 = '';
// $fromCC = $secretary;
$fromCC = '';



    $user->firstname = $_POST['firstname1'];
    $user->lastname = $_POST['lastname1'];
    $user->username = $_POST['username1'];
    $user->password = $_POST['initPass1'];
    $pass2 = $_POST['initPass21'];
    $user->email = $_POST['email1'];
    $user->role = $_POST['role1'];
    $user->streetAddress = $_POST['streetaddress1'];
    $user->city = $_POST['city1'];
    $user->state = $_POST['state1'];
    $user->zip = $_POST['zip1'];
    $user->phone1 = $_POST['phone11'];



    // if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    // } else {
    //     $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailInvalid';
    //     header($redirect);
    //     exit;
    // }
  
    // if (!$user->password == $pass2) {
    //     $redirect = "Location: ".$_SESSION['userurl'].'?error=PasswordMatch';
    //     header($redirect);
    //     exit;
    // }


    // if ($user->validate_user($user->username)) {
      
    //   $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
    //     header($redirect);
    //     exit;  
    // } 
       
    

    // if ($user->validate_email($user->email)) {
          
    //      $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailExists';
    //     header($redirect);
    //     exit;  
    // }

       $passHash = password_hash($user->password, PASSWORD_DEFAULT);
       $user->password = $passHash;
       $user->firstname = htmlentities($_POST['firstname1']);
       $user->lastname = htmlentities($_POST['lastname1']);
       $user->email = htmlentities($_POST['email1']);
       $user->username = htmlentities($_POST['username1']);
       $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL); 
       $user->streetAddress = htmlentities($_POST['streetaddress1']); 
       $user->city = htmlentities($_POST['city1']); 
       $user->state = htmlentities($_POST['state1']); 
       $user->zip = htmlentities($_POST['zip1']); 
  
       $user->phone1 = htmlentities($_POST['phone11']);

       $user->fulltime = htmlentities($_POST['fulltime1']);
       $user->directorylist = 1;
       $formerUser = "no";
       if ($userArchive->getUserName($user->username, $user->email)) {
          $formerUser = "yes";
          $userArchive->deleteUser($user->username, $user->email);
       }
       
       if ($_POST['hoa1'] === "1") {
          $user->hoa = 1;
       } else {
           $user->hoa = 2;
       }

       $fromEmailName = 'SBDC Ballroom Dance Club';
       $toName = $user->firstname.' '.$user->lastname; 
       $member1Full = $toName;
       if ($formerUser === 'yes') {
        $mailSubject = 'Thanks for Returning to us Online at SaddleBrooke Ballroom Dance Club!';
       } else {
        $mailSubject = 'Thanks for Joining us Online at the SaddleBrooke Ballroom Dance Club!';
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
 
       $emailBody .= "Thanks for signing up and paying your dues online! We hope you will participate in and enjoy the following club activities: <br><ul>";
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
    $member1ID = $db->lastInsertId();

    $memberPaid = new MemberPaid($db);
    $memberPaid->userid = $member1ID;
    $memberPaid->paid = 1; // mark paid
    $memberPaid->year = date("Y");
    $memberPaid->create();
    // create a membership record for next year
    $memberPaid->year = date('Y', strtotime('+1 year'));
    $memberPaid->paid = 0; // mark not paid
    $memberPaid->create();

    // ---------------------- MEMBER 2
    if (isset($_POST['firstname2'])) {
    $user->firstname = $_POST['firstname2'];
    $user->lastname = $_POST['lastname2'];
    $user->username = $_POST['username2'];
    $user->password = $_POST['initPass2'];
    $pass2 = $_POST['initPass22'];
    $user->email = $_POST['email2'];
    $user->role = $_POST['role2'];
    $user->streetAddress = $_POST['streetaddress2'];
    $user->city = $_POST['city2'];
    $user->state = $_POST['state2'];
    $user->zip = $_POST['zip2'];
    $user->phone1 = $_POST['phone12'];
    
    // if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    // } else {
    //     $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailInvalid';
    //     header($redirect);
    //     exit;
    // }
  
    // if (!$user->password == $pass2) {
    //     $redirect = "Location: ".$_SESSION['userurl'].'?error=PasswordMatch';
    //     header($redirect);
    //     exit;
    // }


    // if ($user->validate_user($user->username)) {
      
    //   $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
    //     header($redirect);
    //     exit;  
    // } 
       
    

    // if ($user->validate_email($user->email)) {
          
    //      $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailExists';
    //     header($redirect);
    //     exit;  
    // }

    $passHash = password_hash($user->password, PASSWORD_DEFAULT);
    $user->password = $passHash;
    $user->firstname = htmlentities($_POST['firstname2']);
    $user->lastname = htmlentities($_POST['lastname2']);
    $user->email = htmlentities($_POST['email2']);
    $user->username = htmlentities($_POST['username2']);
    $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL); 
    $user->streetAddress = htmlentities($_POST['streetaddress2']); 
    $user->city = htmlentities($_POST['city2']); 
    $user->state = htmlentities($_POST['state2']); 
    $user->zip = htmlentities($_POST['zip2']); 

    $user->phone1 = htmlentities($_POST['phone12']);

    $user->fulltime = htmlentities($_POST['fulltime2']);
    $user->directorylist = 1;
    $formerUser = "no";
    if ($userArchive->getUserName($user->username, $user->email)) {
       $formerUser = "yes";
       $userArchive->deleteUser($user->username, $user->email);
    }
    
    if ($_POST['hoa2'] === "1") {
       $user->hoa = 1;
    } else {
        $user->hoa = 2;
    }

    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $user->firstname.' '.$user->lastname; 
    $member2Full = $toName;
    if ($formerUser === 'yes') {
     $mailSubject = 'Thanks for Returning to us Online at SaddleBrooke Ballroom Dance Club!';
    } else {
     $mailSubject = 'Thanks for Joining us Online at the SaddleBrooke Ballroom Dance Club!';
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

    $emailBody .= "Thanks for signing up and paying your dues online! We hope you will participate in and enjoy the following club activities: <br><ul>";
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
 $member2ID = $db->lastInsertId();

 $memberPaid = new MemberPaid($db);
 $memberPaid->userid = $member2ID;
 $memberPaid->paid = 1; // mark paid
 $memberPaid->year = date("Y");
 $memberPaid->create();
 // create a membership record for next year
 $memberPaid->year = date('Y', strtotime('+1 year'));
 $memberPaid->paid = 0; // mark not paid
 $memberPaid->create();
 // cross reference partners
 $user->id = $member1ID;
 $user->partnerId = $member2ID;
 $user->notes = "Partner Name is: ".$member2Full."";
 $user->updatePartnerID();

 $user->id = $member2ID;
 $user->partnerId = $member1ID;
 $user->notes = "Partner Name is: ".$member1Full."";
 $user->updatePartnerID();
    }  


 $redirect = "Location: ".$_SESSION['homeurl']."";
    header($redirect);
    exit;
    
 
?>