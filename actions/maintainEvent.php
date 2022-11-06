<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
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
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$eventsArch = [];

$updateEvent = false;
$deleteEvent = false;
$addEvent = false;
$archiveEvent = false;
if (isset($_POST['submitEvent'])) {
    if (isset($_POST['eventId'])) {
        $eventId = htmlentities($_POST['eventId']);
        if(isset($_POST['updateEvent'])) {$updateEvent = $_POST['updateEvent'];}
        if(isset($_POST['deleteEvent'])) {$deleteEvent = $_POST['deleteEvent'];}
        if(isset($_POST['addEvent'])) {$addEvent = $_POST['addEvent'];}

        if ($updateEvent || $deleteEvent) {
            $event->id = $eventId;
            if ($event->read_single()) {

            }  else {
                echo "<h3 style='color: red;font-weight: bold;font-size: large'>No Event was found with id ".$event->id."</h3> <br>";
                echo "<h3 style='color: red;font-weight: bold;font-size: large'>Please return and enter a valid Event id. </h3> <br>";
            }
        } 

    }
    if (!isset($_POST['eventId'])) {
        if(isset($_POST['addEvent'])) {$addevent = $_POST['addEvent'];}
    }
}
if (isset($_POST['archiveEvent'])) {

    $archiveEvent = $_POST['archiveEvent'];
     if (isset($_POST['archDate'])) {
       $archDate = $_POST['archDate'];

       $archDate = date("Y")."-".$archDate;

       $result = $event->read_ByArchDate($archDate);
       $rowCount = $result->rowCount();

       $num_archClasses = $rowCount;

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
                    'eventdesc' => $eventdesc,
                    'eventroom' => $eventroom,
                    'eventnumregistered' => $eventnumregistered
                );
                   array_push($eventsArch, $event_item);

               }

           $_SESSION['eventsArch'] = $eventsArch;
           } 

     }
 }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Event Administration</title>
</head>
<body>

