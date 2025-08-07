<?php
  session_start();
  require_once 'config/Database.php';
  require_once 'models/Event.php';
  require_once 'models/DinnerMealChoices.php';
  require_once 'models/User.php';
  if (isset($_SESSION['upcoming_events'])) {
      $events =  $_SESSION['upcoming_events'] ;
      $upcomingEvents = $_SESSION['upcoming_events'];
  }
  if (isset($_SESSION['upcoming_eventnumber'])) {
    $eventNumber = $_SESSION['upcoming_eventnumber'];
  }
 if (!isset($_SESSION['role'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect); 
   }
date_default_timezone_set("America/Phoenix");
$smealCHK1 = '';
$smealCHK2 = '';
$database = new Database();
$db = $database->connect();
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$mealChoices = [];
$mChoice = new DinnerMealChoices($db);
$_SESSION['regtype'] = "manual";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Register for Events </title>
</head>
<body>

    <nav class="nav">
        <div class="container">
        <ul>
            <li><a href="index.php">Back to Home</a></li>
            <?php
      
                if ($_SESSION['role'] != 'visitor') {
                 echo ' <li><a href="yourProfile.php">
                  <img title="Click to see or update your information or registrations" src="img/profile.png" alt="Your Profile" style="width:32px,height:32px">
                  <br>Your Profile</a></li>';
                }
              
            ?>
        </ul>
        </div>
    </nav>
    <br><br>

      
    <div class="container-section ">
    <div id='formdiv'>
    <form method="POST"  id="formelement" action="actions/regEvent.php">
    </div>
    <?php
      require_once "RegEventBody.php";
      ?>
    </div>
<?php
  require 'footer.php';
?>   
</body>
</html>