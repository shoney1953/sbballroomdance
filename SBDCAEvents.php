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
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['adminEventurl'] = $_SERVER['REQUEST_URI']; 


$visitors = [];
$allEvents = [];
$eventRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$_POST = array(); // clear post array so check boxes do not remain checked
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
            'eventnumregistered' => $eventnumregistered,
            'eventproductid' => $eventproductid,
            'eventmempriceid' => $eventmempriceid,
            'eventguestpriceid' => $eventguestpriceid,
            'eventguestcost' => $eventguestcost,
            'eventdwopcount' => $eventdwopcount
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
            'mealchoice' => $mealchoice,
            'dietaryrestriction' => $dietaryrestriction,
            'dwop' => $dwop,
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
    <link rel="stylesheet" href="css/style.css?v3">
    <title>SBDC Ballroom Dance - Event Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="administration.php">Back to Administration</a></li>
        <?php
          if ($_SESSION['role'] != 'DJ') {
           echo '<li><a title="List Historical Data" href="SBDCAAEvents.php">Archived Events</a></li>';
          }
        ?>
       
      </ul>
    </div>

</nav>

<?php
if ($_SESSION['role'] != 'INSTRUCTOR') {

echo '<div class="container-section ">';
echo '<br>';
echo '<section id="events" class="content">';
echo '<form method="POST" action="actions/processEvents.php">';

   echo '<h3 class="form-title">Maintain Events</h3>';

   foreach($allEvents as $event) {
    $rpChk = "rp".$event['id'];
    $upChk = "up".$event['id'];
    $dlChk = "dl".$event['id'];
    $emChk = "em".$event['id'];
    $emNonChk = "emnon".$event['id'];
    $aeChk = "ae".$event['id'];
    $dpChk = "dp".$event['id'];
    $arChk = "ar".$event['id'];
    $drChk = "dr".$event['id'];
    $urChk = "ur".$event['id'];
    $cvChk = "cv".$event['id'];
    $amChk = 'am'.$event['id'];
    $umChk = 'um'.$event['id'];
    $ufChk = 'uf'.$event['id'];
    $mbSrch = "srch".$event['id'];
    $class_month = substr($event['eventdate'], 5, 2);
    $class_year = substr($event['eventdate'], 0, 4);
    $showReg = 0;
    // echo '<div class="form-container">';
    echo '<fieldset>';
    echo "<legend>".$event['eventdate']." &nbsp;&nbsp;&nbsp; ".$event['eventname']."  &nbsp;&nbsp&nbsp;".$event['eventtype']."</legend>";
    echo '<div class="form-grid">';


   echo '<div class="form-item">';
   echo "<h4 class='form-title'>Minimum Cost: ".$event['eventcost']."</h4>";
   echo '</div>';
   echo '<div class="form-item">';
   echo "<h4 class='form-title'>ID: ".$event['id']."</h4>";
   echo '</div>';
   echo '<div class="form-item">';
   $hr = 'eventMem.php?id=';
   $hr .= $event["id"];
   echo "<h4 class='form-title'>Number Registered: 
        <a href='".$hr."'>".$event['eventnumregistered']."</a></h4>";
   echo '</div>';

   echo '</div>';

   echo '<div class="form-grid">';
    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Report?</h4>';
    echo "<input type='checkbox' title='Only select 1 event for Report' name='".$rpChk."'>";
    echo '</div>';

    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Create CSV?</h4>';
    echo "<input type='checkbox' title='Only select 1 event for Create CSV' name='".$cvChk."'>";
    echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Email Attendees?</h4>';
   echo "<input type='checkbox' title='Only select 1 event for Email' name='".$emChk."'>";
   echo '</div>';
   
   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Email Those Not Registered?</h4>';
   echo "<input type='checkbox' title='Only select 1 event for Email' name='".$emNonChk."'>";
   echo '</div>';

    if ($_SESSION['role'] === 'EVENTADMIN') {
        echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Delete?</h4>';
   echo "<input type='checkbox' title='Select to Delete Event(s)' name='".$dlChk."'>";
   echo '</div>';
      echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Duplicate?</h4>';
   echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Update?</h4>';
   echo "<input type='checkbox' title='Select to Update Event(s)' name='".$upChk."'>";   
   echo '</div>';


    if ($compareDate <= $event['eventdate']) {

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Add Meal Options</h4>';
   echo "<input type='checkbox' title='Select to Add Meal(s)' name='".$amChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Update Meal Options</h4>';
   echo "<input type='checkbox' title='Select to Update Meal(s)' name='".$umChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Upload Flyer</h4>';
   echo "<input type='checkbox' title='Select to Upload Flyer' name='".$ufChk."'>";
   echo '</div>';
    echo '<div class="form-item">';
    echo '<button type="submit" name="submitEventProcess">Process This Event</button>'; 

    echo '</div>';
   
}
    }
   if ($_SESSION['role'] === 'SUPERADMIN') {
       echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Duplicate?</h4>';
   echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Update?</h4>';
   echo "<input type='checkbox' title='Select to Update Event(s)' name='".$upChk."'>";   
   echo '</div>';


    if ($compareDate <= $event['eventdate']) {

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Add Meal Options</h4>';
   echo "<input type='checkbox' title='Select to Add Meal(s)' name='".$amChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Update Meal Options</h4>';
   echo "<input type='checkbox' title='Select to Update Meal(s)' name='".$umChk."'>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Upload Flyer</h4>';
   echo "<input type='checkbox' title='Select to Upload Flyer' name='".$ufChk."'>";
   echo '</div>';
   
}
       echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Delete?</h4>';
   echo "<input type='checkbox' title='Select to Delete Event(s)' name='".$dlChk."'>";
   echo '</div>';
    echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Archive?</h4>';
   echo "<input type='checkbox' title='Select to Archive Events' name='".$aeChk."'>";
   echo '</div>';
   }

    echo '</div>';  
    if ($compareDate <= $event['eventdate']) {
    if ($_SESSION['role'] === 'SUPERADMIN') {
 
    echo '<div class="form-grid">';
     echo '<div class="form-item">';
     echo '<h4 class="form-item-title">Add Registrations</h4>';
          echo "<input type='checkbox' 
          title='Only select 1 event to Add Registrations' name='".$arChk."'>";
     echo '</div>';
          echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Update Registrations</h4>';
    echo "<input type='checkbox' 
             title='Only select 1 event to Update Registrations' name='".$urChk."'>";
    echo '</div>';
       echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Delete Registrations?</h4>';
    echo "<input type='checkbox' 
          title='Only select 1 event to Delete Registrations' name='".$drChk."'>";
    echo '</div>';
        echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Enter Search Criteria to limit Registrations</h4>';
    echo "<input type='search'  
          title='Enter Partial or Full Name to qualify Registrations' name='".$mbSrch."' >"; 
    echo '</div>';
    echo '<div class="form-item">';
    echo '<button type="submit" name="submitEventProcess">Process This Event</button>'; 

    echo '</div>';
    
     echo '</div>'; 
    }
    } else {

        // echo '<h4 class="form-title">Registration Options Not Available for Past Events</h4>';
        echo '<div class="form-grid">';
        echo '<div class="form-item">';
        echo '<button type="submit" name="submitEventProcess">Process This Event</button>';
        echo '</div>';
        echo '</div>';
    }
 
//    echo '</div>';
echo '</fieldset>';
   }
   echo '<button type="submit" name="submitEventProcess">Process Events</button>'; 

}

echo '</form>';
echo '<br>';
echo '</div>';

echo '</section>';
echo '</div>';

?>

<footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>
