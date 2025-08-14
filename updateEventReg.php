<?php

     if ($updateReg) {

            echo '<form method="POST" action="updateEventReg.php">';
            foreach ($upcomingEvents as $event) {
                $eventNum = (int)substr($urChk,2);
                if ($event['id'] == $eventNum) {
                    break;
                }
            }
   
          $mealChoices = [];
          $numMeals = 0;
              $result = $mChoices->read_ByEventId($event['id']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {
                     $numMeals = $rowCount;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
                    } // while
                   
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
            $chID = "ch".$reg['id'];
            $sbID = "sb".$reg['id'];
            $fnamID = "fnam".$reg['id'];
            $lnamID = "lnam".$reg['id'];
            $emailID = "email".$reg['id'];
            $useridID = "userid".$reg['id'];
            $messID = "mess".$reg['id'];
            $paidID = "paid".$reg['id'];
            $dddinID = "dddin".$reg['id'];
            $useridID = "userid".$reg['id'];


            echo '<div class="form-container">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Update?</h4>';
            if ($event['eventtype'] !== 'Dinner Dance') {
                echo "<input title='Select to Update Registration' type='checkbox' name='".$updID."'>"; 
            } else {
               echo "<input title='Select to Update Registration' type='checkbox' id=".$updID." name='".$updID."' onclick='displayMeals3U(".$reg['id'].")'>"; 
            }
       
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
            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">User ID</h4>';
            echo "<input type='hidden'  name='".$useridID."' value='".$reg['userid']."'>";
            // echo '</div>'; // end of form item
           
            if ($event['eventtype'] === 'BBQ Picnic') {
                $ad = 0;
                if ($reg['ddattenddinner']) {
                    $ad = $reg['ddattenddinner'];
                }
                else {
                    $ad = 0;
                }

                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                echo "<input type='number'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
       
                echo '</div>'; // end of form item
                $ch = 0;
                if ($reg['cornhole']) {
                    $ch = $reg['cornhole'];
                }
                else {
                    $ch = 0;
                }
                
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                echo "<input type='number'  title='Enter 1 for Play Cornhole' name='".$chID."' min='0' max='1' value='".$ch."'>";
                echo '</div>'; // end of form item

                $sb = 0;
                if ($reg['softball']) {
                    $sb = $reg['softball'];
                }
                else {
                    $sb = 0;
                }
    
          
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
                echo "<input type='number'  title='Enter 1 for Play Softball' name='".$sbID."' min='0' max='1' value='".$sb."'>";
                echo '</div>'; // end of form item
            }
         
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
                 echo "<input type='number'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
       
                
                echo '</div>'; // end of form item
                // echo '<div class="form-item">';  
                // echo '<h4 class="form-item-title">Paid?</h4>';
                // echo "<input type='number' title='Enter 1 to indicate Paid' name='".$paidID."'. min='0' max='1' value='".$reg['paid']."'>";
                // echo '</div>'; // end of form item
             if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
       
             if ($event['eventtype'] === 'Dance Party') {
            
                    if ($numMeals > 0) {
                  
                        $fcuID = "fcu".$reg['id'];

                        echo "<div id='".$fcuID."' class='form-container hidden'>";  
                        // echo "<div class='form-grid'>";
                        foreach ($mealChoices as $choice) {
    
                          $mcID = "mc".$reg['id'].$choice['id'];

                          echo "<div class='form-item'>";
                          echo '<h4 class="form-item-title">Select '.$choice['mealname'].'</h4>';
                            //    echo "<h5 class='form-item-title'>".$choice['mealname']."</h5>";
                          if ($reg['mealchoice'] === $choice['id']) {
                                echo "<input  title='Select This Meal' checked type='checkbox'name='".$mcID."'>";
                          } else {
                               echo "<input  title='Select This Meal' type='checkbox'name='".$mcID."'>";
                          } 
         
                 
                         $price = number_format($choice['memberprice']/100,2);
                          echo "<h5 class='form-item-title'>".$price."</h5>";
                          echo "</div>"; // form item mc                    
                          } // foreach mealchoice
                       $drID = "dr".$reg['id'];
                      echo "<div class='form-item'>";
                      echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                      echo "<input type='text' title='Enter Member Dietary Restrictions' name='".$drID."' value='".$reg['dietaryrestriction']."' >"; 
                      echo "</div>";  // form item dr
                   
                      echo "</div>";  // form item drS

                    } // nummeals
                    
             } // eventtype Dance Party
   
    
            } // testmode
            


     } // dance party         
            



            if ($event['eventtype'] === 'Dinner Dance') {
             if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {

            
                    if ($numMeals > 0) {
                  
                        $fcu2ID = "fcu2".$reg['id'];

                        echo "<div id='".$fcu2ID."' class='form-container hidden'>";  
                        // echo "<div class='form-grid'>";
                        foreach ($mealChoices as $choice) {
    
                          $mcID = "mc".$reg['id'].$choice['id'];

                          echo "<div class='form-item'>";
                          echo '<h4 class="form-item-title">Select?</h4>'; 
                          if ($reg['mealchoice'] === $choice['id']) {
                                echo "<input  title='Select This Meal' checked type='checkbox'name='".$mcID."'>";
                          } else {
                               echo "<input  title='Select This Meal' type='checkbox'name='".$mcID."'>";
                          }
                       
                             echo "<h5 class='form-item-title'>".$choice['mealname']."</h5>";
                         $price = number_format($choice['memberprice']/100,2);
                          echo "<h5 class='form-item-title'>".$price."</h5>";
                          echo "</div>"; // form item mc

                    
                      } // foreach
                       $drID = "dr".$reg['id'];
                      echo "<div class='form-item'>";
                      echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                      echo "<input type='text' title='Enter Member Dietary Restrictions' name='".$drID."' value='".$reg['dietaryrestriction']."'>"; 
                      echo "</div>";  // form item dr
                   
                    echo "</div>";  // form item drS

                    } //nummeals

             } // testmode         

            } // end if dinner dance
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Paid?</h4>';
            echo "<input type='number' title='Enter 1 to indicate Paid' name='".$paidID."'. min='0' max='1' value='".$reg['paid']."'>";
            echo '</div>'; // end of form item
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Message</h4>';
            echo "<textarea  title='Optionally enter a message for this registration' name='".$messID."' rows='2' cols='40'>".$reg['message']."</textarea>";
            echo '</div>';
     
            echo '</div>'; // end of form grid
            echo '</div>'; // end of form container

  
            } // end for each
           
            echo '<button type="submit" name="submitUpdateReg">Update the Registration(s)</button><br>';
            echo '</form>';
            echo '</div>';
        } // end updatereg

?>