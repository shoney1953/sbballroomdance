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
    <title>SBDC Ballroom Dance - Archived Visitors</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="administration.php">Back to Administration</a></li>
        <li><a title="Return to Visitors" href="SBDCAVisitors.php">Back to Visitors</a></li>

      </ul>
    </div>

</nav>
<?php

            echo '<div class="container-section ">';
          
            echo '<section id="visitorsarchived" class="content">';
                echo '<h3 class="section-header">Visitors Archived</h3> '; 
                echo "<div class='form-grid3'>";
                echo '<div class="form-grid-div">';
                echo '<form method="POST" action="actions/reportVisitorsArchived.php">';
                echo '<label for="reportVisitor">Report on Archived Visitors </label><br>';  
                echo '<input type="checkbox" name="reportVisitor"><br>';
                echo '<button type="submit" name="submitVisitorRep">Report</button>';    
                echo '</form>';
                echo '</div>';   
                echo '</div>';  
                echo '<br>';
                
                echo '<table>';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Login Date</th> '; 
                        echo '<th>Logins #</th> '; 
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                        echo '<th>Notes</th>';
                      
                   echo '</tr>';
                   echo '</thead>';
                   echo '<tbody>';
                    
            
                    foreach($visitors as $visitor) {
                 
                          echo "<tr>";
                            echo "<td>".$visitor['logindate']."</td>";
                            echo "<td>".$visitor['numlogins']."</td>";
                            echo "<td>".$visitor['firstname']."</td>";               
                            echo "<td>".$visitor['lastname']."</td>";
                            echo "<td>".$visitor['email']."</td>"; 
                            echo "<td>".$visitor['notes']."</td>";           
                          echo "</tr>";
                      }
                 echo '</tbody>';
                echo '</table>';   
                echo '<br>';
 
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