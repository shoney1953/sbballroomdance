<?php
$eventsArch = [];

if ($reportEvent) {

  foreach ($allEvents as $event) {
    $rpChk = 'rp'.$event['id'];

    if (isset($_POST["$rpChk"])) {
      echo "<h4>Generated Report for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
      echo "<form  name='reportEventForm'   method='POST' action='reportEvent.php'> ";
      echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
      echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
      break;
    }
     
  }
}
  if ($emailEvent) {
 

    foreach ($allEvents as $event) {
  
      $eventNum = (int)substr($emChk,2);

      if ($event['id'] == $eventNum) {
        echo "<h4>Emailing registrants for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
        echo '<form method="POST" action="emailEvent.php"> ';
        echo '<div class="form-grid-div">';
        echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
       
        echo '<label for="replyEmail">Email to reply to: </label>';
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>';  

        echo '<label for="emailBody">Email Text</label><br>';
        echo '<textarea  name="emailBody" rows="30" cols="100"></textarea><br>';
      
        echo '<br>';
        echo '<button type="submit" name="submitEventEmail">Send Email</button> ';  
        echo '</div> ';  

        echo '</form>';
      
        break;
      }
      
      
    }
  }
  if ($updateEvent) {

    echo '<form method="POST" action="updateEvent.php">';
    echo "<h4 class='form-title'>Updating Selected Events</h4>";
    foreach ($allEvents as $event) {
      $upChk = "up".$event['id'];
      if (isset($_POST["$upChk"])) {
       $evSelectChk = "evselect".$event['id'];
       $evnamID = "evnam".$event['id'];
       $evtypeID = "evtype".$event['id'];
       $evdescID = "evdesc".$event['id'];
       $evdjID = "evdj".$event['id'];
       $evroomID = "evroom".$event['id'];
       $evdateID = "evdate".$event['id'];
       $evcostID = "evcost".$event['id'];
       $evnumregID = "evnumreg".$event['id'];
       $evformID = "evform".$event['id'];
       $evidID = "evid".$event['id'];
       echo '<div class="form-container">';
       echo '<div class="form-grid">';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Update?</h4>";
       echo "<input type='checkbox' name='".$evSelectChk."' title='Check this box to update'>";
       echo '</div>';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Event Name</h4>";
       echo "<input type='text' name='".$evnamID."' value='".$event['eventname']."' 
             title='Enter the Name of the Event'>";
       echo '</div>';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Event Description</h4>";
       echo "<input type='text' class='text-large' name='".$evdescID."' 
             value='".$event['eventdesc']."' title='Enter the Description of the Event'>";
       echo '</div>';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Event Type</h4>";
       echo "<select  title='Select the type of Event' name = '".$evtypeID."' >";

        if ($event['eventtype'] == 'Novice Practice Dance') {
          echo "<option value = 'Novice Practice Dance' selected>Novice Practice Dance </option>";
        } else {
          echo "<option value = 'Novice Practice Dance'>Novice Practice Dance</option>";
        }
        if ($event['eventtype'] == 'Dance Party') {
          echo "<option value = 'Dance Party' selected>Dance Party</option>";
        } else {
          echo "<option value = 'Dance Party'>Dance Party</option>";
          }
        if ($event['eventtype'] == 'Dine and Dance') {
          echo "<option value = 'Dine and Dance' selected>Dine and Dance </option>";
        } else {
          echo "<option value = 'Dine and Dance'>Dine and Dance</option>";
          }
        if ($event['eventtype'] == 'Dinner Dance') {
          echo "<option value = 'Dinner Dance' selected>Dinner Dance </option>";
        } else {
          echo "<option value = 'Dinner Dance'>Dinner Dance</option>";
        }
        if ($event['eventtype'] == 'TGIF') {
          echo "<option value = 'TGIF' selected>TGIF </option>";
        } else {
          echo "<option value = 'TGIF'>TGIF</option>";
        }    
        if ($event['eventtype'] == 'Meeting') {
            echo "<option value = 'Meeting' selected>Meeting </option>";
        } else {
          echo "<option value = 'Meeting'>Meeting</option>";
        }
      echo " </select>";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Room</h4>";
      echo "<input type='text' name='".$evroomID."' value='".$event['eventroom']."' 
      title='Enter the Room Where the Event will Occur'>";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Date</h4>";
      echo "<input type='date' name='".$evdateID."' value='".$event['eventdate']."' 
      title='Select the Date of the Event' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event DJ</h4>";
      echo "<input type='text' name='".$evdjID."' value='".$event['eventdj']."' 
      title='Enter the DJ for the Event' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Cost</h4>";
      echo "<input type='text' class='text-small' name='".$evcostID."' value='".$event['eventcost']."' 
      title='Enter the Event Cost if any' >";
      echo '</div>';


      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Num Registered</h4>";
      echo "<input type='number' class='number-small' name='".$evnumregID."' value='".$event['eventnumregistered']."'>";
      echo '</div>';


      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Form</h4>";
      echo "<input type='text' class='text-large' name='".$evformID."' value='".$event['eventform']."' 
      title='Enter a link to the Event Form or Flyer'>";
      echo '</div>';

      echo "<input type='hidden' name='".$evidID."' value='".$event['id']."'>"; 
 
       echo '</div>'; // end of form grid for each event
       echo '</div>'; // end of form container
      } // end of if isset upchk

    
   
  } // end of for each
  echo '<button type="submit" name="submitUpdate">Update Event(s)</button><br>';
    echo '</form>';
   
  }
