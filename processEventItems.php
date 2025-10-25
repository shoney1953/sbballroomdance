<?php
$eventsArch = [];

if ($reportEvent) {

  foreach ($allEvents as $event) {
    $rpChk = 'rp'.$event['id'];

    if (isset($_POST["$rpChk"])) {
      // unset($_POST["$rpChk"]);
      echo "<h4>Generated Report for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
      echo "<form  name='reportEventForm'   method='POST' action='reportEvent.php'> ";
      echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
      echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
      unset($_POST["$rpChk"]);
      break;
    }
     
  }
}
if ($csvEvent) {

  foreach ($allEvents as $event) {
    $cvChk = 'cv'.$event['id'];

    if (isset($_POST["$cvChk"])) {
      // unset($_POST["$rpChk"]);
      echo "<h4>Generated CSV file for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
      echo "<form  name='csvEventForm'   method='POST' action='csvEvent.php'> ";
      echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
      echo '<script language="JavaScript">document.csvEventForm.submit();</script></form>';
      unset($_POST["$cvChk"]);
      break;
    }
     
  }
}
if ($addMeals) {

  foreach ($allEvents as $event) {
    $amChk = 'am'.$event['id'];

    if (isset($_POST["$amChk"])) {
 
      echo "<form  name='addMealsForm'   method='POST' action='addMeals.php'> ";
      echo "<input type='hidden' name='eventid' value='".$event['id']."'>"; 
      echo '<script language="JavaScript">document.addMealsForm.submit();</script></form>';
      unset($_POST["$amChk"]);
      break;
    }
     
  }
}
if ($updateMeals) {

  foreach ($allEvents as $event) {
    $umChk = 'um'.$event['id'];

    if (isset($_POST["$umChk"])) {
 
      echo "<form  name='updateMealsForm'   method='POST' action='updateMeals.php'> ";
      echo "<input type='hidden' name='eventid' value='".$event['id']."'>"; 
      echo '<script language="JavaScript">document.updateMealsForm.submit();</script></form>';
      unset($_POST["$umChk"]);
      break;
    }
     
  }
}
if ($uploadForm) {

  foreach ($allEvents as $event) {
    $ufChk = 'uf'.$event['id'];

    if (isset($_POST["$ufChk"])) {
         echo "<h4>Upload Flyer for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
      echo '<form method="POST" action="uploadForm.php"  enctype="multipart/form-data">';
      echo '<div class="form-grid-div">';
      echo "<input type='hidden' name='eventid' value='".$event['id']."'>"; 
      echo 'Select file to upload Only PDFs supported:<br>';
      echo '<input type="file" name="fileToUpload" id="fileToUpload"><br>';
      echo '<button type="submit" name="submitUpload">UPLOAD</button>';
      echo '</div> ';  
      echo '</form>';
      unset($_POST["$ufChk"]);
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

        echo '<label for="subject">Email Subject </label>';
        echo '<input type="text" name="subject" id="subject" placeholder="Enter Email Subject"><br>';  

        echo '<label for="replyEmail">Email to reply to: </label>';
        echo '<input type="email" name="replyEmail" id="replyEmail" value="'.$_SESSION['useremail'].'"><br>';  

        echo '<label for="emailBody">Email Text</label><br>';
        echo '<textarea  name="emailBody" rows="20" cols="100"></textarea><br>';
      
        echo '<br>';
        echo '<button type="submit" name="submitEventEmail">Send Email</button> ';  
        echo '</div> ';  

        echo '</form>';
      
        break;
      }
      
      
    }
  }
    if ($emailEventNon) {

    foreach ($allEvents as $event) {

      $eventNum = (int)substr($emChk,2);

      if ($event['id'] == $eventNum) {
        echo "<h4>Emailing Those Not Registered for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
        echo '<form method="POST" action="emailEventNon.php"> ';
        echo '<div class="form-grid-div">';
        echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
        echo '<label for="subject">Email Subject </label>';
        echo '<input type="text" name="subject" id="subject" placeholder="Enter Email Subject"><br>';  
        echo '<label for="replyEmail">Email to reply to: </label>';
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>';  

        echo '<label for="emailBody">Email Text</label><br>';
        echo '<textarea  name="emailBody" rows="20" cols="100"></textarea><br>';
      
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
       $evrendID = "evrend".$event['id'];
       $evropenID = "evropen".$event['id'];
       $evformID = "evform".$event['id'];
       $evidID = "evid".$event['id'];
       $evoeID = "evoe".$event['id'];
       $evprodID = "evprod".$event['id'];
       $evgcostID = "evgcost".$event['id'];
       $evgpriceID = "evgprice".$event['id'];
       $evmpriceID = "evmprice".$event['id'];
       echo '<div class="form-container">';
       echo '<div class="form-grid">';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Update?</h4>";
       echo "<input type='checkbox' name='".$evSelectChk."' checked title='Check this box to update'>";
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
        if ($event['eventtype'] == 'Social') {
          echo "<option value = 'Social' selected>Social </option>";
      } else {
        echo "<option value = 'Social'>Social</option>";
      }
      if ($event['eventtype'] == 'BBQ Picnic') {
        echo "<option value = 'BBQ Picnic' selected>BBQ Picnic </option>";
    } else {
      echo "<option value = 'BBQ Picnic'>BBQ Picnic</option>";
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
      echo "<h4 class='form-item-title'>Registration Opens</h4>";
      echo "<input type='date' name='".$evropenID."' value='".$event['eventregopen']."' 
      title='Select the Registration Opening Date' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Dance Only Registration End</h4>";
      echo "<input type='date' name='".$evrendID."' value='".$event['eventregend']."' 
      title='Select the Registration Closing Date' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event DJ</h4>";
      echo "<input type='text' name='".$evdjID."' value='".$event['eventdj']."' 
      title='Enter the DJ for the Event' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Organizer Email</h4>";
      echo "<input type='text' name='".$evoeID."' value='".$event['orgemail']."' 
      title='Enter the Organizers email' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Minimum Cost</h4>";
      echo "<input type='text' class='text-small' name='".$evcostID."' value='".$event['eventcost']."' 
      title='Enter the Member Event Cost if any' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Guest Minimum Cost</h4>";
      echo "<input type='text' class='text-small' name='".$evgcostID."' value='".$event['eventguestcost']."' 
      title='Enter the Guest Event Cost if any' >";
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

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Stripe Product ID</h4>";
      echo "<input type='text' class='text-small' name='".$evprodID."' value='".$event['eventproductid']."' 
      title='Enter the Stripe Product Id for the event' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Stripe Member Price ID</h4>";
      echo "<input type='text' class='text-small' name='".$evmpriceID."' value='".$event['eventmempriceid']."' 
      title='Enter the Member Stripe Product Id for the event' >";
      echo '</div>';

      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'>Event Stripe Guest Price ID</h4>";
      echo "<input type='text' class='text-small' name='".$evgpriceID."' value='".$event['eventguestpriceid']."' 
      title='Enter the Guest Stripe Product Id for the event' >";
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
  echo '<th>Dance Only Registration Ends</th>';
  echo '<th>DJ</th>';
  echo '<th>ORG email</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  foreach ($allEvents as $event) {
    $dlChk = "dl".$event['id'];
    if (isset($_POST["$dlChk"])) {
     $evSelectChk = "evselect".$event['id'];
     $evidID = "evid".$event['id'];
     
        echo '<tr>';
        echo "<td><input type='checkbox' name='".$evSelectChk."' checked title='Check this box to delete'></td>";
        echo "<td>".$event['eventtype']."</td>";
        echo "<td>".$event['eventdesc']."</td>";
        echo "<td>".$event['eventtype']."</td>";
        echo "<td>".$event['eventdesc']."</td>";
        echo "<td>".$event['eventdate']."</td>";
        echo "<td>".$event['eventregebd']."</td>";
        echo "<td>".$event['eventdj']."</td>";
        echo "<td>".$event['orgid']."</td>";

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
    echo '<th>Registration Opens</th>';
    echo '<th>Dance Only Registration Ends</th>';
    echo '<th>DJ</th>';
    echo '<th>ORG Email</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
  
    foreach ($allEvents as $event) {
      $aeChk = "ae".$event['id'];
      if (isset($_POST["$aeChk"])) {
       $evSelectChk = "evselect".$event['id'];
       $evidID = "evid".$event['id'];
       
          echo '<tr>';
          echo "<td><input type='checkbox' name='".$evSelectChk."' checked title='Check this box to delete'></td>";
          echo "<td>".$event['eventtype']."</td>";
          echo "<td>".$event['eventdesc']."</td>";
          echo "<td>".$event['eventtype']."</td>";
          echo "<td>".$event['eventdesc']."</td>";
          echo "<td>".$event['eventdate']."</td>";
          echo "<td>".$event['eventregopen']."</td>";
          echo "<td>".$event['eventregend']."</td>";
          echo "<td>".$event['eventdj']."</td>";
          echo "<td>".$event['orgemail']."</td>";
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
            if ($event['eventtype'] == 'Social') {
              echo "<option value = 'Social' selected>Social </option>";
          } else {
            echo "<option value = 'Social'>Social</option>";
          }
          if ($event['eventtype'] == 'BBQ Picnic') {
            echo "<option value = 'BBQ Picnic' selected>BBQ Picnic </option>";
        } else {
          echo "<option value = 'BBQ Picnic'>BBQ Picnic</option>";
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
          echo "<h4 class='form-item-title'>Registration Opens</h4>";
          echo "<td><input type='date' name='eventregopen' value='".$event['eventregopen']."' 
              title='Select Registration Opening Date' ></td>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Dance Only Registration Ends</h4>";
          echo "<td><input type='date' name='eventregend' value='".$event['eventregend']."' 
              title='Select Registration Closing Date' ></td>";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event DJ</h4>";
          echo "<input type='text' name='eventdj' value='".$event['eventdj']."' 
              title='Enter the DJ for the Event' >";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Organizer Email</h4>";
          echo "<input type='email' name='orgemail' value='".$event['orgemail']."' 
              title='Enter the Organizers Email' >";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Minimum Cost</h4>";
          echo "<input type='text' class='text-small' name='eventcost' value='".$event['eventcost']."' 
              title='Enter the Member Minimum Cost if any' >";
          echo '</div>';
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Event Guest Minimum Cost</h4>";
          echo "<input type='text' class='text-small' name='eventguestcost' value='".$event['eventguestcost']."' 
              title='Enter the Guest Minimum Cost if any' >";
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