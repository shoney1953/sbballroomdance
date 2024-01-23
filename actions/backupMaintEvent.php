<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/DinnerMealChoices.php';
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
$dinnermealchoices = new DinnerMealChoices($db);
$num_mealchoices = 0;

$eventsArch = [];
$mealChoices = [];
$updateEvent = false;
$deleteEvent = false;
$addEvent = false;
$archiveEvent = false;
$updateMealChoices = false;

if (isset($_POST['submitEvent'])) {
    if (isset($_POST['eventId'])) {
        $eventId = htmlentities($_POST['eventId']);
 
        if (isset($_POST['updateEvent'])) {
            $updateEvent = $_POST['updateEvent'];
        }
        if (isset($_POST['deleteEvent'])) {
            $deleteEvent = $_POST['deleteEvent'];
        }
        if (isset($_POST['updateMealChoices'])) {
            $updateMealChoices = $_POST['updateMealChoices'];
        }
        if (isset($_POST['addEvent'])) {
            $addEvent = $_POST['addEvent'];
        }

        if ($updateEvent || $deleteEvent || $updateMealChoices) {
            $event->id = $eventId;
            if ($event->read_single()) {
               
                   $result = $dinnermealchoices->read_DinnerDanceId($eventId);
                    $rowCount = $result->rowCount();
       
                    $num_mealchoices = $rowCount;
             
                        if ($rowCount > 0) {
             
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $meal_item = array(
                                 'id' => $id,
                                 'mealchoice' => $mealchoice,
                                 'memberprice' => $memberprice,
                                 'guestprice' => $guestprice,
                                 'dinnerdanceid' => $dinnerdanceid,

                             );
                                array_push($mealChoices, $meal_item);
             
                            }
                            $_SESSION['mealChoices'] = $mealChoices;
             
             
                } 

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
                    'eventregend' => $eventregend,
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
                echo '<th>Registration Closes</th>';
                echo '<th>Name    </th>';
                echo '<th>Type    </th>';
                echo '<th>Description </th>';
                echo '<th>DJ </th>';
                echo '<th>Room    </th>';
                echo '<th>Cost   </th>';
                echo '<th># Registered </th>';
                echo '<th>Event Form </th>';
  
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$event->id."</td>";
                    echo "<td>".$event->eventdate."</td>";
                    echo "<td>".$event->eventregend."</td>";
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
        if($updateMealChoices) {
            if ($event->eventtype === 'Dinner Dance') {
                echo '<div class="form-left content-left">';
            
                echo '<form method="POST" action="updateMealChoices.php">';
                echo '<h3 class="heading-left" ><em>Update Meal Choices for Dinner Dance</em></h3><br>';
                echo '<h3 class="heading-left" ><em>'.$event->eventname.'  '.$event->eventdate.'</em></h3><br>';
                echo '</div>';

                if ($num_mealchoices > 0) {
                    echo '<table>';
                    echo '<tr>';
                        echo '<th>Update?</th>'; 
                        echo '<th>Meal Choice</th>'; 
                        echo '<th>Member Price</th>';
                        echo '<th>Guest Price</th>';

                        echo '</tr>';
                            
                        foreach ($mealChoices as $mealchoice) {
                     
                              echo "<tr>";
                              $chkboxID = "mc".$mealchoice['id'];
                              echo "<td><input type='checkbox' name='$chkboxID'></td>";
                              $mchoiceNM = "mcname".$mealchoice['id'];
                              echo "<td> 
                              <input type='text' name='$mchoiceNM' 
                                  value='".$mealchoice['mealchoice']."' style='width: 1000px'>
                              </td>";
                              $mPrice = "mprice".$mealchoice['id'];
                              echo "<td> 
                              <input type='number' name='$mPrice'  min='0.00' step='1.00' max='6500' 
                                   value='".$mealchoice['memberprice']."' >
                              </td>";
                              $gPrice = "gprice".$mealchoice['id'];
                              echo "<td> 
                              <input type='number' name='$gPrice'  min='0.00' step='1.00' max='6500' 
                                 value='".$mealchoice['guestprice']."' >
                              </td>";
        
                              echo "</tr>";
                          }
        
                    echo '</table><br>'; 
                    echo '<input type=hidden name="dinnerdanceid" value="'.$event->id.'">';

                    echo '<button type="submit" name="updateMealChoices">UPDATE MEAL CHOICES</button>'; 
                    } 
                    echo '</form>';
                    echo '</div> '; 
                    echo '<div class="form-left content-left">';
                    echo '<form method="POST" action="addMealChoices.php">';
                    echo '<h3 class="heading-left" ><em>Add Meal Choices for Dinner Dance</em></h3>';
                    echo '<h3 class="heading-left" ><em>'.$event->eventname.'  '.$event->eventdate.'</em></h3>';
                    echo '<table>';
                    echo '<tr>';
                        echo '<th>Add?</th>';
                        echo '<th>Meal Choice</th>'; 
                        echo '<th>Member Price</th>';
                        echo '<th>Guest Price</th>';

                        echo '</tr>';
 
                              echo "<tr>";
                           
                                echo "<td>
                                  <input type='checkbox' name='addChoice1'>
                                </td>";
                                echo "<td> 
                                <input type='text' name='mealchoice1' value='meal choice 1' style='width: 1000px'>
                                </td>";
                                echo "<td> 
                                <input type='number' name='memberPrice1'  min='0.00' step='1.00' max='6500' value='50.00' >
                                </td>";
                                echo "<td> 
                                <input type='number' name='guestPrice1'  min='0.00' step='1.00' max='6500' value='55.00' >
                                </td>";

                              echo "</tr>";
                              echo "<tr>";
                           
                              echo "<td>
                                <input type='checkbox' name='addChoice2'>
                              </td>";
                              echo "<td> 
                              <input type='text' name='mealchoice2' value='meal choice 2' style='width: 1000px' >
                              </td>";
                              echo "<td> 
                              <input type='number' name='memberPrice2'  min='0.00' step='1.00' max='6500' value='50.00' >
                              </td>";
                              echo "<td> 
                              <input type='number' name='guestPrice2'  min='0.00' step='1.00' max='6500' value='55.00' >
                              </td>";

                            echo "</tr>";
                            echo "<tr>";
                           
                            echo "<td>
                              <input type='checkbox' name='addChoice3'>
                            </td>";
                            echo "<td> 
                            <input type='text' name='mealchoice3' value='meal choice 3' style='width: 1000px' >
                            </td>";
                            echo "<td> 
                            <input type='number' name='memberPrice3'  min='0.00' step='1.00' max='6500' value='50.00' >
                            </td>";
                            echo "<td> 
                            <input type='number' name='guestPrice3'  min='0.00' step='1.00' max='6500' value='55.00' >
                            </td>";

                          echo "</tr>";
                          echo "<tr>";
                           
                          echo "<td>
                            <input type='checkbox' name='addChoice4'>
                          </td>";
                          echo "<td> 
                          <input type='text' name='mealchoice4' value='meal choice 4' style='width: 1000px' >
                          </td>";
                          echo "<td> 
                          <input type='number' name='memberPrice4'  min='0.00' step='1.00' max='6500' value='50.00' >
                          </td>";
                          echo "<td> 
                          <input type='number' name='guestPrice4'  min='0.00' step='1.00' max='6500' value='55.00' >
                          </td>";

                        echo "</tr>";
                         
        
                    echo '</table><br>'; 

                        echo '<input type=hidden name="dinnerdanceid" value="'.$event->id.'">';

                        echo '<button type="submit" name="addMealChoices">ADD MEAL CHOICES</button>'; 
                
                    echo '</form>';
                    echo '</div> ';  
            } else {
                echo '<h1> Event Selected Not a Dinner Dance event</h1><br>';
            }

        }
       if($updateEvent) {
        echo '<div class="form-left content-left">';
        
        echo '<form method="POST" action="updateEvent.php">';
        echo '<h3 class="heading-left" ><em>Update Event</em></h3><br>';
        echo '<label for="eventname">Event Name</label><br>';
        echo '<input type="text" name="eventname" value="'.$event->eventname.'"><br>';
        echo '<label for="eventtype">Event Type</label><br>';
    
        echo '<br><select name = "eventtype" value="'.$event->eventtype.'">'; 
        echo '<option value = "Dine and Dance">Dine and Dance</option>';
        echo '<option value = "Novice Practice Dance">Novice Practice Dance</option>';
        echo '<option value = "Dinner Dance">Dinner Dance</option>';
        echo '<option value = "TGIF">TGIF</option>';
        echo '<option value = "Meeting">Meeting</option>';
        echo '<option value = "Social">Social</option>';
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
            echo '<option value = "Dine and Dance">Dine and Dance</option>'; 
            echo '<option value = "Novice Practice Dance">Novice Practice Dance</option>'; 
            echo '<option value = "Dinner Dance">Dinner Dance </option>';
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