if ($deleteEvent) {

  echo "<h4>Deleting Selected Events and Their Associated Registrations</h4>";
  echo '<form method="POST" action="deleteEvent.php">';
  echo '<table>' ;
  echo '<thead>';
  echo '<tr>';
  echo '<th>Delete</th>';
  echo '<th>Name</th>';  
  echo '<th>Description</th>';
  echo '<th>Type</th>';
  echo '<th>Room</th>';
  echo '<th>Date</th>';
  echo '<th>DJ</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  foreach ($allEvents as $event) {
    $dlChk = "dl".$event['id'];
    if (isset($_POST["$dlChk"])) {
     $evSelectChk = "evselect".$event['id'];
     $evidID = "evid".$event['id'];
     
        echo '<tr>';
        echo "<td><input type='checkbox' name='".$evSelectChk."' title='Check this box to delete'></td>";
        echo "<td>".$event['eventtype']."</td>";
        echo "<td>".$event['eventdesc']."</td>";
        echo "<td>".$event['eventtype']."</td>";
        echo "<td>".$event['eventdesc']."</td>";
        echo "<td>".$event['eventdate']."</td>";
        echo "<td>".$event['eventdj']."</td>";

        echo "<input type='hidden' name='".$evidID."' value='".$event['id']."'>";
        echo '</tr>';
    }

}
echo '</tbody>';
echo '</table>';  
echo '<button type="submit" name="submitDelete">Delete the Event(s)</button><br>';
echo '</form>';

  }


  if ($archiveEvent) {
  
    echo "<h4>Archiving Selected Events and Their Associated Registrations</h4>";
    echo '<form method="POST" action="archiveEvent.php">';
    echo '<table>' ;
    echo '<thead>';
    echo '<tr>';
    echo '<th>Archive</th>';
    echo '<th>Name</th>';  
    echo '<th>Description</th>';
    echo '<th>Type</th>';
    echo '<th>Room</th>';
    echo '<th>Date</th>';
    echo '<th>DJ</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
  
    foreach ($allEvents as $event) {
      $aeChk = "ae".$event['id'];
      if (isset($_POST["$aeChk"])) {
       $evSelectChk = "evselect".$event['id'];
       $evidID = "evid".$event['id'];
       
          echo '<tr>';
          echo "<td><input type='checkbox' name='".$evSelectChk."' title='Check this box to delete'></td>";
          echo "<td>".$event['eventtype']."</td>";
          echo "<td>".$event['eventdesc']."</td>";
          echo "<td>".$event['eventtype']."</td>";
          echo "<td>".$event['eventdesc']."</td>";
          echo "<td>".$event['eventdate']."</td>";
          echo "<td>".$event['eventdj']."</td>";
          echo "<input type='hidden' name='".$evidID."' value='".$event['id']."'>";
          echo '</tr>';
   
          }
        }
      
      echo '</tbody>';
      echo '</table><br>';
      echo '<button type="submit" name="submitArchive">Archive these Event(s) and their registrations</button><br>';
      echo '</form>';
      echo '<br>';
  }

  if ($duplicateEvent) {

    echo '<div class="form-container">';
    echo '<form method="POST" action="addEvent.php">';
    echo "<h4 class='form-title'>Duplicate this event</h4>";
    echo '<div class="form-grid">';
    foreach ($allEvents as $event) {
      $dpChk = "dp".$event['id'];
      if (isset($_POST["$dpChk"])) {
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Name</h4>";
          echo "<input type='text' name='eventname' value='".$event['eventname']."' 
                title='Enter the Name of the Event'>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Description</h4>";
          echo "<input type='text' class='text-large' name='eventdesc' 
                value='".$event['eventdesc']."' title='Enter the Description of the Event'>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Type</h4>";
          echo "<select  title='Select the type of Event' name = 'eventtype'> ";
           echo '<br>';
            if ($event['eventtype'] == 'Novice Practice Dance') {
              echo "<option value = 'Novice Practice Dance' selected>Novice Practice Dance </option>";
            } else {
              echo "<option value = 'Novice Practice Dance'>Novice Practice Dance</option>";
            }
            if ($event['eventtype'] == 'Dance Party') {
              echo "<option value = 'Dance Party' selected>Dance Party</option>";
            } else {
              echo "<option value = 'Dance Party'>Dance Party</option>";
              }
            if ($event['eventtype'] == 'Dine and Dance') {
              echo "<option value = 'Dine and Dance' selected>Dine and Dance </option>";
            } else {
              echo "<option value = 'Dine and Dance'>Dine and Dance</option>";
              }
            if ($event['eventtype'] == 'Dinner Dance') {
              echo "<option value = 'Dinner Dance' selected>Dinner Dance </option>";
            } else {
              echo "<option value = 'Dinner Dance'>Dinner Dance</option>";
            }
            if ($event['eventtype'] == 'TGIF') {
              echo "<option value = 'TGIF' selected>TGIF </option>";
            } else {
              echo "<option value = 'TGIF'>TGIF</option>";
            }    
            if ($event['eventtype'] == 'Meeting') {
                echo "<option value = 'Meeting' selected>Meeting </option>";
            } else {
              echo "<option value = 'Meeting'>Meeting</option>";
            }
          echo " </select>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Room</h4>";
          echo "<input type='text' name='eventroom' value='".$event['eventroom']."' 
              title='Enter the Room Where the Event will Occur'>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Date</h4>";
          echo "<td><input type='date' name='eventdate' value='".$event['eventdate']."' 
              title='Select the Date of the Event' ></td>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event DJ</h4>";
          echo "<input type='text' name='eventdj' value='".$event['eventdj']."' 
              title='Enter the DJ for the Event' >";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Cost</h4>";
          echo "<input type='text' class='text-small' name='eventcost' value='".$event['eventcost']."' 
              title='Enter the Event Cost if any' >";
          echo '</div>';
       

          break; // break out of for each loop after 1 found
      }
      
    
}
      echo '</div>'; // end form grid
      echo '<button type="submit" name="submitAdd">Add a New Event</button><br>';
      echo '</form>';
      echo '</div>'; // end form container  
  }
    // 
  // $redirect = "Location: ".$_SESSION['adminurl']."#events";
  //   header($redirect);
  //   exit;
 

?>