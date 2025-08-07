<?php
     if ($deleteReg) {
   
        echo '<div class="form-grid-div">';
        
            echo '<form method="POST" action="deleteEventReg.php">';

            foreach ($upcomingEvents as $event) {
                $eventNum = (int)substr($drChk,2);
                if ($event['id'] == $eventNum) {
                    break;
                }
            }
            echo '<h2>Delete registrations to the following Event</h2>';
            echo '<input type=hidden name="eventid" value="'.$eventNum.'">';
    
            echo '<table>';
            echo '<thead>';
           
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Event Name</th>';
            echo '<th>Event Type</th>';
            echo '<th>Event Date</th>';
            echo '<th>Registration Opens</th>';
            echo '<th>Dance Only Registration Closes</th>';
            echo '</tr>';
            echo '</thead>';
    
            echo '<tbody>';
            echo '<tr>';
            echo "<td>".$event['id']."</td>";
            echo "<td>".$event['eventname']."</td>";
            echo "<td>".$event['eventtype']."</td>";
            echo "<td>".$event['eventdate']."</td>";
            echo "<td>".$event['eventregopen']."</td>";
            echo "<td>".$event['eventregend']."</td>";
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

?>