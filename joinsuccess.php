<?php
session_start();

require_once 'includes/sendEmail.php';
require_once 'includes/siteemails.php';
require_once 'config/Database.php';
require_once 'models/User.php';
require_once 'models/Options.php';
require_once 'models/TempOnlineMember.php';
require_once 'models/UserArchive.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

$_SESSION['joiningonline'] = 'YES';
$nextYear = date('Y', strtotime('+1 year'));
$current_month = date('m');
$current_year = date('Y');
$database = new Database();
$db = $database->connect();
$user = new User($db);
$tempmember1 = new TempOnlineMember ($db);
$tempmember2 = new TempOnlineMember ($db);
$userArchive = new UserArchive($db);
$tempmember1ID = $_GET['id1'];
$tempmember2ID = $_GET['id2'];
$partialYearMem = $_GET['py'];
unset($_GET['id1']);
unset($_GET['id2']);
unset($_GET['py']);
$member1ID = 0;
$member2ID = 0;
$tempmember1->id = $tempmember1ID;
$tempmember1->read_single();

if ($tempmember2ID > 0) {
   $tempmember2->id = $tempmember2ID;
   $tempmember2->read_single(); 

}
$allOptions = [];
$options = new Options($db);
$result = $options->read();

$rowCount = $result->rowCount();

$num_options = $rowCount;

$_SESSION['allOptions'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
 
        $option_item = array(
            'id' => $id,
            'year' => $year,
            'renewalmonth' => $renewalmonth,
            'discountmonth' => $discountmonth   
        );
        array_push($allOptions, $option_item);

    }
    $_SESSION['allOptions'] = $allOptions;
} 
foreach($allOptions as $option) {
    if ($current_year === $option['year']) {
        $_SESSION['renewalmonth'] = $option['renewalmonth'];
        $_SESSION['discountmonth'] = $option['discountmonth'];

        break;
    }
}

$mailAttachment = '';
$toCC3 = $webmaster; 
// $toCC2 = '';
// $toCC4 = '';
// $toCC5 = '';

$fromCC = '';


$toCC2 = $president;
$toCC4 = $treasurer;
$toCC5 = $volunteerDirector; 

// $fromCC = $secretary; // leave commented
        $userdefault1 = ucfirst($tempmember1->firstname).ucfirst(substr($tempmember1->lastname,0,1));
        $roledefault = 'MEMBER';
        $passdefault = 'test1234'; 
    $user->firstname = $tempmember1->firstname;
    $user->lastname = $tempmember1->lastname;
    $user->username = $userdefault1;
    $user->password = $passdefault;
    $user->joinedonline = 1;
    $pass2 = $passdefault;
    $passHash = password_hash($user->password, PASSWORD_DEFAULT);
    $user->password = $passHash;
    $user->email = $tempmember1->email;
    $user->role = $roledefault;
    $user->streetAddress = $tempmember1->streetAddress;
    $user->city = $tempmember1->city;
    $user->state = $tempmember1->state;
    $user->zip = $tempmember1->zip;
    $reformatphone = substr($tempmember1->phone,0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($tempmember1->phone,3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($tempmember1->phone,6,4);

    $user->phone1 = $reformatphone;
  

       $user->directorylist = $tempmember1->directorylist;
  
       $user->fulltime = $tempmember1->fulltime;
       $user->hoa = $tempmember1->hoa;

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
   $tempmember1->delete();
    $memberPaid = new MemberPaid($db);
    $memberPaid->userid = $member1ID;
    $memberPaid->paid = 1; // mark paid
    $memberPaid->paidonline = 1; // mark paid
    $memberPaid->year = date("Y");
    $memberPaid->create();
    // create a membership record for next year
    //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($partialYearMem === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->paidonline = 0; // mark paid
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->paidonline = 1; // mark paid
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
        $memberPaid->paidonline = 0; // mark paid
          $memberPaid->create();

    }


    // ---------------------- MEMBER 2
    if ($tempmember2ID !== 0) {
    if (isset($tempmember2->firstname)) {
        $userdefault = ucfirst($tempmember2->firstname).ucfirst(substr($tempmember2->lastname,0,1));
        $roledefault = 'MEMBER';
        $passdefault = 'test1234'; 
    $user->firstname = $tempmember2->firstname;
    $user->lastname = $tempmember2->lastname;
    $user->username = $userdefault;
    $user->password = $passdefault;
    $pass2 = $passdefault;
    $passHash = password_hash($user->password, PASSWORD_DEFAULT);
    $user->password = $passHash;
    $user->email = $tempmember2->email;
    $user->role = $roledefault;
    $user->joinedonline = 1;
    $user->streetAddress = $tempmember2->streetAddress;
    $user->city = $tempmember2->city;
    $user->state = $tempmember2->state;
    $user->zip = $tempmember2->zip;
    $reformatphone = substr($tempmember2->phone,0,3);
    $reformatphone .= '-';
    $reformatphone .= substr($tempmember2->phone,3,3);
     $reformatphone .= '-';
     $reformatphone .= substr($tempmember2->phone,6,4);

    $user->phone1 = $reformatphone;

    $user->fulltime = $tempmember2->fulltime;
    // $user->directorylist = 1;
    $user->directorylist = $tempmember2->directorylist;
    $user->hoa = $tempmember2->hoa;
 
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
 $memberPaid->paidonline = 1; // mark paid
 $memberPaid->year = date("Y");
 $memberPaid->create();
 // create a membership record for next year
  //if in the discount period, they can do only the current year, or current plus next
    if ((int)$current_month >= $_SESSION['discountmonth']) {
      if ($partialYearMem === 1) {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 0; // mark not paid
            $memberPaid->paidonline = 0; // mark paid
            $memberPaid->create();
      } else {
            $memberPaid->year = date('Y', strtotime('+1 year'));
            $memberPaid->paid = 1; // mark paid
            $memberPaid->paidonline = 1; // mark paid
            $memberPaid->create();
      } 
    } else {
          $memberPaid->year = date('Y', strtotime('+1 year'));
          $memberPaid->paid = 0; // mark not paid
          $memberPaid->paidonline = 0; // mark paid
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
  $tempmember2->delete();
    }  
  
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
      echo '<h3 style="color: red">Your userid will be the email you joined with: <em>"'.$tempmember1->email.'" or "'.$userdefault1.'" <em></h3>';
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