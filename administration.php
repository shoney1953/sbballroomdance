<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Contact.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];

$allClasses = [];
$allEvents = [];
$contacts = [];
$users = [];
$classRegistrations = [];
$eventRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$num_classes = 0;
$memberStatus1 = [];
$memberStatus2 = [];
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 

$database = new Database();
$db = $database->connect();
// refresh events

if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
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
        array_push( $allEvents, $event_item);
    
    }
  

} 
/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();
$num_classes = $rowCount;

if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $class_item = array(
            'id' => $id,
            'classname' => $classname,
            'classlevel' => $classlevel,
            'classlimit' => $classlimit,
            'date' => $date,
            'time' => date('h:i:s A', strtotime($time)),
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            'numregistered' => $numregistered
        );
        array_push( $allClasses, $class_item);

    }

} 
/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classdate' => $classdate,
            'classtime' => $classtime,
            'userid' => $userid,
            'email' => $email,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
      
        array_push( $classRegistrations, $reg_item);
  
    }
  

} 
/* get class registrations */
$eventReg = new EventRegistration($db);
$result = $eventReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;

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
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push( $eventRegistrations, $reg_item);
  
    }
  

} 
/* get contacts */
$contact = new Contact($db);
$result = $contact->read();

$rowCount = $result->rowCount();
$num_contacts = $rowCount;
if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $contact_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'message' => $message,
            'email' => $email,
            'danceFavorite' => $danceFavorite,
            'danceExperience' => $danceExperience,
            "contactdate" => date('m d Y h:i:s A', 
            strtotime($contactdate))
           
        );
        array_push( $contacts, $contact_item);
  
    }
  $_SESSION['contacts'] = $contacts;

} 
$num_users = 0;
if ($_SESSION['role'] === 'SUPERADMIN') {
    $user = new User($db);
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
                'passwordChanged' => $passwordChanged
            );
            array_push( $users, $user_item);
      
        }
   
    
    } 
    $memPaid = new MemberPaid($db);
    $result = $memPaid->read_byYear($nextYear);
    
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
                'year' => $year,
                'paid' => $paid

            );
            array_push( $memberStatus2, $member_item);
      
        }
     $_SESSION['memberStatus2'] = $memberStatus2;
    
    } 
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
                'year' => $year,
                'paid' => $paid

            );
            array_push( $memberStatus1, $member_item);
      
        }
     $_SESSION['memberStatus1'] = $memberStatus1;
    
    } 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance Beta - Admin</title>
</head>
<body>
<nav class="nav">
    <div class="container">
  
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="#classes">Classes</a></li>
        <li><a href="#classregistrations">Class Registrations</a></li>
        <li><a href="#eventregistrations">Event Registrations</a></li>
        <li><a href="#contacts">Contacts</a></li>
        <?php
        if ($_SESSION['role'] === 'SUPERADMIN') {
            echo '<li><a href="#users">Users</a></li>';
            echo '<li><a href="#membership">Membership</a></li>';
        }
        ?>
    </ul>
     </div>
