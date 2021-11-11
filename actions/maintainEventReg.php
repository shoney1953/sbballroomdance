<?php

session_start();
include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);


$updateReg = false;
$deleteReg = false;
$addReg = false;



if (isset($_POST['regId'])) {
    $regId = htmlentities($_POST['regId']);
    if(isset($_POST['updateReg'])) {$updateReg = $_POST['updateReg'];}
    if(isset($_POST['deleteReg'])) {$deleteReg = $_POST['deleteReg'];}
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}

    if ($updateReg || $deleteReg) {
        $eventReg->id = $regId;
        $eventReg->read_single();  
    } 

}
if (!isset($_POST['regId'])) {
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance Beta - Admin Event Registrations</title>
</head>
<body>

    <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
      <?php 
        if ($updateReg || $deleteReg) {
        echo '<h1 class="section-header">Selected Registration</h1><br>';
        echo '<table>';
        echo '<tr>';
        
                echo '<th>Event Id    </th>';
                echo '<th>Event Name</th>';
                echo '<th>First Name </th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Userid</th>';
                echo '<th>Registration ID   </th>';    
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$eventReg->eventid."</td>";
                    echo "<td>".$eventReg->eventname."</td>";
                    echo "<td>".$eventReg->firstname."</td>";
                    echo "<td>".$eventReg->lastname."</td>";
                    echo "<td>".$eventReg->email."</td>";
                    echo "<td>".$eventReg->userid."</td>";
                    echo "<td>".$eventReg->id."</td>";
                echo "</tr>";

          
        echo '</table><br>';

       if($updateReg) {
        echo '<form method="POST" action="updateEventReg.php">';
        echo '<label for="eventid">Event Id</label>';
        echo '<input type="text" name="eventid" value="'.$eventReg->eventid.'"><br>';
        echo '<label for="firstname">First Name</label>';
        echo '<input type="text" name="firstname" value="'.$eventReg->firstname.'"><br>';
        echo '<label for="lastnames">Last Name</label>';
        echo '<input type="text" name="lastname" value="'.$eventReg->lastname.'"><br>';
        echo '<label for="email">Email</label>';
        echo '<input type="email" name="email" value="'.$eventReg->email.'"><br>';
        echo '<label for="userid">Userid</label>';
        echo '<input type="text" name="userid" value="'.$eventReg->userid.'"><br>';

        echo '<input type="hidden" name="id" value="'.$eventReg->id.'">';
        echo '<button type="submit" name="submitUpdateReg">Update the Registration</button><br>';
        echo '</form>';
    
        }
    }

        if ($addReg) {
            echo '<form method="POST" action="addEventReg.php">';
            echo '<label for="eventid">Event Id</label>';
            echo '<input type="text" name="eventid" required><br>';
            echo '<label for="firstname">First Name</label>';
            echo '<input type="text" name="firstname" required><br>';
            echo '<label for="lastname">Last Name</label>';
            echo '<input type="text" name="lastname" required ><br>';
            echo '<label for="email">Email</label>';
            echo '<input type="text" name="email" required><br>';
            echo '<button type="submit" name="submitAddReg">Add the Event</button><br>';
            echo '</form>';
        }     
        if($deleteReg) {
            echo '<p> You have selected to delete class id: '.$eventReg->id.'<br>';
            echo 'First name:  '.$eventReg->firstname.' Last Name '.$eventReg->lastname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteEventReg.php">';
            echo '<input type="hidden" name="id" value="'.$eventReg->id.'">';
            echo '<input type="hidden" name="eventid" value="'.$eventReg->eventid.'">';
            echo '<button type="submit" name="submitDeleteReg">Delete the Registration</button><br>';
            echo '</form>';
        }
        ?> 
    </section>
    </div>
</body>
</html>
