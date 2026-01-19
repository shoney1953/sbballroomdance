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
              'joinedonline' => $joinedonline,
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
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
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
        <?php
           echo "<li><a href='index.php'>Back to Home</a></li> ";
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='SBDCAMembers.php'>Back to Members</a></li> ";
                echo "<li><a href='administration.php'>Back to Administration</a></li> ";
            

            }

?>

    </ul>
     </div>
</nav>  

   
   <div class="container">
    <?php

    if ($_SESSION['role'] === 'SUPERADMIN') {
        echo '<div class="container-section "> ';      
        echo '<section id="usersarchived" class="content">';
        echo ' <h3 class="section-header">Archived Member List</h3> ';
        echo '<form name="reportUserArchive" method="POST" action="actions/reportUserArchive.php">'; 
        echo  '<div class="form-grid2">';
        echo '<div class="form-grid-div">';

        echo '<button type="submit" name="submitUserRep">  Report Archived Members</button>';   

        echo '</form>';   
        echo '</div> ';  
        echo '<div class="form-grid-div">';
        echo '<form name="reportUserArchive" method="POST" action="actions/reportUserArchByMonth.php">'; 
        echo '<button type="submit" name="submitUserRep">  Report Archived Members by Month</button>';   

        echo '</form>';   
        echo '</div> ';   
        echo '<form target="_blank" method="POST" action="actions/searchUserArchive.php" >';
        echo '<input type="search"  name="search" ><br>';
        echo '<button type="submit" name="searchUser">Search Archived Members</button>'; 
        echo '</form>';
        
        echo '</div> ';    
        echo '<table>';
        echo "<thead>";
        echo '<tr>';
             
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Role</th>'; 
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                // echo '<th>HOA</th>';
                // echo '<th>Address</th>';
                echo '<th>Orig Created</th>';
                echo '<th>Archived</th>';
                echo '<th>Joined Online</th>';
                echo '</tr>';
                echo "</thead>";
                echo "<tbody>";
        
                foreach($users as $user) {
                    $hr = 'archmember.php?id=';
                    $hr .= $user["id"];
               
                    echo '<td> <a href="'.$hr.'">'.$user["id"].'</a></td>';
             
                        // echo "<td>".$user['id']."</td>"; 
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        // echo "<td>".$user['hoa']."</td>";
                        // echo "<td>".$user['streetAddress']."</td>"; 
                        echo "<td>".$user['memberorigcreated']."</td>"; 
                        echo "<td>".$user['created']."</td>"; 
                        echo "<td>".$user['joinedonline']."</td>"; 
              
                       
                        
                      echo "</tr>";
                  }
             
             echo "</tbody>"  ; 
            echo '</table><br>';  
          
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
</html>
