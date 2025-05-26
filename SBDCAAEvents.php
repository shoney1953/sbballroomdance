<?php
session_start();
require_once 'config/Database.php';

require_once 'models/ClassRegistrationArch.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/EventArch.php';
require_once 'models/DanceClassArch.php';
require_once 'models/UserArchive.php';
require_once 'models/VisitorsArch.php';

$_SESSION['archiveurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
$database = new Database();
$db = $database->connect();
$users = [];
$allClasses = [];
$visitors = [];
$num_visitors = 0;
$allEvents = [];
$classRegistrations = [];
$eventRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$num_classes = 0;
if ($_SESSION['role'] === 'SUPERADMIN') {
    $user = new Userarchive($db);
    $result = $user->read();
    
    $rowCount = $result->rowCount();
    $num_users = $rowCount;
    if($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'role' => $role,
                'email' => $email,
                'phone1' => $phone1,
                'password' => $password,
                'partnerId' => $partnerid,
                'hoa' => $hoa,
                'passwordChanged' => $passwordChanged,
                'memberorigcreated' => $memberorigcreated,
                'created' => $created,
                'streetAddress' => $streetaddress,
                'lastLogin' => $lastLogin
            );
            array_push($users, $user_item);
      
        }
   
    
    } 
$event = new EventArch($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'previd' => $previd,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
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
  

} 
$_SESSION['allArchEvents'] = $allEvents;

 
/* get event registrations */

$eventReg = new EventRegistrationArch($db);
$result = $eventReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'preveventid' => $preveventid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'ddattenddinner' => $ddattenddinner,
            'ddattenddance' => $ddattenddance,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    }
}
$_SESSION['archeventreg'] = $eventRegistrations;
/* get archived visitors */
$visitorArch = new VisitorArch($db);
$result = $visitorArch->read();

$rowCount = $result->rowCount();
$num_visitors = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'logindate' => date('m d Y h:i:s A', strtotime($logindate)),
            'notes' => $notes,
            'numlogins' => $numlogins
        );
        array_push($visitors, $reg_item);
  
    }
}

  
} // end of superadmin check
if (($_SESSION['role'] === 'SUPERADMIN') ||  ($_SESSION['role'] === 'INSTRUCTOR') ) {
 
    $class = new DanceClassArch($db);
    $result = $class->read();
    
    $rowCount = $result->rowCount();
    $num_classes = $rowCount;
    
    
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $class_item = array(
                'id' => $id,
                'previd' => $previd,
                'classname' => $classname,
                'classlevel' => $classlevel,
                'classlimit' => $classlimit,
                'date' => $date,
                'time' => date('h:i:s A', strtotime($time)),
                'instructors' => $instructors,
                'classnotes' => $classnotes,
                "registrationemail" => $registrationemail,
                "room" => $room,
                'numregistered' => $numregistered
            );
            array_push($allClasses, $class_item);
    
        }
    
    } 
    /* get class registrations */
    $_SESSION['allArchClasses'] = $allClasses;
    $classReg = new ClassRegistrationArch($db);
    $result = $classReg->read();
    
    $rowCount = $result->rowCount();
    $num_registrations = $rowCount;
    
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'archclassid' => $archclassid,
                'archclassid' => $archclassid,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'classid' => $classid,
                'classname' => $classname,
                'classdate' => $classdate,
                'classtime' => $classtime,
                'userid' => $userid,
                'email' => $email,
                'registeredby' => $registeredby,
                'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
            );
          
            array_push($classRegistrations, $reg_item);
      
        }   
    $_SESSION['archClassRegistrations'] = $classRegistrations;
    
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
    <title>SBDC Ballroom Dance - Archived Members</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="administration.php">Back to Administration</a></li>
        <li><a title="Return to Events" href="SBDCAEvents.php">Back to Events</a></li>

      </ul>
    </div>

</nav>
<?php
 echo "<div class='container-section '>";
 echo "<section id='eventsarchived' class='content'>";
 echo "<h3 class='section-header'>Archived Events</h3>";
 echo "<div class='form-grid3'>";
 echo "<div class='form-grid-div'>";
 echo "<h2 class='section-header'>Summary Report</h2>";
 echo "<form method='POST' action='actions/reportSummaryEvents.php'>"; 
 echo "<button type='submit' name='submitSummaryRep'>Summary Report</button>";   
 echo "</form>";
 echo "</div>";
 echo "<div class='form-grid-div'>";
 echo "<h2 class='section-header'>Event Report</h2>";
 echo "<form method='POST' action='actions/reportEventArchived.php'> ";

 echo "<input type='checkbox' name='reportEvent'>";
 echo "<label for='reportEvent'>Report on all or one Archived Event </label><br>";
 echo "<input type='text' class='text-small' name='eventId' >";    
 echo "<label for='eventId'><em> &larr; 
         Specify Event ID from Table below for Report on One Event: </em> </label>";
   
 echo "<br><button type='submit' name='submitEventRep'>Report Archived Events</button>";  
 echo "</div>";   

 echo "</form>";
 echo "</div> <br>";
 echo "<table>";
 echo "<thead>";
 echo "<tr>";
             echo "<th>ID</th>";
             echo "<th>Prev ID</th>";
             echo "<th>Date</th>";
             echo "<th>Name    </th>";
             echo "<th>Type    </th>";
             echo "<th>Description</th>";
             echo "<th>DJ</th>";
             echo "<th>ORG Email</th>";             
             echo "<th>Room</th>";
             echo "<th>Cost</th>";
             echo "<th># Attending</th>";
         echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
         $eventNumber = 0;
         foreach($allEvents as $event) {
              $eventNumber++;
              $hr = 'archevent.php?id=';
              $hr .= $event["id"];
         
             
               echo "<tr>";
               echo '<td> <a href="'.$hr.'">'.$event["id"].'</a></td>';
                 // echo "<td>".$event['id']."</td>";
                 echo "<td>".$event['previd']."</td>";
                 echo "<td>".$event['eventdate']."</td>";
                 echo "<td>".$event['eventname']."</td>";
                 echo "<td>".$event['eventtype']."</td>";
                 echo "<td>".$event['eventdesc']."</td>"; 
                 echo "<td>".$event['eventdj']."</td>";  
                 echo "<td>".$event['orgemail']."</td>";           
                 echo "<td>".$event['eventroom']."</td>";
                 echo "<td>".$event['eventcost']."</td>";
                 echo "<td>".$event['eventnumregistered']."</td>";
               echo "</tr>";
           }
      
     echo '</tbody>';
     echo "</table>";
  
     echo "</div>";
     echo "</section>";
    

?>
<footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>