<div class="section-back container content-left">
   
      <br>
      <?php 
        if ($deleteEvent) {
        echo '<h1 class="section-header">Selected Event</h1><br>';
        echo '<table>';
        echo '<tr>';
                echo '<th>ID   </th>';  
                echo '<th>Date    </th>';
                echo '<th>Event Name    </th>';
                echo '<th>Type    </th>';
                echo '<th>Event Description </th>';
                echo '<th>Event DJ </th>';
                echo '<th>Room    </th>';
                echo '<th>Cost   </th>';
                echo '<th># Registered </th>';
                echo '<th>Event Form </th>';
  
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$event->id."</td>";
                    echo "<td>".$event->eventdate."</td>";
                    echo "<td>".$event->eventname."</td>";
                    echo "<td>".$event->eventtype."</td>";
                    echo "<td>".$event->eventdesc."</td>";
                    echo "<td>".$event->eventdj."</td>";
                    echo "<td>".$event->eventroom."</td>";
                    echo "<td>".$event->eventcost."</td>";
                    echo "<td>".$event->eventnumregistered."</td>";
                    echo "<td>".$event->eventform."</td>";

                echo "</tr>";

          
        echo '</table><br>';
        }
       if($updateEvent) {
        echo '<div class="form-left content-left">';
        
        echo '<form method="POST" action="updateEvent.php">';
        echo '<h3 class="heading-left" ><em>Update Event</em></h3><br>';
        echo '<label for="eventname">Event Name</label><br>';
        echo '<input type="text" name="eventname" value="'.$event->eventname.'"><br>';
        echo '<label for="eventtype">Event Type</label><br>';
    
        echo '<br><select name = "eventtype" value="'.$event->eventtype.'">'; 
        echo '<option value = "Dinner Dance">Dinner Dance</option>';
        echo '<option value = "First Friday">First Friday</option>';
        echo '<option value = "First Thursday">First Thursday</option>';
        echo '<option value = "Novice Practice Dance">Novice Practice Dance</option>';
        echo '<option value = "TGIF">TGIF</option>';
        echo '<option value = "Meeting">Meeting</option>';
        echo '</select><br>';
        echo '<label for="eventdesc">Event Description</label><br>';
        echo '<textarea name="eventdesc" cols="100" rows="3" >'.$event->eventdesc.'</textarea><br>';
        echo '<label for="eventdj">Event DJ</label><br>';
        echo '<input type="text" name="eventdj" value="'.$event->eventdj.'"><br>';
        echo '<label for="eventroom">Room</label><br>';
        echo '<input type="text" name="eventroom" value="'.$event->eventroom.'"><br>';
        echo '<label for="eventdate">Date</label><br>';
        echo '<input type="date" name="eventdate" value="'.$event->eventdate.'"><br>';
        echo '<label for="eventcost">Member Cost</label><br>';
        echo '<input type="text" name="eventcost" value="'.$event->eventcost.'"><br>';
        echo '<label for="eventnumregistered"># Registered</label><br>';
        echo '<input type="number" name="eventnumregistered" value="'.$event->eventnumregistered.'"><br>';
        echo '<label for="eventform">Link to Form</label><br>';
        echo '<input type="text" name="eventform" value="'.$event->eventform.'"><br>';
        echo '<input type="hidden" name="id" value="'.$event->id.'">';
        echo '<button type="submit" name="submitUpdate">Update the Event</button><br>';
        echo '</form>';
        echo '</div>';
        }
    

        if ($addEvent) {
            echo '<div class="form-left content-left">';
           
            echo '<form method="POST" action="addEvent.php">';
            echo '<h3 class="heading-left" ><em>Add Event</em></h3><br>';
           
            echo '<label for="eventname">Event Name</label><br>';
            echo '<input type="text" name="eventname" required><br>';
            echo '<label for="eventtype">Event Type</label><br> ';
          
            echo '<br><select name = "eventtype">';
            echo '<option value = "Dinner Dance">Dinner Dance </option>';
            echo '<option value = "First Friday">First Friday</option>';
            echo '<option value = "First Thursday">First Thursday</option>';
            echo '<option value = "Novice Practice Dance">Novice Practice Dance</option>';
            echo '<option value = "TGIF">TGIF</option>';  
            echo '<option value = "Meeting">Meeting</option>';
            echo '</select><br>';
            echo '<label for="eventdesc">Event Description</label><br>';
            echo '<textarea name="eventdesc" cols="100" rows="3" required></textarea><br>';
            echo '<label for="eventdj">Event DJ</label><br>';
            echo '<input type="text" name="eventdj"><br>';
            echo '<label for="eventroom">Room</label><br>';
            echo '<input type="text" name="eventroom" required><br>';
            echo '<label for="eventdate">Date</label><br>';
            echo '<input type="date" name="eventdate" ><br>';
            echo '<label for="eventcost">Member Cost</label><br>';
            echo '<input type="text" name="eventcost" value="0"><br>';
            echo '<label for="eventnumregistered"># Registered</label><br>';
            echo '<input type="number" name="eventnumregistered" value="0"><br>';
            echo '<label for="eventform">Link to Form</label><br>';
            echo '<input type="text" name="eventform"><br>';
          
        
            echo '<button type="submit" name="submitAdd">Add the Event</button><br>';
            echo '</form>';
            echo '</div>';
        }     
        if ($deleteEvent) {
            echo '<p> You have selected to delete event id: '.$event->id.'<br>';
            echo 'event name:  '.$event->eventname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteEvent.php">';
            echo '<input type="hidden" name="id" value="'.$event->id.'">';
            echo '<button type="submit" name="submitDelete">Delete the Event</button><br>';
            echo '</form>';
        }
        if ($archiveEvent) {
            echo '<h3> You have selected to archive the following events and their registrations: </h3><br>';
            echo '<table>';
            echo '<tr>';
                    echo '<th>Date    </th>';
                    echo '<th>Event    </th>';
                    echo '<th>Type    </th>';
                    echo '<th>DJ    </th>';
                    echo '<th># Registered </th>';
                    echo '<th>Room    </th>';
                    echo '<th>Cost    </th>';
                    echo '<th>Form    </th>';
                    echo '<th>ID   </th>';    
                echo '</tr>';

            foreach($eventsArch as $event) {
                echo "<tr>";
                echo "<td>".$event['eventdate']."</td>";
          
                echo "<td>".$event['eventname']."</td>";
                echo "<td>".$event['eventtype']."</td>";
                echo "<td>".$event['eventdj']."</td>";
                echo "<td>".$event['eventnumregistered']."</td>";
                echo "<td>".$event['eventroom']."</td>";
                echo "<td>".$event['eventcost']."</td>";
                echo "<td>".$event['eventform']."</td>";
                echo "<td>".$event['id']."</td>";
            echo "</tr>";
            }
            echo '</table><br>';
            echo '<form method="POST" action="archiveEvent.php">';
            echo '<button type="submit" name="submitArchive">Archive these Event(s) and their registrations</button><br>';
            echo '</form>';
        }
    
        ?> 

    </div>
</body>
</html>

