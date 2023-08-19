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
            'eventdj' => $eventdj,
            "eventdesc" => html_entity_decode($eventdesc),
            "eventroom" => $eventroom,
            'eventnumregistered' => $eventnumregistered
        );
        array_push($allEvents, $event_item);
    
    }
  

} 
/* get classes */

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
} 
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
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="administration.php">Back to Administration</a></li>
        <li><a href="#usersarchived">Archived Members</a></li>
        <li><a href="#eventsarchived">Archived Events</a></li>
        <li><a href="#eventregistrationsarchived">Archived Event Registrations</a></li>
        <li><a href="#classesarchived">Archived Classes</a></li>
        <li><a href="#classregistrationsarchived">Archived Class Registrations</a></li>
        <li><a href="#visitorsarchived">Visitors</a></li>


    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
    <!-- <div class="content">
    <br><br>
    <h3 class="section-header">Summary Report</h3>
      <div class="form-grid3">
        <div class="form-grid-div">
         <h2 class="section-header">Summary Report</h2>
        <form method='POST' action="actions/reportSummaryActivity.php"> 
        <button type='submit' name="submitSummaryRep">Summary Report</button>   
        </form>
        </div> 
      </div> -->
    <?php

    if ($_SESSION['role'] === 'SUPERADMIN') {
        echo '<div class="container-section ">  <br><br>';
       
        echo '<section id="usersarchived" class="content">';
        echo ' <h3 class="section-header">Archived Member List</h3> ';
        echo '<form method="POST" action="actions/reportUserArchive.php">'; 
        echo  '<div class="form-grid2">';
        echo '<div class="form-grid-div">';
     
        echo '<input type="checkbox" name="reportUsers">';
        echo '<label for="reportUsers">Report Archived Members</label><br>';    
      
        echo '<button type="submit" name="submitUserRep">  Report Members</button>';   
    
        echo '</form>';   
        echo '</div> ';    
        echo '<form target="_blank" method="POST" action="actions/searchUserArchive.php" >';
        echo '<input type="text"  name="search" ><br>';
        echo '<button type="submit" name="searchUser">Search Archived Members</button>'; 
        echo '</form>';
        
        echo '</div> ';    
        echo '<table>';
        echo '<tr>';
             
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Role</th>'; 
                echo '<th>Part ID</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                echo '<th>HOA</th>';
                echo '<th>Address</th>';
                echo '<th>Last Login</th>';
                echo '<th>PWD Changed</th>';
                echo '</tr>';
                
        
                foreach($users as $user) {
             
             
                        echo "<td>".$user['id']."</td>"; 
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['partnerId']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['hoa']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
                        echo "<td>".$user['lastLogin']."</td>"; 
                        echo "<td>".$user['passwordChanged']."</td>"; 
                       
                        
                      echo "</tr>";
                  }
             
                
            echo '</table><br>';  
          
            echo '</section>';
            echo '</div>';
    
    }
  ?>  
    <div class="container-section ">
    <section id="eventsarchived" class="content">
    <h3 class="section-header">Archived Events</h3>
        <div class="form-grid3">
        <div class="form-grid-div">
         <h2 class="section-header">Summary Report</h2>
        <form method='POST' action="actions/reportSummaryEvents.php"> 
        <button type='submit' name="submitSummaryRep">Summary Report</button>   
        </form>
        </div> 
        <div class="form-grid-div">
        <h2 class="section-header">Event Report</h2>
        <form method='POST' action="actions/reportEventArchived.php"> 

        <input type='checkbox' name='reportEvent'>
        <label for='reportEvent'>Report on all or one Archived Event </label><br>
        <input type='text' class='text-small' name='eventId' >    
        <label for='eventId'><em> &larr; 
            Specify Event ID from Table below for Report on One Event: </em> </label>
      
        <br>
        <button type='submit' name="submitEventRep">Report Archived Events</button>   
        </div>   

        </form>
        </div> 
        <br>
        <table>
            <tr>
                <th>ID</th>
                <th>Prev ID</th>
                <th>Event Date</th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th> 
                <th>Event DJ</th>          
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Reg </th>
            </tr>
            <?php 
            $eventNumber = 0;
            foreach($allEvents as $event) {
                 $eventNumber++;
                  echo "<tr>";
                    echo "<td>".$event['id']."</td>";
                    echo "<td>".$event['previd']."</td>";
                    echo "<td>".$event['eventdate']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".$event['eventdj']."</td>";           
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                  echo "</tr>";
              }
         
            ?> 
        </table>
     
        </div>
            </section>
        <div class="container-section ">
  
    <section id="eventregistrationsarchived" class="content">
    <br><br>
        <h3 class="section-header">Archived Event Registrations</h3>   
        <table>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Id</th>
                <th>Prev Event Id</th>
                <th>Event Date</th>
                <th>First Name</th>
                <th>Last Name    </th>
                <th>Email</th>
                <th>Paid</th>
                <th>Attend Dinner</th>
                <th>Attend Dance</th>
                <th>Message</th>
                <th>Date Reg</th>  
                <th>Reg By</th>          
            </tr>
            <?php 
    
            foreach($eventRegistrations as $eventRegistration) {
          
    
                  echo "<tr>";
                    echo "<td>".$eventRegistration['id']."</td>";
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventid']."</td>";
                    echo "<td>".$eventRegistration['preveventid']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";
                    echo "<td>".$eventRegistration['firstname']."</td>";
                    echo "<td>".$eventRegistration['lastname']."</td>";
                    echo "<td>".$eventRegistration['email']."</td>"; 
         
                    if ($eventRegistration['paid'] == true ) {
                        echo "<td>&#10004;</td>"; 
                      } else {
                          echo "<td>&times;</td>"; 
                      } 
                      if ($eventRegistration['ddattenddinner'] == true ) {
                        echo "<td>&#10004;</td>"; 
                      } else {
                          echo "<td>&times;</td>"; 
                      } 
                      if ($eventRegistration['ddattenddance'] == true ) {
                        echo "<td>&#10004;</td>"; 
                      } else {
                          echo "<td>&times;</td>"; 
                      }
                    echo "<td>".$eventRegistration['message']."</td>";         
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
                    echo "<td>".$eventRegistration['registeredby']."</td>";
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
        <div class="container-section ">
        </div>
    </section>
    <section id="classesarchived" class="content">
      <br><br>
        <h3 class="section-header">Archived Classes</h3>
        <div class="form-grid3">
        <div class="form-grid-div">
         <h2 class="section-header">Summary Report</h2>
        <form method='POST' action="actions/reportSummaryClasses.php"> 
        <button type='submit' name="submitSummaryRep">Summary Report</button>   
        </form>
        </div> 
        <div class="form-grid-div">
        <form method='POST' action="actions/reportClassArchive.php"> 

        <h2 class="section-header">Report Classes</h2>
        <input type='checkbox' name='reportClass'>
        <label for='reportClass'>Report on all or one Archived Class </label><br>  
        <input type='text' class='text-small' name='classId' > 
        <label for='classId'><em> &larr; 
            Specify Class ID from Table above for Report on One Class: </em> </label>
       
        <br>
        <button type='submit' name="submitClassRep">Report</button>   
  
        </form>
        </div> 
        </div>
        <table>
            <tr>
           
                <th>ID   </th>   
                <th>Start Date</th>
                <th>Time    </th>
                <th>Room    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Registration Email </th>
                <th>Notes</th>
                <th>Instructors    </th>
                <th>Class Limit    </th>
                <th># Reg </th>
               
             
            </tr>
            <?php 
           
            foreach($allClasses as $class)
             { 
                  echo "<tr>";
                    echo "<td>".$class['id']."</td>";
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
             
            ?> 
        </table>
        <br>
    </section>
    </section>
        <div class="container-section ">
    
    <section id="classregistrationsarchived" class="content">
    <br><br>
        <h3 class="section-header">Archived Class Registrations</h3>    
        <table>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Id</th>
                <th>Class Date</th>
                <th>Class Time</th>
                <th>First Name</th>
                <th>Last Name    </th>
                <th>Email</th>
                <th>Date Reg</th> 
                <th>Reg By</th>             
            </tr>
            <?php 
    
            foreach($classRegistrations as $classRegistration) {
          
    
                  echo "<tr>";
                    echo "<td>".$classRegistration['id']."</td>";
                    echo "<td>".$classRegistration['classname']."</td>";
                    echo "<td>".$classRegistration['classid']."</td>";
                    echo "<td>".$classRegistration['classdate']."</td>";
                    echo "<td>".$classRegistration['classtime']."</td>";
                    echo "<td>".$classRegistration['firstname']."</td>";
                    echo "<td>".$classRegistration['lastname']."</td>";
                    echo "<td>".$classRegistration['email']."</td>";           
                    echo "<td>".$classRegistration['dateregistered']."</td>";
                    echo "<td>".$classRegistration['registeredby']."</td>";
             
                  echo "</tr>";

              }
         
            ?> 
        </table>
        </div>
        </section>

        <br>
        <?php
            echo '<div class="container-section ">';
            echo '<br><br>';
            echo '<section id="visitorsarchived" class="content">';
                echo '<h3 class="section-header">Visitors Archived</h3> '; 
              
                echo '<div class="form-grid-div">';
                echo '<form method="POST" action="actions/reportVisitorsArchived.php">';
                echo '<label for="reportVisitor">Report on Archived Visitors </label><br>';  
                echo '<input type="checkbox" name="reportVisitor"><br>';
                echo '<button type="submit" name="submitVisitorRep">Report</button>';    
                echo '</form>';
                echo '</div>';   
                echo '<br>';
                
            
                echo '<table>';
                    echo '<tr>';
                        echo '<th>Login Date</th> '; 
                        echo '<th>Logins #</th> '; 
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                        echo '<th>Notes</th>';
                      
                   echo '</tr>';
                    
            
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
                 
                echo '</table>';   
                echo '<br>';
 
            echo '</section>';
            echo '</div>'; 

         
        ?>
<?php
  include 'footer.php';
?>
</body>
</html>