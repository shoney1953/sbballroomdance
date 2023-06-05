<?php
if ($addReg) {
    echo '<h2>Add Either Member <em>OR</em> Visitor Registrations to the following Event.</h2>';
 
  
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="addEventReg.php">';
       
        foreach ($upcomingEvents as $event) {
          
            $eventNum = (int)substr($arChk,2);
            if ($event['id'] == $eventNum) {
                break;
            }
        }
        echo '<input type=hidden name="eventid" value="'.$eventNum.'">';

        echo '<table>';
        echo '<thead>';
       
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Event Name</th>';
        echo '<th>Event Type</th>';
        echo '<th>Event Date</th>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';
        echo '<tr>';
        echo "<td>".$event['id']."</td>";
        echo "<td>".$event['eventname']."</td>";
        echo "<td>".$event['eventtype']."</td>";
        echo "<td>".$event['eventdate']."</td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '';
        echo '<div class="form-grid2">';
        echo '<div class="form-grid-div">'; 
    echo '<br><br><table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=6>Add Member Registrations</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th colspan=6>Select One or all of the following members</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Add</th>';
    if ($event['eventtype'] === 'Dine and Dance') {
        echo '<th>Attend<br>Dinner?</th>';
    }
    if ($event['eventtype'] === 'Dance Party') {
        echo '<th>Attend<br>Dinner?</th>';
    }

    echo '<th>First Name</th>';
    echo '<th>Last Name</th>';
    echo '<th>Email</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($users as $usr) {
  
        $usrID = "us".$usr['id'];
        $attDin = "datt".$usr['id'];
        echo '<tr>';
        echo "<td><input  title='Select to Add Registrations' type='checkbox'name='".$usrID."'></td>";
        if ($event['eventtype'] === 'Dine and Dance') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='".$attDin."'></td>";
        }
        if ($event['eventtype'] === 'Dance Party') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='".$attDin."'></td>";
        }
 
        echo "<td >".$usr['firstname']."</td>";
        echo "<td>".$usr['lastname']."</td>";
        echo "<td>".$usr['email']."</td>";
        echo '</tr>';

     }
     echo '</tbody>';
     echo '</table>';
 
        echo '<button type="submit" name="submitAddReg">
           Add the Event Registration</button><br>';
        echo '</form>';
        echo '</div>';

        echo '<div class="form-grid-div">';
        echo '<br><br>';
        echo '<form method="POST" action="addVisitorEventReg.php">';
        echo "<input type='hidden' name='eventid' value='".$event['id']."'>";
        echo '<table>';   
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=5>Add Visitor Registration</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>First Name</td>';
        echo '<th>Last Name</td>';
        echo '<th>Email</td>';
    
        if ($event['eventtype'] === 'Dine and Dance') {
            echo '<th>Attend<br>Dinner?</th>';
        }
        if ($event['eventtype'] === 'Dance Party') {
            echo '<th>Attend<br>Dinner?</th>';
        }
        echo '<th>Notes</td>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname1'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname1'></td>";
        echo "<td><input type='email' name='email1' required></td>";
        if ($event['eventtype'] === 'Dine and Dance') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin1'></td>";
        }
        if ($event['eventtype'] === 'Dance Party') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin1'></td>";
        }
        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes1' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname2'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname2'></td>";
        echo "<td><input type='email' name='email2'></td>";
        if ($event['eventtype'] === 'Dine and Dance') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin2'></td>";
        }
        if ($event['eventtype'] === 'Dance Party') {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin2'></td>";
        }
        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes2' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    
        echo '<button type="submit" name="submitAddVisitorReg">Add Visitor Registration</button> ';
        echo '</form>'; 
        echo '</div>';
        echo '</div>';

     } // end add reg
    
     if ($deleteReg) {
   
        echo '<div class="form-grid-div">';
        
            echo '<form method="POST" action="deleteEventReg.php">';
            foreach ($upcomingEvents as $event) {
                $eventNum = (int)substr($drChk,2);
                if ($event['id'] == $eventNum) {
                    break;
                }
            }
            echo '<h2>Delete registrations to the following event</h2>';
            echo '<input type=hidden name="eventid" value="'.$eventNum.'">';
    
            echo '<table>';
            echo '<thead>';
           
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Event Name</th>';
            echo '<th>Event Type</th>';
            echo '<th>Event Date</th>';
            echo '</tr>';
            echo '</thead>';
    
            echo '<tbody>';
            echo '<tr>';
            echo "<td>".$event['id']."</td>";
            echo "<td>".$event['eventname']."</td>";
            echo "<td>".$event['eventtype']."</td>";
            echo "<td>".$event['eventdate']."</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
    
        echo '<br><br><table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=6>Select One or all of the following registrations.</th>';
        echo '</tr>';
        echo '<tr>'; 
        echo '<th>Delete</th>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Email</th>';
        echo '<th>Userid</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($regs as $reg) {
  
            $delID = "del".$reg['id'];

            echo '<tr>';
            echo "<td><input title='Select to Delete Registration' type='checkbox' name='".$delID."'></td>";
     
            echo "<td>".$reg['firstname']."</td>";
            echo "<td>".$reg['lastname']."</td>";
            echo "<td>".$reg['email']."</td>";
            echo "<td>".$reg['userid']."</td>";

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitDeleteReg">Delete the Registration(s)</button><br>';
        echo '</form>';
        echo '</div>';
     } // end delete reg

     if ($updateReg) {

            echo '<form method="POST" action="updateEventReg.php">';
            foreach ($upcomingEvents as $event) {
                $eventNum = (int)substr($urChk,2);
                if ($event['id'] == $eventNum) {
                    break;
                }
            }
   
            echo '<input type=hidden name="eventid" value="'.$eventNum.'">';
            echo '<div class="form-container">';
            echo '<h4 class="form-title">Update registrations to the following event</h4>';
            echo '<div class="form-grid">';

            echo '<div class="form-item">';
    
            echo  "<h4 class='form-title'>Event ID: ".$event['id']."</h4>";
            echo '</div>'; // end of form item

        
            echo '<div class="form-item">';

            echo "<h4 class='form-title'>Event Name: ".$event['eventname']."</h4>";
            echo '</div>'; // end of form item

            echo '<div class="form-item">';

            echo "<h4 class='form-title'>Event Type: ".$event['eventtype']."</h4>";
            echo '</div>'; // end of form item

            echo '<div class="form-item">';
            echo "<h4 class='form-title'>Event Date: ".$event['eventdate']."</h4>";
            echo '</div>'; // end of form item

            echo '<div class="form-item">';
            echo "<h4 class='form-title'>Event Cost: ".$event['eventcost']."</h4>";
            echo '</div>'; // end of form item

            echo '</div>'; // end form grid
            echo '</div>'; // end form container

    
    
        foreach ($regs as $reg) {
  
            $updID = "upd".$reg['id'];
            $fnamID = "fnam".$reg['id'];
            $lnamID = "lnam".$reg['id'];
            $emailID = "email".$reg['id'];
            $useridID = "userid".$reg['id'];
            $messID = "mess".$reg['id'];
            $paidID = "paid".$reg['id'];
            $dddinID = "dddin".$reg['id'];
            echo '<div class="form-container">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Update?</h4>';
            echo "<input title='Select to Update Registration' type='checkbox' name='".$updID."'>";
            echo '</div>'; // end of form item
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">First Name</h4>';
            echo "<input type='text' title='Registrant First Name' name='".$fnamID."' value='".$reg['firstname']."'>";
            echo '</div>'; // end of form item
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Last Name</h4>';
            echo "<input type='text' title='Registrant Last Name' name='".$lnamID."' value='".$reg['lastname']."'>";
            echo '</div>'; // end of form item
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Email</h4>';
            echo "<input type='email'  title='Registrant Email'name='".$emailID."' value='".$reg['email']."'>";
            echo '</div>'; // end of form item
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">User ID</h4>';
            echo "<input type='text'  title='Registrant User Id' name='".$useridID."' value='".$reg['userid']."'>";
            echo '</div>'; // end of form item
            if ($event['eventtype'] === 'Dance Party') {
                $ad = 0;
                if ($reg['ddattenddinner']) {
                    $ad = $reg['ddattenddinner'];
                }
                else {
                    $ad = 0;
                }
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                echo "<input type='number'  title='Enter 1 for Attend dinner' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Paid?</h4>';
                    echo "<input type='number' title='Enter 1 to indicate Paid' name='".$paidID."'. min='0' max='1' value='".$reg['paid']."'>";
                    echo '</div>'; // end of form item
            } // end dance party

            
            if ($event['eventtype'] === 'Dine and Dance') {
                $ad = 0;
                if ($reg['ddattenddinner']) {
                    $ad = $reg['ddattenddinner'];
                }
                else {
                    $ad = 0;
                }
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                echo "<input type='number'  title='Enter 1 for Attend dinner' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
                echo '</div>'; // end of form item
            } // end if dine and dance

          
            if ($event['eventtype'] === 'Dinner Dance') {
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Paid?</h4>';
                echo "<input type='number' title='Enter 1 to indicate Paid' name='".$paidID."'. min='0' max='1' value='".$reg['paid']."'>";
                echo '</div>'; // end of form item
            } // end if dinner dance
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Message</h4>';
            echo "<textarea  title='Optionally enter a message for this registration' name='".$messID."' rows='2' cols='40'>".$reg['message']."</textarea>";
            echo '</div>'; // end of form item
            echo '</div>'; // end of form grid
            echo '</div>'; // end of form container
  
            } // end for each
           
            echo '<button type="submit" name="submitUpdateReg">Update the Registration(s)</button><br>';
            echo '</form>';
            echo '</div>';
        } // end updatereg

       
     
     ?>