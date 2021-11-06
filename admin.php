<?php
session_start();

include_once 'config/Database.php';
include_once 'models/Contact.php';
include_once 'models/ClassRegistration.php';
include_once 'models/Event.php';
include_once 'models/DanceClass.php';

$allClasses = $_SESSION['classes'];
$allEvents = $_SESSION['events'];
$contacts = [];
$classRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$num_classes = 0;

$database = new Database();
$db = $database->connect();
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
            "contactdate" => $contactdate
        );
        array_push( $contacts, $contact_item);
  
    }
  

} else {
   echo 'NO REGISTRATIONS';

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
       
        <button type='submit'>Submit</button>      
        </form>
       
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