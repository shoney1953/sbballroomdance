<?php
session_start();
require_once 'config/Database.php';

require_once 'models/MemberPaid.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
$database = new Database();
$db = $database->connect();
$memPaid = new MemberPaid($db);
$thisYear = date("Y");
$nextYear = date('Y', strtotime('+1 year')); 
$memberCurr = [];
$result = $memPaid->read_byYear($thisYear);
  
  $rowCount = $result->rowCount();
  $num_memPaid = $rowCount;
  if ($rowCount > 0) {
  
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $member_item = array(
              'id' => $id,
              'firstname' => $firstname,
              'lastname' => $lastname,
              'userid' => $userid,
              'email' => $email,
              'year' => $year,
              'paidonline' => $paidonline,
              'paid' => $paid
          );
          array_push($memberCurr, $member_item);
    
      }

    $_SESSION['memPaidCurrent'] = $memberCurr;
  } 


  
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Populate Next Year Member Payment</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Home Page" href="administration.php">Back to Administration</a></li>

      </ul>
    </div>

</nav>

<?php

if ($_SESSION['role'] === 'SUPERADMIN') {
         echo "<div class='container-section' name='users'>  <br><br>";
        echo '<section id="users"  class="content">';
        echo ' <h3 class="section-header">Populate Next Years Member Payment Status</h3> ';
          echo "<div class='form-grid-div'>";
            echo "<form method='POST' action='actions/populatePaid.php'>"; 
            echo "<button type='submit' name='submitPopPaid'>Populate Members Paid for: ".$nextYear."</button>";   
            echo '</form>'    ;   
          echo '</div>';
          echo '</section>';
    echo '</div>';
}
?>
    <footer>
    <?php
    require 'footer.php';
   ?>
    </footer>

</body>
