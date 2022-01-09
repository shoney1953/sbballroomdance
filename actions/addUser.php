<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/User.php';
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
       
       if ($_POST['hoa'] === "1") {
          $user->hoa = 1;
       } else {
           $user->hoa = 2;
       }

       $fromEmailName = 'SBDC Ballroom Dance Club';
       $toName = $user->firstname.' '.$user->lastname; 
       $mailSubject = 'Thanks for Joining us at SBDC Ballroom Dance Club!';
       $replyTopic = "Welcome";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $mailAttachment = "../img/Intro.pdf"; 
       $fromCC = "sbbdcschedule@gmail.com";
       $emailBody = "<br>We would like to welcome you<b> $toName </b> as a new member to our club.<br><br>";
       $emailBody .= "
       You've joined at a wonderful time as SaddleBrooke is opening up more all the time 
       now that most of the community has been vaccinated for Covid 19. 
       We have many opportunities to dance 
       plus we offer lessons to learn new dances and to improve on the dances we already do. <br>";

       $emailBody .= "We dance year round, so join us for these dance events.<br><ul>"; 
       $emailBody .= "<li>A Dine and Dance is often held on the first Friday of the month, usually starting at 4:30pm. 
Dinner may be purchased at the MountainView Bar & Grill at HOA2, 
then we dance around 6pm in the MountainView Ballroom. You may come just for the dancing.<br>
On occasional months, the date of this event may change or there may not be a Dine and Dance.</li> <br>";

    $emailBody .= "<li>Dinner Dance Parties that include a plated dinner and dancing are scheduled for many upcoming months.
     An email announcing each of these events is sent to the members along with details on how to register for the event. 
     You may also register on the website. There will be a form available to download with the description of the event.</li><br>";

     $emailBody .= "<li>Free dance lessons are offered to SBDC members on Mondays at 6pm in the Mariposa Room at the DesertView Clubhouse in HOA2. 
     Usually, lesson reviews (and possibly additional steps) are held on Thursdays at 6pm in the same location. Lessons may be offered at other dates, times and locations.
     You may register for classes on the website.</li><br>"; 

     $emailBody .= "<li>Open Dance sessions are available on Tuesday from 4-5:30pm and Fridays from 3-5pm in the Vermillion Room at HOA1 
     and Sundays from 3-5pm in the MountainView Ballroom at HOA2. These open dance sessions are open to everyone.<br>
      Most of the time we have a DJ, but if you do not see one on the activities calendar, feel free to bring your own music.<br>
     Always check the website activities calendar to be sure. We try to keep it up to date because we do have cancellations.</li><br>";

     $emailBody .= "</ul>You have been added to our email distribution list so periodically you will receive emails from SBDC. 
     Another way to know about upcoming dances and lessons is to go to our website: www.sbballroomdance.com. You may register for events or classes from there.<br><br>";

     $emailBody .= "Be happy! Dance happy!<br>";
     $emailBody .= "--<br>";
     $emailBody .= "SaddleBrooke Ballroom Dance Club Board Members<br>";
     $emailBody .= "Brian Hand, President<br>";
     $emailBody .= "Rich Adinolfi, Vice President<br>"; 
     $emailBody .= "Dottie Adams, Treasurer<br>";
     $emailBody .= "Wanda Ross, Secretary<br>";
     $emailBody .= "Roger Shamburg, Chair, Dance Instructors<br>";
     $emailBody .= "Rick Baumgartner, Chair, DJs<br>";

       
     $emailBody .= "$actLink.<br>";
 

        sendEmail(
            $user->email, 
            $toName, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $mailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment
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
    
 
 $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
    
} 
?>