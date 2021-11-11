<?php

session_start();
include_once '../config/Database.php';
include_once '../models/Event.php';
$database = new Database();
$db = $database->connect();
$event = new Event($db);


$updateEvent = false;
$deleteEvent = false;
$addEvent = false;

if (isset($_POST['submitEvent'])) {
    if (isset($_POST['eventId'])) {
        $eventId = htmlentities($_POST['eventId']);
        if(isset($_POST['updateEvent'])) {$updateEvent = $_POST['updateEvent'];}
        if(isset($_POST['deleteEvent'])) {$deleteEvent = $_POST['deleteEvent'];}
        if(isset($_POST['addEvent'])) {$addEvent = $_POST['addEvent'];}

        if ($updateEvent || $deleteEvent) {
            $event->id = $eventId;
            $event->read_single();  
        } 

    }
    if (!isset($_POST['eventId'])) {
        if(isset($_POST['addEvent'])) {$addevent = $_POST['addEvent'];}
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
    <title>SBDC Ballroom Dance Beta - Admin Events</title>
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
            echo '<input type="text" name="eventcost"><br>';
            echo '<label for="eventnumregistered"># Registered</label><br>';
            echo '<input type="number" name="eventnumregistered" ><br>';
            echo '<label for="eventform">Link to Form</label><br>';
            echo '<input type="text" name="eventform"><br>';
          
        
            echo '<button type="submit" name="submitAdd">Add the Event</button><br>';
            echo '</form>';
            echo '</div>';
        }     
        if($deleteEvent) {
            echo '<p> You have selected to delete event id: '.$event->id.'<br>';
            echo 'event name:  '.$event->eventname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteEvent.php">';
            echo '<input type="hidden" name="id" value="'.$event->id.'">';
            echo '<button type="submit" name="submitDelete">Delete the Event</button><br>';
            echo '</form>';
        }
        ?> 

    </div>
</body>
</html>
