<?php
session_start();
require_once 'config/Database.php';

require_once 'models/ClassRegistrationArch.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/EventArch.php';
require_once 'models/DanceClassArch.php';
require_once 'models/UserArchive.php';
require_once 'models/VisitorsArch.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
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
    <title>SBDC Ballroom Dance - Archived Classes</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration" href="administration.php">Back to Administration</a></li>
        <li><a title="Return to Classes" href="SBDCAClasses.php">Back to Classes</a></li>
      </ul>
    </div>

</nav>
<?php
if (($_SESSION['role'] === 'SUPERADMIN') || ($_SESSION['role'] === 'INSTRUCTOR'))
      {
        echo "<br> <div class='container-section '>";
        echo "<section id='classesarchived' class='content'>";
        echo "<br><br><h3 class='section-header'>Archived Classes</h3>";

        echo "<div class='form-grid3'>";
        
        echo "<div class='form-grid-div'>";
     
        echo "<form method='POST' action='actions/reportSummaryClasses.php'>";
        echo "<button type='submit' name='submitSummaryRep'>Summary Report</button>";   
        echo "</form>";
        echo "</div>";
        
        echo "<div class='form-grid-div'>";
        echo "<form method='POST' action='actions/reportClassArchive.php'>"; 

        echo "<input type='checkbox' name='reportClass'>";
        echo "<label for='reportClass'>Report on all or one Archived Class </label><br> "; 
        echo "<label for='classId'><em>Specify Class ID from Table below to Report on One Class. </em> </label><br>";
        echo "<input type='text' class='text-small' name='classId' ><br>"; 
     
        echo "<button type='submit' name='submitClassRep'>Class Report</button>";  
          echo "</form>";
          echo "</div> ";

          echo "<div class='form-grid-div'>";
          echo "<form method='POST' action='actions/archEmail.php'>"; 
       
          echo "<label for='classId'><em>Specify Class ID from Table below to create email. </em> </label>";
          echo "<input type='text' class='text-small' name='classId'>"; 
         
          echo '<br><button type="submit" name="submitArchEmail">  Generate Email</button>';   
          echo "</form>";
          echo "</div> ";

          echo "</div>";
       
          echo "<table>";
          echo "<thead>";
          echo "<tr>"; 
          echo "<th>ID</th>";                
          echo "<th>Date</th>";
          echo "<th>Time</th>";
          echo "<th>Room</th>";
          echo "<th>Name    </th>";
          echo "<th>Level    </th>";
          echo "<th>Reg Email</th>";
          echo "<th>Notes</th>";
          echo "<th>Instructors</th>";             
          echo "<th>Limit</th>";
          echo "<th># Attending</th>";
          echo "</tr>" ;    
          echo "</thead>"  ;
          echo "<tbody>";
              foreach($allClasses as $class)
                { 
                    echo "<tr>";
                    $hr = 'archclass.php?id=';
                      $hr .= $class["id"];
                  
                      echo '<td> <a href="'.$hr.'">'.$class["id"].'</a></td>';
                      // echo "<td>".$class['id']."</td>";
                      echo "<td>".$class['date']."</td>";
                      echo "<td>".$class['time']."</td>";
                      echo "<td>".$class['room']."</td>";
                      echo "<td>".$class['classname']."</td>";
                      echo "<td>".$class['classlevel']."</td>";
                      echo "<td>".$class['registrationemail']."</td>";
                      echo "<td>".$class['classnotes']."</td>";
                      echo "<td>".$class['instructors']."</td>";
                      echo "<td>".$class['classlimit']."</td>";
                      echo "<td>".$class['numregistered']."</td>";
      
                  echo "</tr>";
                  
                }
                echo "</tbody>";
                echo "</table";
            
                echo '<br>';
 
            echo '</section>';
            echo '</div>'; 
              }
                ?>

   
</body>
</html>