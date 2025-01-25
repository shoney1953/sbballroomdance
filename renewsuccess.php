<?php
session_start();

$_SESSION['successurl'] = $_SERVER['REQUEST_URI'];
require_once 'config/Database.php';
require_once 'models/MemberPaid.php';
$renewalYear = '';
$database = new Database();
$db = $database->connect();
$member = new MemberPaid($db);
$partner = new MemberPaid($db);
$_SESSION['renewThisYear'] = 0;
$_SESSION['renewNextYear'] = 0;
$current_year = date('Y');
$next_year = date('Y+1');

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


if (count($yearsPaid) == 0) {
    $member->userid = $_SESSION['userid'];
    $member->year = $renewalYear;
    $member->paid = 1;
    $member->create();
}
if (count($yearsPaid) > 0) {
  $member->userid = $_SESSION['userid'];
  $member->year = $renewalYear;
  $member->paid = 1;
  $member->update();
}
// renew partner
if ($_SESSION['renewboth'] === 1) {
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


    if (count($yearsPaid) == 0) {
        $partner->userid = $_SESSION['partnerid'];
        $partner->year = $renewalYear;
        $partner->paid = 1;
        $partner->create();
    }
    if (count($yearsPaid) > 0) {
      $partner->userid = $_SESSION['partneridS'];
      $partner->year = $renewalYear;
      $partner->paid = 1;
      $partnerS>update();
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
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <section id="RenewSuccessful" class="content">

           <br><br><br>
    <div class="container-section ">
      <h1>You have successfully renewed your membership to  the Saddlebrooke Ballroom Dance Club!</h1>
      <h3>Your payment has been successfully processed, and should show SaddleBrooke Ballroom Dance Club on your statement</h3>
      <h3>You can see your payment status in Your Profile</h3>     
     
       
              <br><br><br>
    </div>

    </section>
</body>
</html>