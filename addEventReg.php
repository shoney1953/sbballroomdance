<?php

if ($addReg) {
    echo '<h2>Add Either Member <em> or </em>  Visitor Registrations to the following Event.</h2>';
 
  
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
        echo '<th>Registration Opens</th>';
        echo '<th>Registration Closes</th>';
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
        echo '</div>';
        echo '';
        echo '<div class="form-grid2">';
        echo '<div class="form-grid-div">'; 
    echo '<br><br><table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=7>Add Member Registrations</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th colspan=7>Select One or all of the following members</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Add</th>';
    
    if (($event['eventtype'] === 'Dine and Dance') || ($event['eventtype'] === 'Dance Party')){
        echo '<th>Attend<br>Dinner?</th>';
        echo '<th>Paid?</th>';
    }
    if ($event['eventtype'] === 'BBQ Picnic'){
        echo '<th>Attend<br>Dinner?</th>';
        echo '<th>Cornhole?</th>';
        echo '<th>Softball?</th>';
    }
    if ($event['eventtype'] === 'Dinner Dance'){
  
        echo '<th>Paid?</th>';
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
        $pdDinn = "dpaid".$usr['id'];
        $ch = "ch".$usr['id'];
        $sb = "sb".$usr['id'];

        echo '<tr>';
         if ($event['eventtype'] === 'Dinner Dance'){
           echo "<td><input  title='Select to Add Registrations' type='checkbox' id='".$usrID."'  name='".$usrID."' onclick='displayMeals1(".$usr['id'].")' ></td>";
         } else {
           echo "<td><input  title='Select to Add Registrations' type='checkbox'  name='".$usrID."'></td>";
         }
        if (($event['eventtype'] === 'Dine and Dance') || ($event['eventtype'] === 'Dance Party') ){
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' id='".$attDin."' name='".$attDin."' onclick='displayMeals2(".$usr['id'].")'></td>";
            echo "<td><input title='Select to indicate Registrant has Paid' type='checkbox' name='".$pdDinn."'></td>";
        }
        if ($event['eventtype'] === 'Dinner Dance'){
            echo "<td><input title='Select to indicate Registrant has Paid' type='checkbox' name='".$pdDinn."'></td>";
        }
        if ($event['eventtype'] === 'BBQ Picnic')  {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='".$attDin."'></td>";
            echo "<td><input title='Select to indicate Registrant will play Cornhole' type='checkbox' name='".$ch."'></td>";
            echo "<td><input title='Select to indicate Registrant will play Softball' type='checkbox' name='".$sb."'></td>";
        }
        echo "<td >".$usr['firstname']."</td>";
        echo "<td>".$usr['lastname']."</td>";
        echo "<td>".$usr['email']."</td>";
        echo '</tr>';

        if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
       
             if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {
              $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($event['id']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
                    } // while
                    if ($rowCount > 0) {
                        echo "<tr>";
                        echo "<td> </td>";  
                        echo "<td colspan='5'>";
                        $fcID = "fc".$usr['id'];

                        echo "<div id='".$fcID."' class='form-container hidden'>";  
                    echo "<div class='form-grid'>";
                    foreach ($mealChoices as $choice) {
    
                          $mcID = "mc".$usr['id'].$choice['id'];

                          echo "<div class='form-item'>";
                          echo '<h4 class="form-item-title">Select?</h4>'; 
                          echo "<input  title='Select This Meal' type='checkbox'name='".$mcID."'>";
                             echo "<h5 class='form-item-title'>".$choice['mealchoice']."</h5>";
                         $price = number_format($choice['memberprice']/100,2);
                          echo "<h5 class='form-item-title'>".$price."</h5>";
                          echo "</div>";

                    
                      } // foreach
                       $drID = "dr".$usr['id'];
                      echo "<div class='form-item'>";
                      echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                      echo "<input type='text' title='Enter Member Dietary Restrictions' name='".$drID."' >"; 
                      echo "</div>";
                         echo "</div>";
          
                    echo "</div>";
                      echo "</td>";
                    echo "</tr>";
                    } // rowcount
                    
             } // eventtype
   
        }
     } // testmode
    }  // usr
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
        echo '<th colspan=6>Add Visitor Registration</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Visitor First Name</td>';
        echo '<th>Last Name</td>';
        echo '<th>Email</td>';
    
        if (($event['eventtype'] === 'Dine and Dance') || ($event['eventtype'] === 'Dance Party') ) {
            echo '<th>Attend<br>Dinner?</th>';
            echo '<th>Paid?</th>';
        }
        if ($event['eventtype'] === 'Dinner Dance') {

            echo '<th>Paid?</th>';
        }
        echo '<th>Notes</td>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname1'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname1'></td>";
        echo "<td><input type='email' name='email1' required></td>";
        if (($event['eventtype'] === 'Dine and Dance') || ($event['eventtype'] === 'Dance Party')){
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin1'></td>";
            echo "<td><input title='Select to indicate Registrant has paid' type='checkbox' name='pddinn1'></td>";
        }
        if ($event['eventtype'] === 'Dinner Dance'){
            echo "<td><input title='Select to indicate Registrant has paid' type='checkbox' name='pddinn1'></td>";
        }
        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes1' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
       if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
       
             if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {
              $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($event['id']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
                    } // while
              
                    echo "<tr>";
                    echo "<td> </td>";  
                    echo "<td colspan='5>";
                
                    echo "<div  class='form-container'>";  
                    echo "<div class='form-grid'>";

                        foreach ($mealChoices as $choice) {
                          $mcID = "mcVisitor1".$choice['id'];
                          echo "<div class='form-item'>";
                          echo '<h4 class="form-item-title">Select?</h4>'; 
                          echo "<input  title='Select This Meal' type='checkbox'name='".$mcID."'>";
                          echo "<h5 class='form-item-title'>".$choice['mealchoice']."</h5>";
                          $price = number_format($choice['guestprice']/100,2);
                          echo "<h5 class='form-item-title'>".$price."</h5>";
                          echo "</div>";
                         } // foreach

                       $drID = "drVisitor1";
                      echo "<div class='form-item'>";
                      echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                      echo "<input type='text' title='Enter Member Dietary Restrictions' name='".$drID."' >"; 
                      echo "</div>";
                         echo "</div>";
          
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                  
                }   // row count 
             } // eventtype
   
        }  // testmode
 
  
        // second visitor
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname2'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname2'></td>";

