<?php
session_start();

require_once 'includes/sendEmail.php';
require_once 'includes/siteemails.php';
$_SESSION['successurl'] = $_SERVER['REQUEST_URI'];
require_once 'config/Database.php';
require_once 'models/MemberPaid.php';
require_once 'models/User.php';
require_once 'models/TempOnlineRenewal.php';
$renewalYear = '';

$database = new Database();
$db = $database->connect();
$user1 = new User($db);
$user2 = new User($db);

$member  = new MemberPaid($db);
$partner = new MemberPaid($db);
$tempOnlineRenewal = new TempOnlineRenewal($db);
$tempID = $_GET['renewalid'];

$tempOnlineRenewal->id = $_GET['renewalid'];

unset($_GET['renewalid']);
$tempOnlineRenewal->read_single();


$noRenewalYear = 0;

$current_year = date('Y');

$next_year = date('Y', strtotime('+1 year'));


if ($tempOnlineRenewal->renewthisyear === '1') {
  $renewalYear = $current_year;

}
if ($tempOnlineRenewal->renewnextyear === '1') {
  $renewalYear = $next_year;
 
}
$user1->id = $tempOnlineRenewal->userid;

$user1->read_single();

$yearsPaid = [];

$result = $member->read_byUserid($tempOnlineRenewal->userid);

$rowCount = $result->rowCount();


if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paid_item = array(
            'id' => $id,
            'paid' => $paid,
            'paidonline' => $paidonline,
            'year' => $year

        );
        array_push($yearsPaid, $paid_item);


    }
} 
$noRenewalYear = 0;
if ($rowCount > 0) {

  foreach ($yearsPaid as $yp) {


    if ($renewalYear === $yp['year']) {

        $member->userid = $tempOnlineRenewal->userid;
        $member->year = $renewalYear;
        $member->paid = 1;
        $member->paidonline = 1;
        $member->id = $yp['id'];

        $member->update();
       sendThanks($user1,$treasurer,$president,$webmaster);
       $noRenewalYear = 1;
    }
  }

   if ($noRenewalYear === 0) {
     $member->userid = $tempOnlineRenewal->userid;
    $member->year = $renewalYear;
    $member->paid = 1;   
    $member->paidonline = 1;
    $member->create();
    sendThanks($user1,$treasurer,$president,$webmaster);

   }
} else {
  
    $member->userid = $tempOnlineRenewal->userid;
    $member->year = $renewalYear;
    $member->paid = 1;  
    $member->paidonline = 1;
    $member->create();
    sendThanks($user1,$treasurer,$president,$webmaster);


}

// renew partner
if ($tempOnlineRenewal->renewboth === '1') {
    $user2->id = $tempOnlineRenewal->partnerid;
    $user2->read_single();

    $noRenewalYear = 0;
      $yearsPaid = [];

    $result = $partner->read_byUserid($tempOnlineRenewal->partnerid);

    $rowCount = $result->rowCount();


    if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $paid_item = array(
                'id' => $id,
                'paid' => $paid,
                 'paidonline' => $paidonline,
                'year' => $year

            );
            array_push($yearsPaid, $paid_item);

        }
    } 


    if ($rowCount > 0) {

      foreach ($yearsPaid as $yp) {
          if ($renewalYear === $yp['year']) {
            $partner->userid = $tempOnlineRenewal->partnerid;
            $partner->year = $renewalYear;
            $partner->paid = 1;
            $partner->paidonline = 1;
            $partner->id = $yp['id'];

            $partner->update();
            $noRenewalYear = 1;
            sendThanks($user2,$treasurer,$president,$webmaster);
          }
    }

       if ($noRenewalYear === 0) {
        $partner->userid = $tempOnlineRenewal->partnerid;
        $partner->year = $renewalYear;
        $partner->paid = 1;
        $partner->paidonline = 1;
 
        $partner->create();
        sendThanks($user2,$treasurer,$president,$webmaster);

   }
} else {
   
        $partner->userid = $tempOnlineRenewal->partnerid;
        $partner->year = $renewalYear;
        $partner->paid = 1;
        $partner->paidonline = 1;
        $partner->create();
        sendThanks($user2,$treasurer,$president,$webmaster);
    
}

}
$tempOnlineRenewal->id = $tempID;

$tempOnlineRenewal->delete();
    $_SESSION['renewThisYear'] = 0;
    $_SESSION['renewNextYear'] = 0;

function sendThanks($user,$treasurer,$president,$webmaster) {

    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $user->firstname." ".$user->lastname ;
    $userEmail = $user->email;
    $mailSubject = 'Thanks for Renewing your membership Online at SBDC Ballroom Dance Club!';
    $fromCC = $treasurer;
    $toCC2 = $president;
    $toCC3 = $webmaster;
    $toCC4 = '';
    $toCC5 = null;

    $replyTopic = "Thanks for your renewal!";
       $replyEmail = 'sbbdcschedule@gmail.com';
       $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = ""; 

       $emailBody = "<br>Welcome renewing member <b>$toName </b>
       to the SaddleBrooke Ballroom Dance Club.<br><br>";
       $emailBody .= "Thanks for renewing your membership. We hope you'll 
       continue to enjoy our activites.<br>";
       $emailBody .= "Just as a reminder please click the link to our 
         activity calendar to view the latest updates to events and classes:<br> $actLink";
       $emailBody .= "Our Website address is https://sbballroomdance.com.<br><br>";
       $emailBody .= "<br>We hope to see you soon!<br>";
       require 'includes/emailSignature.php';

       sendEmail(
        $userEmail, 
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">

</script>
    <title>Successful submitRenewal</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            
            <li><a href="yourProfile.php">
            <img title="Click to see or update your information or registrations" src="img/profile.png" alt="Your Profile" style="width:32px;height:32px;">
            <br>Your Profile</a></li>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <section id="RenewSuccessful" class="content">

           <br><br><br>
    <div class="container-section ">
      <h1>You have successfully renewed your membership to  the Saddlebrooke Ballroom Dance Club!</h1>
      <h3>Your payment has been successfully processed, and should show SaddleBrooke Ballroom Dance Club on your statement</h3>
      <h3>Click on Your Profile in the menu above to see your payment status.</h3>     
     
       
              <br><br><br>
    </div>

    </section>
    
</body>
</html>