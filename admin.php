<?php
session_start();
require 'includes/db.php';
$classes = $_SESSION['classes'];
$contacts = [];
$classRegistrations = [];
$num_registrations = 0;
$allEvents = [];
$num_events = 0;
$allClasses = [];
$num_classes = 0;

$sql = "SELECT id, 
    classid, 
    firstname, 
    lastname, 
    email,
    dateregistered
    FROM classregistration ORDER BY classid, lastname, firstname;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $num_registrations++;
        $classRegistrations[$num_registrations] = [
            'id' => $row["id"],
            'classid' => $row["classid"],
            'firstname' => $row["firstname"],
            'lastname' => $row["lastname"],
            'email' => $row["email"],
            'dateregistered' => $row["dateregistered"]
          
        ];
    }
}
$num_contacts = 0;
$sql = "SELECT id, 
    firstname, 
    lastname, 
    email,
    message,
    contactdate
    FROM contacts ORDER BY contactdate DESC;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $num_contacts++;
        $contacts[$num_contacts] = [
            'id' => $row["id"],
            'firstname' => $row["firstname"],
            'lastname' => $row["lastname"],
            'email' => $row["email"],
            'message' => $row["message"],
            'contactdate' => $row["contactdate"]
          
        ];
    }
}
/* get events */
$sql = "SELECT id, 
    eventname,
    eventtype, 
    eventroom, 
    eventdesc,
    eventdate,
    eventcost,
    eventnumreg,
    eventform,
    eventnumregistered
         FROM events ORDER BY eventdate ;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {
      
        $num_events++;
        $allEvents[$num_events] = [
            'id' => $row["id"],
            'eventname' => $row["eventname"],
            'eventtype' => $row["eventtype"],
            'eventroom' => $row["eventroom"],
            'eventdesc' => $row["eventdesc"],
            'eventdate' => $row["eventdate"],
            'eventcost' => $row["eventcost"],
            'eventnumreg' => $row["eventnumreg"],
            'eventform' => $row["eventform"],
            'eventnumregistered' => $row["eventnumregistered"]
        ];    
    }
}
$sql = "SELECT id, 
    classname, 
    registrationemail, 
    instructors, 
    classlimit, 
    classlevel,
    room, 
    numregistered,
    time,
    date FROM danceclasses ORDER BY date;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
   
    while ($row = $result->fetch_assoc()) {
        $num_classes++;
        $allClasses[$num_classes] = [
            'id' => $row["id"],
            'classname' => $row["classname"],
            'classlevel' => $row["classlevel"],
            'registrationemail' => $row["registrationemail"],
            'instructors' => $row["instructors"],
            'classlimit' => $row["classlimit"],
            'room' => $row["room"],
            'date' => $row["date"],
            'numregistered' => $row['numregistered'],
            'time' => $row["time"],
        ];
        
    }
}

$conn->close();
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
    <br>
    <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
        <h1 class="section-header">All Classes</h1><br>
        <table>
            <tr>
                <th>Date    </th>
                <th>Time    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Registration Email </th>
                <th>Instructors    </th>
                <th>Class Limit    </th>
                <th># Registered </th>
                <th>Room    </th>
                <th>ID   </th>    
            </tr>
            <?php 
           
            foreach($allClasses as $class)
             { 
                  echo "<tr>";
                    echo "<td>".$class['date']."</td>";
                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['classlevel']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
                    echo "<td>".$class['numregistered']."</td>";
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['id']."</td>";
     
                echo "</tr>";
                
              }
             
            ?> 
        </table>
        <br>
    </section>
    </div>
    <div class="section-back">
    <section id="registrations" class="container content">
        <h1>Class Registrations</h1>    
        <table>
            <tr>
                <th>Class</th>
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
                    }
                }
    
                  echo "<tr>";
                    echo "<td>".$className."</td>";
                    echo "<td>".$classRegistration['firstname']."</td>";
                    echo "<td>".$classRegistration['lastname']."</td>";
                    echo "<td>".$classRegistration['email']."</td>";           
                    echo "<td>".$classRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
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
             
            </tr>
            <?php 
    
            foreach($contacts as $contact) {
         
                  echo "<tr>";
                    echo "<td>".$contact['contactdate']."</td>";
                    echo "<td>".$contact['firstname']."</td>";               
                    echo "<td>".$contact['lastname']."</td>";
                    echo "<td>".$contact['email']."</td>";
                    echo "<td>".$contact['message']."</td>";           
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
    </section>
    </div>
    <div class="section-back">
    <section id="events" class="container content">

      <br>
        <h1 class="section-header">All Events</h1><br>
        <table>
            <tr>
                <th>Event Date</th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th>          
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Registered </th>
            </tr>
            <?php 
            $eventNumber = 0;
            foreach($allEvents as $event) {
                 $eventNumber++;
                  echo "<tr>";
                    echo "<td>".$event['eventdate']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>";           
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
    </section>
    </div>
    

    
</body>
</html>