</nav>
    <div class="container-section" >
    <br>
    <br>
    <br>
    <h1 style="text-align: center; margin-top: 40px; color:white">Administrative Functions for SaddleBrooke Ballroom Dance Club</h1>
    </div>
    
 
    <div class="container-section ">
    <section id="events" class="content">
       <br><br>
        <h1 class="section-header">Events</h1><br>
        <table>
            <tr>
                <th>ID</th>
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
        <br>
        <div class="form-grid2">
       
        <form method='POST' action="actions/maintainEvent.php">
        
        <div class="form-grid-div">
        <h4>Maintain Events</h4>
        <form method='POST' action="actions/maintainEvent.php">
        <input type='checkbox' name='updateEvent'>
        <label for='updateEvent'>Update an Event </label><br>   
        <input type='checkbox' name='deleteEvent'>
        <label for='deleteEvent'>Delete an Event </label><br> 
        <input type='text' class='text-small' name='eventId' >
        <label for='eventId'><em> &larr; 
            Specify Event ID from Table above for Update or Delete: 
            </em> </label>

        <p>OR</p>
        <input type='checkbox' name='addEvent'>
        <label for='addEvent'>Add an Event </label> <br> 
       
        <button type='submit' name="submitEvent">Submit</button>   
        </div>   
        </form>
        <form method='POST' action="actions/reportEvent.php"> 
        <div class="form-grid-div">
        <h4>Report Events</h4>
        <input type='checkbox' name='reportEvent'>
        <label for='reportEvent'>Report on all or one Event </label><br>
        <input type='text' class='text-small' name='eventId' >    
        <label for='eventId'><em> &larr; 
            Specify Event ID from Table above for Report on One Event: </em> </label>
      
        <br>
        <button type='submit' name="submitEventRep">Report</button>   
        </div>   
        </form>
      
        </div>
    </section>
    </div>
    <div class="container-section ">
    
    <section id="eventregistrations" class="content">
    <br><br>
        <h1 class="section-header">Event Registrations</h1><br>    
        <table>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Id</th>
                <th>Event Date</th>
                <th>First Name</th>
                <th>Last Name    </th>
                <th>Email</th>
                <th>Paid</th>
                <th>Date Reg</th>          
            </tr>
            <?php 
    
            foreach($eventRegistrations as $eventRegistration) {
          
    
                  echo "<tr>";
                    echo "<td>".$eventRegistration['id']."</td>";
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventid']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";
                    echo "<td>".$eventRegistration['firstname']."</td>";
                    echo "<td>".$eventRegistration['lastname']."</td>";
                    echo "<td>".$eventRegistration['email']."</td>"; 
         
                    if ($eventRegistration['paid'] == true ) {
                        echo "<td>&#10004;</td>"; 
                      } else {
                          echo "<td>&times;</td>"; 
                      }          
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
        <div class="form-grid1">
        
        <form method='POST' action="actions/maintainEventReg.php">
        
        <div class="form-grid-div">
        <h4>Maintain event Registrations</h4>
        <input type='checkbox' name='updateReg'>
        <label for='updateReg'>Update a Event Registration </label><br>   
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete a Event Registration </label><br>
        <input type='text' class='text-small' name='regId' >
        <label for='regId'><em> &larr; 
            Specify Registration ID from Table above for Update or Delete:  
            </em></label>
        <p>OR</p>
        <input type='checkbox' name='addReg'>
        <label for='addReg'>Add a Event Registration</label> <br> 
       
        <button type='submit' name="submitEventReg">Submit</button>   
        </div>   
        </form>
        </div>
       
        </section>
    </div>
   
    <div class="container-section ">
    <section id="classes" class="content">
   
      <br><br>
        <h1 class="section-header">Classes</h1><br>
        <table>
            <tr>
           
                <th>ID   </th>   
                <th>Date    </th>
                <th>Time    </th>
                <th>Room    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Registration Email </th>
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
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
                    echo "<td>".$class['numregistered']."</td>";
   
                echo "</tr>";
                
              }
             
            ?> 
        </table>
        <br>
        <div class="form-grid2">
        
        <form method='POST' action="actions/maintainClass.php">
        
        <div class="form-grid-div">
        <h4>Maintain Classes</h4>
        
        <input type='checkbox' name='updateClass'>
        <label for='updateClass'>Update a Class </label><br>   
        <input type='checkbox' name='deleteClass'>
        <label for='deleteClass'>Delete a Class </label><br>
        <input type='text' class='text-small' name='classId' >
        <label for='classId'> <em> &larr; 
         Specify Class ID from Table above for Update or Delete: </em> </label>     
        <p>OR</p>
        <input type='checkbox' name='addClass'>
        <label for='addClass'>Add a Class </label> <br> 
       
        <button type='submit' name="submitClass">Submit</button>   
        </div>   
        </form>
        <form method='POST' action="actions/reportClass.php"> 
        <div class="form-grid-div">
        <h4>Report Classes</h4>
        <input type='checkbox' name='reportClass'>
        <label for='reportClass'>Report on all or one Class </label>    
        <label for='classId'><em> &rarr; 
            Specify Class ID from Table above for Report on One Class: </em> </label>
        <input type='text' class='text-small' name='classId' >
        <br>
        <button type='submit' name="submitClassRep">Report</button>   
        </div>   
        </form>
        
            </div>
    </section>
    </div>
   
    <div class="container-section ">
    
    <section id="classregistrations" class="content">
    <br><br>
        <h1 class="section-header">Class Registrations</h1><br>    
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
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
        <div class="form-grid1">
        
        <form method='POST' action="actions/maintainClassReg.php">
        
        <div class="form-grid-div">
        <h4>Maintain Class Registrations</h4>
        <input type='checkbox' name='updateReg'>
        <label for='updateReg'>Update a Class Registration </label><br>    
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete a Class Registration </label><br>
        <input type='text' class='text-small' name='regId' >
        <label for='regId'><em> &larr; Specify Registration ID from
             Table above for Update or Delete: </em></label>

        <p>OR</p>
        <input type='checkbox' name='addReg'>
        <label for='addReg'>Add a Class Registration</label> <br> 
       
        <button type='submit' name="submitReg">Submit</button>   
        </div>   
        </form>
        </div>
       
        </section>
    </div>
  

    <div class="container-section ">
    <br><br>
    <section id="contacts" class="content">
         <h1 class="section-header">Contacts</h1><br>  
        <table>
            <tr>
                <th>Date Contacted</th>  
                <th>First Name</th>
                <th>Last Name    </th>
                <th>Email</th>
                <th>Message</th> 
                <th>Favorite Dance Style</th>
                <th>Dance Experience</th>        
             
            </tr>
            <?php 
    
            foreach($contacts as $contact) {
         
                  echo "<tr>";
                    echo "<td>".$contact['contactdate']."</td>";
                    echo "<td>".$contact['firstname']."</td>";               
                    echo "<td>".$contact['lastname']."</td>";
                    echo "<td>".$contact['email']."</td>";
                    echo "<td>".$contact['message']."</td>"; 
                    echo "<td>".$contact['danceFavorite']."</td>"; 
                    echo "<td>".$contact['danceExperience']."</td>";             
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
        <div class="form-grid3">
      
        <form method='POST' action="actions/maintainContact.php">
        <div class="form-grid-div">
        <h4>Maintain Contacts</h4>
        <input type='checkbox' name='deleteContact'>
        <label for='deleteContact'>Delete a Range of Contacts</label><br>
        <input type='date'  name='delContactBefore' >
        <label for='delContactBefore'><em> &larr; Specify a Date 
            to delete contacts before: </em></label><br>
        <button type='submit' name="submitContact">Submit</button> 
        </div>
        </form>
        <div class="form-grid-div">
        <h4>Report Contacts</h4>
        <form method='POST' action="actions/reportContact.php">
        <input type='checkbox' name='reportContact'>
        <label for='reportContact'>Report on Contacts </label><br>    
        <button type='submit' name="reportContact">Report</button> 
      
        </div>     
        </form>
        <br>
        
    
        </div>
    </section>
    </div>
    <?php
    if ($_SESSION['role'] === 'SUPERADMIN') {
        echo '<div class="container-section ">  <br><br>';
       
        echo '<section id="users" class="content">';
        echo ' <h1 class="section-header">Users</h1><br> ';
     
        echo '<table>';
        echo '<tr>';
             
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Role</th>'; 
                echo '<th>Email</th>';
                echo '<th>Phone</th>';
                echo '<th>Password Changed</th>';     
                echo '</tr>';
                
        
                foreach($users as $user) {
             
             
                        echo "<td>".$user['id']."</td>"; 
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['passwordChanged']."</td>"; 
                        
                      echo "</tr>";
                  }
             
                
            echo '</table><br>';       
            echo '<div class="form-grid3">';
          
            echo '<form method="POST" action="actions/maintainUser.php">';
            echo '<div class="form-grid-div">';
            echo '<h4>Maintain Users</h4>';
            echo '<input type="checkbox" name="updateUser">';
            echo '<label for="updateUser">Update a User</label><br>';   
            echo '<input type="checkbox" name="deleteUser">';
            echo '<label for="deleteUser">Delete a User </label><br>';
            echo '<input type="text" class="text-small" name="userId" >';
            echo '<label for="userId"><em> &larr; Specify User ID from Table above for Update or Delete:  </em></label>';
            
            echo '<p>OR</p>';
            echo '<input type="checkbox" name="addUser">';
            echo '<label for="addUser">Add a User</label> <br>';
               
            echo '<button type="submit" name="submitUser">Submit</button>';  
            echo '</form> <br>';
            echo '</div>';
            echo '<form method="POST" action="actions/reportUser.php">'; 
            echo '<div class="form-grid-div">';
            echo '<h4>Report Users</h4>';
            echo '<input type="checkbox" name="reportUsers">';
            echo '<label for="reportUsers">Report Users</label><br>';    
          
            echo '<button type="submit" name="submitUserRep">Report</button>';   
            echo '</div> ';  
            echo '</form>';
            echo '</section>';
      

        echo '<section id="membership" class="content">';
        echo ' <h1 class="section-header">Membership Maintenance</h1><br> ';
        echo '<div class="form-grid3">';
        echo '<div class="form-grid-div">';  
        echo '<form method="POST" action="actions/updateMemberPaid.php">';
        echo '<table>';
        echo '<tr>';
    
                echo '<th>Year</th>'; 
                echo '<th>ID</th>'; 
                echo '<th>Userid</th>'; 
                echo '<th>Paid UP?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '</tr>';
                    
                foreach ($memberStatus1 as $memStat) {
             
                      echo "<tr>";
                   
                        echo "<td>".$memStat['year']."</td>";
                        echo "<td>".$memStat['id']."</td>"; 
                        echo "<td>".$memStat['userid']."</td>"; 
                        if ($memStat['paid'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<em><td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>";  

                 
                      echo "</tr>";
                  }

            echo '</table><br>'; 
            echo '<input type=hidden name="thisyear" value="1">';
            echo '<button type="submit" name="updateMemPaid">UPDATE</button>'; 
            echo '</form>';
        echo '</div>';
        echo '<form method="POST" action="actions/updateMemberPaid.php">';
        echo '<table>';
        echo '<tr>';
    
                echo '<th>Year</th>'; 
                echo '<th>ID</th>'; 
                echo '<th>Userid</th>'; 
                echo '<th>Paid UP?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '</tr>';
                    
                foreach ($memberStatus2 as $memStat) {
             
                      echo "<tr>";
                   
                        echo "<td>".$memStat['year']."</td>";
                        echo "<td>".$memStat['id']."</td>"; 
                        echo "<td>".$memStat['userid']."</td>"; 
                        if ($memStat['paid'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<em><td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>";  

                 
                      echo "</tr>";
                  }

            echo '</table><br>'; 
            echo '<input type=hidden name="nextyear" value="1">';
            echo '<button type="submit" name="updateMemPaid">UPDATE</button>'; 
            echo '</form>';
        echo '<div class="form-grid-div">';  
        echo '</div>';
      /*  echo '<div class="form-grid-div">';          
        echo '<form method="POST" action="actions/createMemYear.php">';
       
        echo '<h4>Maintain Membership Data - populate year</h4>';
        echo '<input type="checkbox" name="populateYear">';
        echo '<label for="populateYear">Create Membership Paid records for a year based on user data
             (should only be done once for a year)</label><br>';
     
        echo '<input type="number"  name="populateYear" min="2021"  required />';
        echo '<label for="year"><em> &larr; Specify 4 digit year</em></label><br>';
        echo '<button type="submit" name="createMemYear">
             Populate MemberPaid data</button>';
        echo '</div> ';  
        echo '</form>'; 
        echo '</div>';  */
        echo '</section>';
        echo '</div>';

    }
}
    ?>

    <footer >
    <?php
    require 'footer.php';
   ?>
   
</body>
</html>