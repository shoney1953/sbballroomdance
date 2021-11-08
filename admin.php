<?php
session_start();
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];

include_once 'config/Database.php';
include_once 'models/Contact.php';
include_once 'models/ClassRegistration.php';
include_once 'models/Event.php';
include_once 'models/DanceClass.php';

$allClasses = [];
$allEvents = [];
$contacts = [];
$classRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$num_classes = 0;

$database = new Database();
$db = $database->connect();
// refresh events
$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;

if($rowCount > 0) {

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
  

} else {
   echo 'NO EVENTS';

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
            'time' => $time,
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            'numregistered' => $numregistered
        );
        array_push( $allClasses, $class_item);

    }

} else {
   echo 'NO CLASSES';

}
/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;

if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'email' => $email,
            "dateregistered" => $dateregistered
        );
        array_push( $classRegistrations, $reg_item);
  
    }
  

} else {
   echo 'NO REGISTRATIONS';

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
            "contactdate" => $contactdate
        );
        array_push( $contacts, $contact_item);
  
    }
  $_SESSION['contacts'] = $contacts;

} else {
   echo 'NO Contacts';

}


//$conn->close();
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
     <h1 class="logo"><a href="index.html">SBDC Ballroom Dance Club</a></h1>
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="#classes">Classes</a></li>
        <li><a href="#registrations">Class Registrations</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="#contacts">Contacts</a></li>
    </ul>
     </div>
</nav>
    <br>
    <br>
    <div class="section-back">
    <section id="events" class="container content">

      <br>
        <h1 class="section-header">All Events</h1><br>
        <table>
            <tr>
                <th>Event ID</th>
                <th>Event Date</th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th> 
                <th>Event DJ</th>          
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Registered </th>
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
        <div class="form-grid1">
            <h3>Maintain Events</h3>
        <form method='POST' action="actions/maintainEvent.php">
        <label for='eventId'>Specify Event ID from Table above for:  </label>
        <input type='text' class='text-small' name='eventId' >
        <input type='checkbox' name='updateEvent'>
        <label for='updateEvent'>Update an Event </label>    
        <input type='checkbox' name='deleteEvent'>
        <label for='deleteEvent'>Delete an Event </label><br> 
        <p>OR</p><br>
        <input type='checkbox' name='addEvent'>
        <label for='addEvent'>Add an Event </label> <br> 
       
        <button type='submit' name="submitEvent">Submit</button>      
        </form>
        </div>
    </section>
    </div>
    
    <br>
    <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
        <h1 class="section-header">All Classes</h1><br>
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
                <th># Registered </th>
               
             
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
        <div class="form-grid1">
        <h3>Maintain Classes</h3>
        <form method='POST' action="actions/maintainClass.php">
        <label for='classId'>Specify Class ID from Table above for:  </label>
        <input type='text' class='text-small' name='classId' >
        <input type='checkbox' name='updateClass'>
        <label for='updateClass'>Update a Class </label>    
        <input type='checkbox' name='deleteClass'>
        <label for='deleteClass'>Delete a Class </label><br> 
        <p>OR</p><br>
        <input type='checkbox' name='addClass'>
        <label for='addClass'>Add a Class </label> <br> 
       
        <button type='submit' name="submitClass">Submit</button>      
        </form>
        </div>
    </section>
    </div>
    <div class="section-back">
    <section id="registrations" class="container content">
        <h1>Class Registrations</h1>    
        <table>
            <tr>
                <th>Registration Id</th>
                <th>Class Name</th>
                <th>Class id</th>
                <th>Class Date</th>
                <th>First Name</th>
                <th>Last Name    </th>
                <th>Email</th>
                <th>Date</th>          
            </tr>
            <?php 
    
            foreach($classRegistrations as $classRegistration) {
                $className = 'NONE';
                foreach($allClasses as $class) {
                    if($classRegistration['classid'] == $class['id']) {
                        $className = $class['classname'];
                        $classDate = $class['date'];
                    }
                }
    
                  echo "<tr>";
                    echo "<td>".$classRegistration['id']."</td>";
                    echo "<td>".$className."</td>";
                    echo "<td>".$classRegistration['classid']."</td>";
                    echo "<td>".$classDate."</td>";
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
            <h3>Maintain Class Registrations</h3>
        <form method='POST' action="actions/maintainReg.php">
        <label for='regId'>Specify Registration ID from Table above for:  </label>
        <input type='text' class='text-small' name='regId' >
        <input type='checkbox' name='updateReg'>
        <label for='updateReg'>Update a Class Registration </label>    
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete a Class Registration </label><br> 
        <p>OR</p><br>
        <input type='checkbox' name='addReg'>
        <label for='addReg'>Add a Class Registration</label> <br> 
       
        <button type='submit' name="submitReg">Submit</button>      
        </form>
        </div>
            </section>
    </div>
   
    <div class="section-back">
    <section id="contacts" class="container content">
        <h1>Contacts</h1>    
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
        <div class="form-grid1">
        <h3>Maintain Contacts</h3>
        <form method='POST' action="actions/maintainContact.php">
        <label for='delContactBefore'>Specify a Date to delete contacts before:</label>
        <input type='date'  name='delContactBefore' >
        <input type='checkbox' name='deleteContact'>
        <label for='deleteContact'>Delete a Range of Contacts</label><br> 
        <p>OR</p>
        <input type='checkbox' name='reportContact'>
        <label for='reportContact'>Report on Contacts </label><br>    
       
        <button type='submit' name="submitContact">Submit</button>      
        </form>
        <br>
        
    
        </div>
    </section>
    </div>
   

    
</body>
</html>