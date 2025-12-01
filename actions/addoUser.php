<?php

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
$nextYear = date('Y', strtotime('+1 year'));
$current_month = date('m');
$current_year = date('Y');
$mailAttachment = '';
$mailAttachment2 = '';
$toCC2 = $webmaster; 


$fromCC = '';
  if ($_SERVER['SERVER_NAME'] === 'localhost') {  
   $toCC3 = '';
   $toCC4 = '';
  } else {
    $toCC3 = $president;
    $toCC4 = $treasurer;
  }


$toCC5 = ''; 

// $fromCC = $secretary; // leave commented

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
    $reformatphone = substr($_POST['phone11'],0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($_POST['phone11'],3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($_POST['phone11'],6,4);

    $user->phone1 = $reformatphone;

  
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
       $user->directorylist = ($_POST['directorylist1']);
  
       $user->fulltime = htmlentities($_POST['fulltime1']);
       $user->joinedonline = 1;
    //    $user->directorylist = 1;
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
        $mailSubject = 'Thanks '.$toName.' for Returning to us Online at SaddleBrooke Ballroom Dance Club!';
       } else {
        $mailSubject = 'Thanks '.$toName.' for Joining us Online at the SaddleBrooke Ballroom Dance Club!';
       }

       $replyTopic = "Welcome";
       $replyEmail = $webmaster;

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
             $mailAttachment2,
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
    $memberPaid->paidonline = 1;
    $memberPaid->year = date("Y");
    $memberPaid->create();
    // create a membership record for next year
    //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($_SESSION['partialyearmem'] === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->paidonline = 0;
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->paidonline = 1;
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
          $memberPaid->paidonline = 0;
          $memberPaid->create();

    }


    // ---------------------- MEMBER 2
    if (isset($_POST['firstname2'])) {

    $user->firstname = $_POST['firstname2'];
    $user->lastname = $_POST['lastname2'];
    $user->username = $_POST['username2'];
    $user->password = $_POST['initPass2'];
    $pass2 = $_POST['initPass22'];
    $user->email = $_POST['email2'];
    $user->role = $_POST['role2'];

  
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
    $reformatphone = substr($_POST['phone12'],0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($_POST['phone12'],3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($_POST['phone12'],6,4);

    $user->phone1 = $reformatphone;

    $user->fulltime = htmlentities($_POST['fulltime2']);
    // $user->directorylist = 1;
    $user->directorylist = $_POST['directorylist2'];
    $user->joinedonline = 1;
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
     $mailSubject = "Thanks ".$toName." for Returning to us Online at SaddleBrooke Ballroom Dance Club!";
    } else {
     $mailSubject = "Thanks ".$toName." for Joining us Online at the SaddleBrooke Ballroom Dance Club!";
    }

    $replyTopic = "Welcome";
    $replyEmail = $webmaster;

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
        $mailAttachment2,
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
 $memberPaid->paidonline = 1;
 $memberPaid->create();
 // create a membership record for next year
  //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($_SESSION['partialyearmem'] === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->paidonline = 0;
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->paidonline = 1;
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
          $memberPaid->paidonline = 0;
          $memberPaid->create();

    }


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


   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
    
 
?>