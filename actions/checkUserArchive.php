<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';
require_once '../models/MemberPaid.php';
require_once '../models/MemberPaidArchive.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

if (!isset($_SESSION['username']))
{
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
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
        }
       } else {
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
       }
}

if(isset($_GET['error'])) {

    unset($_GET['error']);
} elseif(isset($_GET['success'])) {
    
    unset($_GET['success']);
} 


$database = new Database();
$db = $database->connect();
$user = new User($db);
$userArch = new UserArchive($db);
$mailAttachment = '';
$mailAttachment2 = '';
$toCC2 = '';
$toCC3 = $webmaster;
$toCC5 = '';
$toCC4 = '';
$fromCC = $ggWebmaster;

$passdefault = 'test1234';
$userdefault = '';


if (isset($_POST['submitCheckArch'])) {
    if (isset($_POST['archemail'])) {
      if (!$userArch->getUserEmail($_POST['archemail'])) {
         $redirect = "Location: ".$_SESSION['userurl']."?userstatus=NotArchived";
           header($redirect);
      } 
      if ($user->validate_user($_POST['archemail'])) {
  
         $redirect = "Location: ".$_SESSION['userurl']."?userstatus=MemberExists&email=".$_POST['archemail'];
           header($redirect);
      }
    }    
        $formerUser = 'YES';
    // $user->id = $userArch->id'];
          // $user->previd = $userArch->previd;
          $user->firstname = $userArch->firstname;
          $user->lastname = $userArch->lastname;
          $user->username = $userArch->username;
          $user->email = $userArch->email;
          $user->role = $userArch->role;
          $user->password = $passdefault;
    
          $user->created = $userArch->memberorigcreated;
          $user->role = $userArch->role;
          // $user->passwordChanged = $userArch->passwordChanged;
          // $user->partnerId = $userArch->partnerid;
          $user->partnerId = 0;
          $user->streetAddress = $userArch->streetAddress;
          $user->city = $userArch->city;
          $user->state = $userArch->state;
          $user->zip = $userArch->zip;
          $user->hoa = $userArch->hoa;
          $user->phone1 = $userArch->phone1;
          $user->phone2 = $userArch->phone2;
          $user->notes = $userArch->notes;
          $user->lastLogin = $userArch->lastLogin;
          // $user->dateArchived = $userArch->dateArchived;
         $user->numlogins = $userArch->numlogins;
         $user->directorylist = $userArch->directorylist;
         $user->fulltime = $userArch->fulltime;
         $user->robodjnumlogins = $userArch->robodjnumlogins;
         $user->robodjlastlogin = $userArch->robodjlastlogin;
        //  $user->memberorigcreated = $userArch->memberorigcreated;
         $user->regformlink = $userArch->regformlink;
         $user->joinedonline = $userArch->joinedonline;
          $user->dietaryrestriction = $userArch->dietaryrestriction;

          if ($userArch->previd !== null) {
         
               $user->id = $userArch->previd;
               $user->unArchive2();
          } else {
            $user->unArchive();
          }

    // create a membership record for current year
    $memberPaid = new MemberPaid($db);
    $memberPaidArch= new MemberPaidArch($db);
    $restoredUserid = 0;
     if ($userArch->previd !== null) {
         $memberPaid->userid = $userArch->previd;   
         $restoredUserid = $userArch->previd;
     } else {
         $memberPaid->userid = $db->lastInsertId(); 
         $restoredUserid = $db->lastInsertId();

     }
    // create current payment year and next year
    $memberPaid->paid = 1; // mark paid
    $memberPaid->year = date("Y");
    $savePaidYear = $memberPaid->year;
    $memberPaid->paidonline = 0;
    $memberPaid->create();
    // create a membership record for next year
    $memberPaid->year = date('Y', strtotime('+1 year'));
    $savePaidNextYear = $memberPaid->year;
    $memberPaid->paid = 0; // mark not paid
    $memberPaid->paidonline = 0;
    $memberPaid->create();
    // restore membership history
     $result = $memberPaidArch->read_byUserid($user->id);
        $rowCount = $result->rowCount();
        if ($rowCount > 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $paid_item = array(
                    'id' => $id,
                    'paid' => $paid,
                    'year' => $year,
                    'paidonline' => $paidonline

                );
             $memberPaid->userid = $restoredUserid;
             $memberPaid->paid = $paid_item['paid'];
             $memberPaid->year = $paid_item['year'];
             $memberPaid->paidonline = $paid_item['paidonline'];
             // don't recreate years we inserted above
             if ( ($paid_item['year'] != $savePaidYear) && ($paid_item['year'] != $savePaidNextYear) )
             $memberPaid->create();

            } // while
          }  // rowcount
        $memberPaidArch->deleteUserid($user->id);

          $userArch->delete();
       $fromEmailName = 'SBDC Ballroom Dance Club';
       $toName = $user->firstname.' '.$user->lastname; 
        $mailSubject = 'Thanks '.$toName. ' for Returning to us at SBDC Ballroom Dance Club!';

       $replyTopic = "Welcome Returning Member";
       $replyEmail = 'sheila_honey_5@hotmail.com';

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
    
   
          
       $redirect = "Location: ".$_SESSION['userurl']."?userstatus=SuccessfullyUnArchived";
           header($redirect);
}
