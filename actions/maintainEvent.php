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

    <div class="section-back">
    <section id="events" class="container content">
   
      <br>
      <?php 
        if ($updateEvent || $deleteEvent) {
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

       if($updateEvent) {
       
   
        echo '<form method="POST" action="updateEvent.php">';
        echo '<label for="eventname">Event Name</label>';
        echo '<input type="text" name="eventname" value="'.$event->eventname.'"><br>';
        echo '<label for="eventtype">event Level</label>';
        echo '<input type="text" name="eventtype" value="'.$event->eventtype.'"><br>';
        echo '<label for="eventdesc">Event Description</label>';
        echo '<textarea name="eventdesc" cols="100" rows="3" >'.$event->eventdesc.'</textarea><br>';
        echo '<label for="eventdj">Event DJ</label>';
        echo '<input type="text" name="eventdj" value="'.$event->eventdj.'"><br>';
        echo '<label for="eventroom">Room</label>';
        echo '<input type="text" name="eventroom" value="'.$event->eventroom.'"><br>';
        echo '<label for="eventdate">Date</label>';
        echo '<input type="date" name="eventdate" value="'.$event->eventdate.'"><br>';
        echo '<label for="eventcost">Member Cost</label>';
        echo '<input type="text" name="eventcost" value="'.$event->eventcost.'"><br>';
        echo '<label for="eventnumregistered"># Registered</label>';
        echo '<input type="text" name="eventnumregistered" value="'.$event->eventnumregistered.'"><br>';
        echo '<label for="eventform">Link to Form</label>';
        echo '<input type="text" name="eventform" value="'.$event->eventform.'"><br>';
        echo '<input type="hidden" name="id" value="'.$event->id.'">';
        echo '<button type="submit" name="submitUpdate">Update the Event</button><br>';
        echo '</form>';
    
        }
    }

        if ($addEvent) {
        

            echo '<form method="POST" action="addEvent.php">';
            echo '<label for="eventname">Event Name</label>';
            echo '<input type="text" name="eventname"><br>';
            echo '<label for="eventtype">event Level</label>';
            echo '<input type="text" name="eventtype" ><br>';
            echo '<label for="eventdesc">Event Description</label>';
            echo '<textarea name="eventdesc" cols="100" rows="3"></textarea><br>';
            echo '<label for="eventdj">Event DJ</label>';
            echo '<input type="text" name="eventdj"><br>';
            echo '<label for="eventroom">Room</label>';
            echo '<input type="text" name="eventroom" ><br>';
            echo '<label for="eventdate">Date</label>';
            echo '<input type="date" name="eventdate" ><br>';
            echo '<label for="eventcost">Member Cost</label>';
            echo '<input type="text" name="eventcost"><br>';
            echo '<label for="eventnumregistered"># Registered</label>';
            echo '<input type="text" name="eventnumregistered" ><br>';
            echo '<label for="eventform">Link to Form</label>';
            echo '<input type="text" name="eventform"><br>';
          
        
            echo '<button type="submit" name="submitAdd">Add the Event</button><br>';
            echo '</form>';
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
    </section>
    </div>
</body>
</html>

