<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Contact.php';
require_once 'models/Visitor.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];


$visitors = [];
$allEvents = [];
$contacts = [];
$users = [];
unset($_SESSION['process_users']);

$eventRegistrations = [];
$num_registrations = 0;
$num_events = 0;

$num_visitors = 0;
$memberStatus1 = [];
$memberStatus2 = [];
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 
$current_month = date('m');
$current_year = date('Y');
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');

$rpChk = '';
$upChk = '';
$dlChk = '';
$emChk = '';
$dpChk = '';
$arChk = '';
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$database = new Database();
$db = $database->connect();
// refresh events

$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;
$_SESSION['allEvents'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventregend' => $eventregend,
            'eventregopen' => $eventregopen,
            'eventcost' => $eventcost,
            'eventform' => $eventform,
            'orgemail' => $orgemail,
            'eventdj' => $eventdj,
            "eventdesc" => html_entity_decode($eventdesc),
            "eventroom" => $eventroom,
            'eventnumregistered' => $eventnumregistered
        );
        array_push($allEvents, $event_item);
    
    }
  
    $_SESSION['allEvents'] = $allEvents;
} 


/* get event registrations */
$eventReg = new EventRegistration($db);
$result = $eventReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventregistrations'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'orgemail' => $orgemail,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    }
  
    $_SESSION['eventregistrations'] = $eventRegistrations;
} 





    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container">
  
     <ul> 
        <?php
    if (($_SESSION['role'] === 'ADMIN') ||
        ($_SESSION['role'] === 'SUPERADMIN')) 
       {
        echo '<li><a title="Return to Home Page" href="index.php">Back to Home</a></li>';

        echo '<li><a title="Add, Update and Archive Events" href="SBDCAEvents.php">Maintain Events</a></li>';
        echo '<li><a title="Class Related Functions" href="SBDCAClasses.php">Maintain Classes</a></li>';
        if ($_SESSION['role'] === 'SUPERADMIN') {
            
            echo '<li><a title="Add, Update, Report on Members" href="SBDCAMembers.php">Maintain Members</a></li>';
        }
        // echo '<li><a title="List Historical Data" href="archives.php">Archives</a></li>';

        echo '</ul>';
        echo '</div>';
        echo '<div class="container">';
  
        echo '<ul>';
 
        echo '<li><a title="List Visitors" href="SBDCAVisitors.php">Visitors</a></li>';
        echo '<li><a title="List Booking Reports" href="https://drive.google.com/drive/folders/1PQSs3_gNDuSfQ2L24Gw0Fnsoe9_vQlQ-?usp=sharing">
             Booking Reports</a></li>';

     }
     if ($_SESSION['role'] === 'INSTRUCTOR') {
   
        echo '<li><a title="Return to Home Page" href="index.php">Back to Home</a></li>';
        echo '<li><a title="Class Related Functions" href="SBDCAClasses.php">Maintain Classes</a></li>';
        // echo '<li><a title="List Historical Data" href="archives.php">Archives</a></li>';
        echo '</ul>';
        echo '</div>';
     }
        
        if ($_SESSION['role'] === 'SUPERADMIN') {
            
            // echo '<li><a title="Add, Update, Report on Members" href="SBDCAMembers.php">Maintain Members</a></li>';
            // echo '<li><a title="Add, Update, Report on Members" href="#users">Maintain Members</a></li>';
            // echo '<li><a title="List Members Status" href="#membership">Membership</a></li>';
            // echo '<li><a title="List Historical Data" href="archives.php">Archives</a></li>';
            

            if (isset($_SESSION['testmode'])) {
                if($_SESSION['testmode'] === 'YES') {
                    echo '<li><a title="Set Up Payment Options" href="payments.php">Payments</a></li>';
                }
            }

         
            echo '</ul>';
            echo '</div>';
        }
        ?>
    </ul>
     </div>
</nav>
<br><br><br>

<?php
    echo '<div class="container-section ">';
    echo '<section  class="content">';
    echo '<br><br><br>';
    echo '<h1>Administrative Functions for SaddleBrooke Ballroom Dance Club</h1>';
    echo '<h3>Please Select a Menu Option Above</h3>';
    echo '<h4><a href="https://drive.google.com/file/d/1R7kQMmGTbadaz_0ekPAIQXOrYvo0Mut8/view?usp=sharing">Click for Administrators Guide</a></h4>';
    echo '<br>';
    echo '</div>';
    echo '</div>';
    if (($_SESSION['role'] == 'ADMIN') || 
        ($_SESSION['role'] == 'SUPERADMIN') ) {

        echo '<div class="container-section ">';
        echo '<br>';
        echo '<section id="testmode" class="content">';
        echo '<form method="POST" action="actions/setTestMode.php">';
        // echo '<h4 class="form-title form-division">Set Test Mode On</h4>';
        echo '<div class="form-grid">';
        echo '<div class="form-item">';
        echo '<h4 class="form-item-title">Check on to see test functions.</h4>';
        echo "<input type='checkbox' title='Click to see Test Functions' name='testmode'><br>";
        echo '<button type="submit" name="submitTestMode">Set Test Mode</button>'; 
        echo '</div>';
        echo '</div>';
        echo '</form>';
        echo '</section>';
        echo '</div>';
    }

   
   ?>
 
    <footer >
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>