<?php
session_start();

$_SESSION['successurl'] = $_SERVER['REQUEST_URI'];
require_once 'config/Database.php';
require_once 'models/MemberPaid.php';
$renewalYear = '';

$database = new Database();
$db = $database->connect();
$member  = new MemberPaid($db);
$partner = new MemberPaid($db);
$noRenewalYear = 0;

$current_year = date('Y');

$next_year = date('Y', strtotime('+1 year'));


if ($_SESSION['renewThisYear'] === 1) {
  $renewalYear = $current_year;

}
if ($_SESSION['renewNextYear'] === 1) {
  $renewalYear = $next_year;
 
}
$yearsPaid = [];

$result = $member->read_byUserid($_SESSION['userid']);

$rowCount = $result->rowCount();


if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paid_item = array(
            'id' => $id,
            'paid' => $paid,
            'year' => $year

        );
        array_push($yearsPaid, $paid_item);

    }
} 
$noRenewalYear = 0;
if ($rowCount > 0) {

  foreach ($yearsPaid as $yp) {


    if ($renewalYear === $yp['year']) {
        $member->userid = $_SESSION['userid'];
        $member->year = $renewalYear;
        $member->paid = 1;
        $member->id = $yp['id'];
 
        $member->update();
        $_SESSION['renewThisYear'] = 0;
        $_SESSION['renewNextYear'] = 0;
       $noRenewalYear = 1;
    }
  }

   if ($noRenewalYear === 0) {
     $member->userid = $_SESSION['userid'];
    $member->year = $renewalYear;
    $member->paid = 1;   
    $member->create();
    $_SESSION['renewThisYear'] = 0;
    $_SESSION['renewNextYear'] = 0;
   }
} else {
  
    $member->userid = $_SESSION['userid'];
    $member->year = $renewalYear;
    $member->paid = 1;   
    $member->create();
    $_SESSION['renewThisYear'] = 0;
    $_SESSION['renewNextYear'] = 0;

}
// renew partner
if ($_SESSION['renewboth'] === 1) {
    $noRenewalYear = 0;
      $yearsPaid = [];

    $result = $partner->read_byUserid($_SESSION['partnerid']);

    $rowCount = $result->rowCount();


    if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $paid_item = array(
                'id' => $id,
                'paid' => $paid,
                'year' => $year

            );
            array_push($yearsPaid, $paid_item);
        

        }
    } 


    if ($rowCount > 0) {

      foreach ($yearsPaid as $yp) {
          if ($renewalYear === $yp['year']) {
            $partner->userid = $_SESSION['partneridS'];
            $partner->year = $renewalYear;
            $partner->paid = 1;
            $partner->id = $yp['id'];
            $partner->update();
            $noRenewalYear = 1;
          }
    }
       if ($noRenewalYear === 0) {
        $partner->userid = $_SESSION['partnerid'];
        $partner->year = $renewalYear;
        $partner->paid = 1;
        $partner->create();
    $_SESSION['renewThisYear'] = 0;
    $_SESSION['renewNextYear'] = 0;
   }
} else {
   
        $partner->userid = $_SESSION['partnerid'];
        $partner->year = $renewalYear;
        $partner->paid = 1;
        $partner->create();
    
}
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