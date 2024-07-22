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
    <title>SBDC Ballroom Dance - Archived files</title>
</head>
<body>

<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
            if ($_SESSION['role'] === 'SUPERADMIN') {
                echo "<li><a href='administration.php'>Back to Administration</a></li> ";
                echo "<li><a href='#usersarchived'>Archived Members</a></li>";
                echo "<li><a href='#eventsarchived'>Archived Events</a></li>";
                echo "<li><a href='#visitorsarchived'>Visitors</a></li>";
            }
            if (($_SESSION['role'] === 'SUPERADMIN') ||  ($_SESSION['role'] === 'INSTRUCTOR'))  {
         
                echo "<li><a href='#classesarchived'>Archived Classes</a></li>";
        
            }
?>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
   <div class="container">
    <?php

    if ($_SESSION['role'] === 'SUPERADMIN') {
        echo '<div class="container-section ">  <br><br>';
       
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
        echo '<input type="text"  name="search" ><br>';
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
              
                       
                        
                      echo "</tr>";
                  }
             
             echo "</tbody>"  ; 
            echo '</table><br>';  
          
            echo '</section>';
            echo '</div>';
    
   
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
                echo "<th># Reg </th>";
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
       


    echo "</section> ";

            echo '<br>';
            echo '<div class="container-section ">';
            echo '<br><br>';
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
                    }
        if (($_SESSION['role'] === 'SUPERADMIN') || ($_SESSION['role'] === 'INSTRUCTOR'))
                    {
                      echo "<br> <div class='container-section '>";
                      echo "<section id='classesarchived' class='content'>";
                      echo "<br><br><h3 class='section-header'>Archived Classes</h3>";
                      echo "<div class='form-grid3'>";
                      echo "<div class='form-grid-div'>";
                      echo "<h2 class='section-header'>Summary Report</h2>";
                      echo "<form method='POST' action='actions/reportSummaryClasses.php'>";
                      echo "<button type='submit' name='submitSummaryRep'>Summary Report</button>";   
                      echo "</form>";
                      echo "</div>";
                      
                      echo "<div class='form-grid-div'>";
                      echo "<form method='POST' action='actions/reportClassArchive.php'>"; 
                      echo "<h2 class='section-header'>Report Classes</h2>";
                      echo "<input type='checkbox' name='reportClass'>";
                      echo "<label for='reportClass'>Report on all or one Archived Class </label><br> "; 
                      echo "<input type='text' class='text-small' name='classId' >"; 
                      echo "<label for='classId'><em> &larr; Specify Class ID from Table below to Report on One Class. </em> </label>";
                        echo "</form>";
                        echo "</div> ";

                        echo "<div class='form-grid-div'>";
                        echo "<form method='POST' action='actions/archEmail.php'>"; 
                        echo "<h2 class='section-header'>Email Registrants from a previous class.</h2>";
                        echo "<input type='text' class='text-small' name='classId'>"; 
                        echo "<label for='classId'><em> &larr; Specify Class ID from Table below to create email. </em> </label>";
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
                        echo "<th># Reg </th>";
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
                         
                              echo "</section>";
                              echo "</div>";
  

                }


                


                ?>
   </div>

</body>
</html>