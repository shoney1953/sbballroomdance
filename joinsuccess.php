<?php
session_start();
require_once 'includes/sendEmail.php';
require_once 'includes/siteemails.php';
require_once 'config/Database.php';
require_once 'models/User.php';
require_once 'models/UserArchive.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
$potentialMember1 = $_SESSION['potentialMember1'];
$potentialMember2 = $_SESSION['potentialMember2'];
$_SESSION['joiningonline'] = 'YES';
$database = new Database();
$db = $database->connect();
$user = new User($db);
$userArchive = new UserArchive($db);
$member1ID = 0;
$member2ID = 0;
$nextYear = date('Y', strtotime('+1 year'));
$current_month = date('m');
$current_year = date('Y');

$toCC3 = $webmaster; 
// $toCC2 = '';
// $toCC4 = '';
// $toCC5 = '';

$fromCC = '';


$toCC2 = $president;
$toCC4 = $vicePresident;
$toCC5 = $volunteerDirector; 

// $fromCC = $secretary; // leave commented
        $userdefault1 = ucfirst($potentialMember1['firstname']).ucfirst(substr($potentialMember1['lastname'],0,1));
        $roledefault = 'MEMBER';
        $passdefault = 'test1234'; 
    $user->firstname = $potentialMember1['firstname'];
    $user->lastname = $potentialMember1['lastname'];
    $user->username = $userdefault1;
    $user->password = $passdefault;
    $pass2 = $passdefault;
    $passHash = password_hash($user->password, PASSWORD_DEFAULT);
    $user->password = $passHash;
    $user->email = $potentialMember1['email'];
    $user->role = $roledefault;
    $user->streetAddress = $potentialMember1['streetaddress'];
    $user->city = $potentialMember1['city'];
    $user->state = $potentialMember1['state'];
    $user->zip = $potentialMember1['zip'];
    $reformatphone = substr($potentialMember1['phone1'],0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($potentialMember1['phone1'],3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($potentialMember1['phone1'],6,4);

    $user->phone1 = $reformatphone;


       $user->directorylist = $potentialMember1['directorylist'];
  
       $user->fulltime = $potentialMember1['fulltime'];
       $user->hoa = $potentialMember1['hoa'];

       $formerUser = "no";
       if ($userArchive->getUserName($user->username, $user->email)) {
          $formerUser = "yes";
          $userArchive->deleteUser($user->username, $user->email);
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

   require 'includes/welcomeText.php';
   require 'includes/emailSignature.php';



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
    //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($_SESSION['partialyearmem'] === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
          $memberPaid->create();

    }


    // ---------------------- MEMBER 2
    if (isset($potentialMember2['firstname'])) {
        $userdefault = ucfirst($potentialMember2['firstname']).ucfirst(substr($potentialMember2['lastname'],0,1));
        $roledefault = 'MEMBER';
        $passdefault = 'test1234'; 
    $user->firstname = $potentialMember2['firstname'];
    $user->lastname = $potentialMember2['lastname'];
    $user->username = $userdefault;
    $user->password = $passdefault;
    $pass2 = $passdefault;
    $passHash = password_hash($user->password, PASSWORD_DEFAULT);
    $user->password = $passHash;
    $user->email = $potentialMember2['email'];
    $user->role = $roledefault;
    $user->email = $potentialMember2['email'];
    $user->streetAddress = $potentialMember2['streetaddress'];
    $user->city = $potentialMember2['city'];
    $user->state = $potentialMember2['state'];
    $user->zip = $potentialMember2['zip'];
    $reformatphone = substr($potentialMember2['phone1'],0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($potentialMember2['phone1'],3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($potentialMember2['phone1'],6,4);

    $user->phone1 = $reformatphone;

    $user->fulltime = $potentialMember2['fulltime'];
    // $user->directorylist = 1;
    $user->directorylist = $potentialMember2['directorylist'];
    $user->hoa = $potentialMember2['hoa'];
 
    $formerUser = "no";
    if ($userArchive->getUserName($user->username, $user->email)) {
       $formerUser = "yes";
       $userArchive->deleteUser($user->username, $user->email);
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

require 'includes/welcomeText.php';
require 'includes/emailSignature.php';



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
  //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($_SESSION['partialyearmem'] === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
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



$_SESSION['successurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['joiningonline'] = 'NO';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">     
    
</script>
    <title>SBDC Successful Membership</title>
</head>
<body>
    <div class="content">
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <section id="joinSuccessful" class="content">


           <br><br><br>
    <div class="container-section ">
      <h1>You have successfully joined the Saddlebrooke Ballroom Dance Club!</h1>
      <h2>We are excited to have you in the club!</h2>

      <h3>Your payment has been successfully processed, and should show SaddleBrooke Ballroom on your statement</h3>
    <?php
      echo '<h3 style="color: red"><em>NOTE</em>: Your Password will initally be<em> "test1234".</em><h3>';
      echo '<h3 style="color: red">Your userid will be the email you joined with: <em>"'.$potentialMember1['email'].'" or "'.$userdefault1.'" <em></h3>';
      ?>
     <h3>Please return to the home screen and login to begin enjoying the benefits of our club</h3>
    
      <br>

       
              <br><br><br>
    </div>

    </section>
    </div>
    <?php
  require 'footer.php';
?>
</body>
</html>