//             second visitor reg
        echo "<td><input type='email' name='email2'></td>";
        if (($event['eventtype'] === 'Dine and Dance') || ($event['eventtype'] === 'Dance Party'))  {
            echo "<td><input title='Select to indicate Registrant will attend dinner' type='checkbox' name='attdin2'></td>";
            echo "<td><input title='Select to indicate Registrant has paid' type='checkbox' name='pddinn2'></td>";
        }
        if ($event['eventtype'] === 'Dinner Dance'){
            echo "<td><input title='Select to indicate Registrant has paid' type='checkbox' name='pddinn2'></td>";
        }

        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes2' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
        if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
        if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {

                if ($num_meals > 0) {

                    echo "<tr>";
                    echo "<td> </td>";  
                    echo "<td colspan='5>";
                
                    echo "<div class='form-container'>";  
                    echo "<div class='form-grid'>";

                        foreach ($mealChoices as $choice) {
                          $mcID = "mcVisitor2".$choice['id'];
                          echo "<div class='form-item'>";
                          echo '<h4 class="form-item-title">Select?</h4>'; 
                          echo "<input  title='Select This Meal' type='checkbox'name='".$mcID."'>";
                          echo "<h5 class='form-item-title'>".$choice['mealchoice']."</h5>";
                          $price = number_format($choice['guestprice']/100,2);
                          echo "<h5 class='form-item-title'>".$price."</h5>";
                          echo "</div>";
                         } // foreach

                       $drID = "drVisitor2";
                      echo "<div class='form-item'>";
                      echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                      echo "<input type='text' title='Enter Member Dietary Restrictions' name='".$drID."' >"; 
                      echo "</div>";
                         echo "</div>";
          
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                  
                }   // num_meals
             } // eventtype
   
        }  // testmode
        echo '</tbody>';
        echo '</table>';
    
        echo '<button type="submit" name="submitAddVisitorReg">Add Visitor Registration</button> ';
        echo '</form>'; 
        echo '</div>';
        echo '</div>';

     } // end add reg

?>