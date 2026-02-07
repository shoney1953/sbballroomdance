<?php

             $gotEventReg = 0;
             $gotPartnerEventReg = 0;
                 if ($_SESSION['role'] === 'visitor') {
                    if ($partnerReg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                       $gotEventReg = 1;
                    }
                } else {
                   if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                      $gotEventReg = 1;
                   }
                }
            
  
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                 if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                        $gotPartnerEventReg = 1;
                 }
               }
                $comparedateTS = strtotime($compareDate); // current date in timestamp
                $eventDinnRegEnd = strtotime($event['eventdinnerregend']); // last day to register for meals
              $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
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
                    } // while $row
            } // rowcount
             $mealsNumber = count($mealChoices);
 
                          // ----------------------------------------- add guests
             if (isset($_POST["$addGuestsChk"])) {

                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                  if ($comparedateTS > $eventDinnRegEnd) {
                    echo '<h1 class="form-title"><em>Deadline to sign up for dinner has passed</em></h1>';
                  }
                echo  '<form id="regEvent" method="POST" action="regEventPt.php"> ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<input type="hidden" name="eventproductid" value='.$event['eventproductid'].'>';
                echo '<input type="hidden" name="eventcost" value='.$event['eventcost'].'>';
                echo '<input type="hidden" name="eventname" value='.$event['eventname'].'>';
                echo '<input type="hidden" name="eventtype" value='.$event['eventtype'].'>';
                echo '<input type="hidden" name="eventdate" value='.$event['eventdate'].'>';
                echo '<input type="hidden" name="orgemail" value='.$event['orgemail'].'>';
                echo '<input type="hidden" name="priceid" value='.$event['eventmempriceid'].'>';  
                echo '<input type="hidden" name="guestpriceid" value='.$event['eventguestpriceid'].'>';     
                echo '<input type="hidden" name="eventguestcost" value='.$event['eventguestcost'].'>';
            
              
              
                 echo '<div class="form-grid">';

               
                if ($event['eventform']) {
                echo '<div class="form-item" id="eventform">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>VIEW or PRINT FORM</a></h4>";
                echo '</div>'; // end of form item   
                   }
              
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Message to Event Organizer</h4>';
                  echo "<textarea  title='Enter any message to event organizer' name='message' rows='1' cols='20'></textarea>";
                  echo '</div>'; // form item
                 echo '</div>'; // form grid

        
               echo '<div class="form-grid-div">';
            
                echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Add Additional Guests?</h4>";
                echo "<h4 class='form-title-left'> <input type='checkbox'  title='Click to add guests to reservation' id='addguests' name='addguests' onclick='displayguests()'</h4>";
                echo '</div>'; // form grid div
         
                     echo '<div class="form-container hidden" id="displayguests">';
                      echo '<h4>Enter Guest Information</h4>';
         
                      echo '<div id="guestinfo" class="form-grid4">';
                      echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 First Name</h4>";
                        echo "<input type='text' title='Enter Guest 1 First Name' name='guest1fname' placeholder='Guest 1 first name' >"; 
                      echo '</div>';
                       echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Last Name</h4>";
                        echo "<input type='text' title='Enter Guest 1 Last Name' name='guest1lname' placeholder='Guest 1 last name' >"; 
                      echo '</div>';
                      echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Email</h4>";
                        echo "<input type='text' title='Enter Guest 1 Email' name='guest1email' placeholder='Guest 1 email' >"; 
                      echo '</div>';
                 


                     if ($comparedateTS <= $eventDinnRegEnd) {

                
                        echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest 1 Select Dinner</h4>";
                        echo "<input type='checkbox' title='Indicate Guest 1 will have dinner' id='guest1dinner' name='guest1dinner' onclick='displayG1Meals()'>"; 
                      echo '</div>';
                            
                        echo '</div>'; // end formgrid4

                      echo '<div class="form-container hidden" id="guestMealChoice1">';
                       echo '<div class="form-grid">';
                $mealsNumber = count($mealChoices);
                foreach ($mealChoices as $choice){
                  $guest1Chk = 'g1meal'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($mealsNumber === 1) {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$guest1Chk."' name='".$guest1Chk."'>".$choice['mealname']."</h4>"; 
                  } else {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$guest1Chk."' name='".$guest1Chk."'>".$choice['mealname']."</h4>";
                  }
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
                   echo '</div>'; // end form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter guest1 Dietary Restrictions' name='dietaryg1'  >"; 
                  echo "</div>";  // form item
                  
                }
           
                  echo '</div>'; // end form grid div hidden

                   echo '<div id="guestinfo" class="form-grid4">';
                        echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest2 First Name</h4>";
                        echo "<input type='text' title='Enter Guest 2 First Name' name='guest2fname' placeholder='Guest 2 first name' >"; 
                      echo '</div>';
                       echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Last Name</h4>";
                        echo "<input type='text' title='Enter Guest 2 Last Name' name='guest2lname' placeholder='Guest 2 last name' >"; 
                      echo '</div>';
                      echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Email</h4>";
                        echo "<input type='text' title='Enter Guest 2 Email' name='guest2email' placeholder='Guest 2 email' >"; 
                      echo '</div>';

                          if ($comparedateTS <= $eventDinnRegEnd) {
                         echo '<div class="form-item">';
                        
                       echo "<h4 class='form-item-title'>Guest 2 Select Dinner</h4>";
                        echo "<input type='checkbox' title='Indicate Guest 2 will have dinner' id='guest2dinner' name='guest2dinner' onclick='displayG2Meals()' >"; 
                      echo '</div>';
                           echo '</div>';
                  echo '<div class="form-container hidden" id="guestMealChoice2">';
                       echo '<div class="form-grid">';
 
                foreach ($mealChoices as $choice){
                  $guest2Chk = 'g2meal'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($mealsNumber === 1) {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$guest2Chk."' name='".$guest2Chk."'>".$choice['mealname']."</h4>"; 
                  } else {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$guest2Chk."' name='".$guest2Chk."'>".$choice['mealname']."</h4>";
                  }
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
                   echo '</div>'; // end form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Guest2 Dietary Restrictions' name='dietaryg2'  >"; 
                  echo "</div>";  // form item
                            
                  
                  echo '</div>'; // end form grid div hidden
                            }
                     echo '</div>';
                echo '</div>'; // form container
                  // }
                   echo '<div class="form-grid">';
                echo '<div class="form-item">';
                
                echo "<h4 class='form-item-title-emp'>PAY ONLINE?</h4>";
                echo "<input  type='checkbox' class='checkbox-red' title='Check to PAY ONLINE NOW' id='payonline' name='payonline' checked onclick='togglePay1()'>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title-emp'>or PAY Manually Later?</h4>";
                echo "<input  type='checkbox' class='checkbox-red' title='Check to PAY the Treasurer Later' id='paylater' name='paylater' onclick='togglePay2()'>";
                echo '</div>';

               echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
               echo '</form>';
                 echo '</div>'; // form grid div
                echo '</div>'; // form container
              }  // end add guests check
                            // ----------------------------------------- add registrations
             if (isset($_POST["$regChk"])) {
           
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                if ($comparedateTS > $eventDinnRegEnd) {
                    echo '<h3><em>Deadline to sign up for dinner has passed</em></h3>';
                  }
                 echo  '<form id="regEvent" method="POST" action="regEventPt.php"> ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<input type="hidden" name="eventproductid" value='.$event['eventproductid'].'>';
                echo '<input type="hidden" name="eventcost" value='.$event['eventcost'].'>';
                echo '<input type="hidden" name="eventname" value='.$event['eventname'].'>';
                echo '<input type="hidden" name="eventtype" value='.$event['eventtype'].'>';
                echo '<input type="hidden" name="eventdate" value='.$event['eventdate'].'>';
                echo '<input type="hidden" name="orgemail" value='.$event['orgemail'].'>';
                echo '<input type="hidden" name="priceid" value='.$event['eventmempriceid'].'>';  
                echo '<input type="hidden" name="guestpriceid" value='.$event['eventguestpriceid'].'>';     
                echo '<input type="hidden" name="eventguestcost" value='.$event['eventguestcost'].'>';
            
              
                  if ($_SESSION['role'] === 'visitor') {
                  echo '<input type="hidden" name="visitor" value="1">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                } else {
                  echo '<input type="hidden" name="visitor" value="0">';
                echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                }
                  if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                   echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                   echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                   echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                 }
                 echo '<div class="form-grid">';

               
                if ($event['eventform']) {
                echo '<div class="form-item" id="eventform">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>VIEW or PRINT FORM</a></h4>";
                echo '</div>'; // end of form item   
                   }
              
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Message to Event Organizer</h4>';
                  echo "<textarea  title='Enter any message to event organizer' name='message' rows='1' cols='20'></textarea>";
                  echo '</div>'; // form item
                 echo '</div>'; // form grid
                 if (!$gotEventReg) {
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                if ($_SESSION['role'] === 'visitor') {
                 echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']." ".$_SESSION['visitoremail']."</h4>";
               } else {
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
               }
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem1CHK' name='mem1Chk' checked>";
                echo '</div>';
                 if ($comparedateTS <= $eventDinnRegEnd) {
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Dinner?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='ddattdin1' name='ddattdin1' onclick='displayMeals1()'>";
                echo '</div>';
                echo '</div>';
                 echo '<div class="form-container hidden" id="memMealChoice1">';
                if ($_SESSION['role'] === 'visitor') {
                  echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname'].":</h4>";
                } else {
                  echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname'].":</h4>";
                }
             
                echo '<div class="form-grid">';
                $mealsNumber = count($mealChoices);
                foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($mealsNumber === 1) {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$mealChk."' name='".$mealChk ."'>".$choice['mealname']."</h4>"; 
                  } else {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk ."'>".$choice['mealname']."</h4>";
                  }
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
                   echo '</div>'; // end form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr1' value='".$_SESSION['dietaryrestriction']."' >"; 
                  echo "</div>";  // form item

                 }
                  echo '</div>'; // end form grid div hidden
                 }
                  echo '</div>'; // end form grid div
                 }  // end not goteventreg

                // 
                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if (!$gotPartnerEventReg) {
                  echo '<div class="form-grid-div">';
                   echo '<div class="form-grid">';
                   echo "<div class='form-item'>";
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";      
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem2CHK' name='mem2Chk' checked>";
                echo '</div>';
                 echo '<div class="form-item">';
                  if ($comparedateTS <= $eventDinnRegEnd) {
            
                echo "<h4 class='form-item-title'>Attend Dinner?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='ddattdin2' name='ddattdin2' onclick='displayMeals2()'>";
                echo '</div>'; // form item
                echo '</div>'; // form grid 
                echo '<div class="form-container hidden" id="memMealChoice2">';
                echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname'].":</h4>";
    
                echo '<div class="form-grid">';
         
                  foreach ($mealChoices as $choice){
                  $mealChk2 = 'meal2'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($mealsNumber === 1) {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$mealChk2."' name='".$mealChk2 ."'>".$choice['mealname']."</h4>";
                  } else {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'>".$choice['mealname']."</h4>";
                  }
                    echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  // echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice

                  echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr2' value='".$_SESSION['partnerdietaryrestriction']."' >"; 
                  echo "</div>";
                  
                  
                  } // end of date check
 
                  
          
               echo '</div>'; // form grid
                  echo '</div>'; // form grid            
              //  echo '</div>'; // form grid div
               echo '<div class="form-grid-div">';
            
                echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Add Guests?</h4>";
                echo "<h4 class='form-title-left'> <input type='checkbox'  title='Click to add guests to reservation' id='addguests' name='addguests' onclick='displayguests()'</h4>";
                echo '</div>'; // form grid div
         
                     echo '<div class="form-container hidden" id="displayguests">';
                      echo '<h4>Enter Guest Information</h4>';
         
                      echo '<div id="guestinfo" class="form-grid4">';
                      echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 First Name</h4>";
                        echo "<input type='text' title='Enter Guest 1 First Name' name='guest1fname' placeholder='Guest 1 first name' >"; 
                      echo '</div>';
                       echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Last Name</h4>";
                        echo "<input type='text' title='Enter Guest 1 Last Name' name='guest1lname' placeholder='Guest 1 last name' >"; 
                      echo '</div>';
                      echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest1 Email</h4>";
                        echo "<input type='text' title='Enter Guest 1 Email' name='guest1email' placeholder='Guest 1 email' >"; 
                      echo '</div>';
                      
                if ($comparedateTS <= $eventDinnRegEnd) {
                        echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Guest 1 Select Dinner</h4>";
                        echo "<input type='checkbox' title='Indicate Guest 1 will have dinner' id='guest1dinner' name='guest1dinner' onclick='displayG1Meals()'>"; 
                      echo '</div>';
                        echo '</div>'; // end formgrid4

                      echo '<div class="form-container hidden" id="guestMealChoice1">';
                       echo '<div class="form-grid">';
                $mealsNumber = count($mealChoices);
                foreach ($mealChoices as $choice) {
                  $guest1Chk = 'g1meal'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($mealsNumber === 1) {
                    echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$guest1Chk."' name='".$guest1Chk."'>".$choice['mealname']."</h4>"; 
                  } else {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$guest1Chk."' name='".$guest1Chk."'>".$choice['mealname']."</h4>";
                  }
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
                   echo '</div>'; // end form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter guest1 Dietary Restrictions' name='dietaryg1'  >"; 
                  echo "</div>";  // form item

                }
                  echo '</div>'; // end form grid div hidden
                  echo '<div id="guestinfo" class="form-grid4">';
                  echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Guest2 First Name</h4>";
                  echo "<input type='text' title='Enter Guest 2 First Name' name='guest2fname' placeholder='Guest 2 first name' >"; 
                  echo '</div>';
                  echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Guest2 Last Name</h4>";
                  echo "<input type='text' title='Enter Guest 2 Last Name' name='guest2lname' placeholder='Guest 2 last name' >"; 
                  echo '</div>';
                  echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Guest2 Email</h4>";
                  echo "<input type='text' title='Enter Guest 2 Email' name='guest2email' placeholder='Guest 2 email' >"; 
                  echo '</div>';
                  if ($comparedateTS <= $eventDinnRegEnd) {
                      echo '<div class="form-item">';
                      echo "<h4 class='form-item-title'>Guest 2 Select Dinner</h4>";
                      echo "<input type='checkbox' title='Indicate Guest 2 will have dinner' id='guest2dinner' name='guest2dinner' onclick='displayG2Meals()' >"; 
                      echo '</div>';
                      echo '</div>';
                      echo '<div class="form-container hidden" id="guestMealChoice2">';
                      echo '<div class="form-grid">';
                      $mealsNumber = count($mealChoices);
                      foreach ($mealChoices as $choice){
                        $guest2Chk = 'g2meal'.$choice['id'];
                         echo '<div class="form-item">';
                        if ($mealsNumber === 1) {
                          echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' checked id='".$guest2Chk."' name='".$guest2Chk."'>".$choice['mealname']."</h4>"; 
                        } else {
                          echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$guest2Chk."' name='".$guest2Chk."'>".$choice['mealname']."</h4>";
                        }
                 
                        echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                        echo '</div>'; // end of form item         
                     } // for each mealchoice
            
                    echo '</div>'; // end form grid
                    echo "<div class='form-item'>";
                    echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                    echo "<input type='text' title='Enter Guest2 Dietary Restrictions' name='dietaryg2'  >"; 
                    echo "</div>";  // form item
                           
                  echo '</div>'; // end form grid div hidden
              
            
          
                   } // end of date check for meals
                           echo '</div>';
                  echo '</div>'; // form container
                  
                   echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title-emp'>PAY ONLINE?</h4>";
                echo "<input  type='checkbox' class='checkbox-red' title='Check to PAY ONLINE NOW' id='payonline' name='payonline' checked onclick='togglePay1()'>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title-emp'>or PAY Manually Later?</h4>";
                echo "<input  type='checkbox' class='checkbox-red' title='Check to PAY the Treasurer Later' id='paylater' name='paylater' onclick='togglePay2()'>";
                echo '</div>';

               echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
               echo '</form>';
                 echo '</div>'; // form grid div
                echo '</div>'; // form container
     
              }  // regchk
    }
              // ----------------------------------------- delete
             if (isset($_POST["$delChk"])) {
         
               $gotEventReg = 0;
               $gotPartnerEventReg = 0;
              if ($_SESSION['role'] === 'visitor') {
                     if ($memReg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                             $gotEventReg = 1;
                     }
                } else {
                     if ($memReg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                          $gotEventReg = 1;
                     }
                }
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                         $gotPartnerEventReg = 1;
                } 
               }
          
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';

                if ($gotEventReg) {
                    if ($memReg->paid !== '1') {
                      $remID1 = "rem".$memReg->id;
                    echo '<div class="form-grid-div">';
                    echo '<div class="form-grid">';
                  
                    echo '<div class="form-item">';
                    if ($_SESSION['role'] === 'visitor') {
                        echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                    } else {
                      echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                    }
                    echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                    echo '</div>'; // form item
                    echo '</div>'; // form grid
                    echo '</div>'; // form grid div
                    }
                   
                }


                 if ($gotPartnerEventReg) {    
                  if ($partnerReg->paid !== '1')  {
                    echo '<div class="form-grid-div">';
                    echo '<div class="form-grid">';
                    $remID2 = "rem".$partnerReg->id;               
                    echo '<div class="form-item">';
                    echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                    echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                    echo '</div>';
                     echo '</div>'; // end of form grid
                     echo '</div>';  // end form grid div
                  }                          
                 }
                 $guests = [];
                 $_SESSION['guests'] = [];
                  $result = $guestEventReg->read_ByEventIdRegBy($event['id'], $_SESSION['username']);

                  $rowCount = $result->rowCount();
                  $num_guests  = $rowCount;
         
                  if ($num_guests > 0) {

                      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                          extract($row);
                          $guest_item = array(
                              'id' => $id,
                              'firstname' => $firstname,
                              'lastname' => $lastname,
                              'eventid' => $eventid,
                              'eventname' => $eventname,
                              'eventdate' => $eventdate,
                              'eventtype' => $eventtype,
                              'orgemail' => $orgemail,
                              'message' => $message,
                              'userid' => $userid,
                              'email' => $email,
                              'paid' => $paid,
                              'ddattenddance' => $ddattenddance,
                              'ddattenddinner' => $ddattenddinner,
                              'registeredby' => $registeredby,
                              'mealchoice' => $mealchoice,
                              'dietaryrestriction' => $dietaryrestriction,
                              'paidonline' => $paidonline,    
                              'mealname' => $mealname,     
                              'modifiedby' => $modifiedby,
                              'modifieddate' => $modifieddate,
                              'dwop' => $dwop,
                              'guest' => $guest,
                              'numhotdogs' => $numhotdogs,
                              'numhdbuns' => $numhdbuns,
                              'numhamburgers' => $numhamburgers,
                              'numhbbuns' => $numhbbuns,
                              'vegetarian' => $vegetarian,
                              'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
                          );
                          array_push($guests, $guest_item);
                          
                      } // end while
                      $_SESSION['guests'] = $guests;
                      foreach ($guests as $guest) {

                        if ($guest['paid'] !== '1')  {
                    echo '<div class="form-grid-div">';
                    echo '<div class="form-grid">';
                    $remGuestID = "remguest".$guest['id'];   
                    $guestID = 'guestid'.$guest['id']  ;          
                    echo '<div class="form-item">';
                    echo "<h4 class='form-item-title'>Remove registration for ".$guest['firstname']."</h4>";
                    echo "<input type='checkbox'  title='Check to remove registration' id='".$remGuestID."' name='".$remGuestID."' checked>";
                    echo '<input type="hidden" name="'.$guestID.'" value='.$guest['id'].'>';
                    echo '</div>';
                     echo '</div>'; // end of form grid
                     echo '</div>';  // end form grid div
                  }                         
                      } // end foreach guest
                  } // end num guest > 0
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo '</div>'; // end of form container
                echo  '</form>';
             } // end of delete check
// ----------------------------------------- update
            if (isset($_POST["$upChk"])) {
         
               $gotEventReg = 0;
               $gotPartnerEventReg = 0;
               $guests = [];
              if ($_SESSION['role'] === 'visitor') {
                     if ($memReg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                 
                             $gotEventReg = 1;
                     }
                } else {
                     if ($memReg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                          $gotEventReg = 1;
                              
                     }
                }
             if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                         $gotPartnerEventReg = 1;
                } 
               }
           
             $mealChoices = [];
              $result = $mChoices->read_ByEventId($event['id']);
                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
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
              } // while $row
            } // if rowcount
            echo '<div class="form-container"';
            echo "<h1 class='form-title'>Update Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
            echo '<form method="POST" name="MemberUpdateEventMeals" action="updateMealEventRegt.php">';  
            echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
            if ($gotEventReg) {
             echo '<input type="hidden" name="regID1" value='.$memReg->id.'>';
             echo '<div class="form-grid-div">'; 
             echo '<div class="form-grid">';
               
             echo '<div class="form-item">';
            if ($_SESSION['role'] === 'visitor') {
                  echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
            } else {
             echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
            }
        
            echo '</div>'; // end of form item
               if (($memReg->ddattenddinner === '1') || ($memReg->paid !== '1')) {
             echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Update?</h4>";
                 echo "<input type='checkbox'  title='Update member 1' id='mem1upd' name='mem1update' >";
             echo '</div>'; // end of form item
               } else {
                echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Member cannot be modified because dinner was not selected and registration was paid.</h4>";
            
                        echo '</div>'; // end of form item
               }
            if ($memReg->paid !== '1') {
                  echo '<div class="form-item">';
                               echo "<h4 class='form-item-title'>Attend Dinner</h4>";
                   if ($memReg->ddattenddinner === '1') {
             
                        echo "<input type='checkbox'  title='Attend Dinner' id='ddattdin1' name='ddattdin1' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Attend dinner' id='ddattdin1' name='ddattdin1' >";
                  }
                  echo '</div>'; // end of form item
            } else {
                echo "<input type='hidden'   id='ddattdin1' name='ddattdin1' value".$memReg->ddattenddinner.">";
            }
            if ($memReg->ddattenddinner === '1') {
               foreach ($mealChoices as $choice){
               $mealChk = 'meal'.$choice['id'];
        
                     echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";
                  if ($memReg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."'>";
                  }
                    echo '</div>'; // end of form item  
            }
            }
          
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
          }  // end of goteventreg
           if ($gotPartnerEventReg) {
            echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
            echo '<div class="form-grid-div">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
            echo '</div>'; // end of form item
               if (($partnerReg->ddattenddinner === '1') || ($partnerReg->paid !== '1')) {
              echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Update?</h4>";
                 echo "<input type='checkbox'  title='Update member 2' id='mem2upd' name='mem2update' >";
             echo '</div>'; // end of form item
               } else {
                echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Partner cannot be modified because dinner was not selected and registration was paid.</h4>";
            
                        echo '</div>'; // end of form item
               }
            if ($partnerReg->paid !== '1') {
                  echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Attend Dinner</h4>";
                   if ($partnerReg->ddattenddinner === '1') {
                        echo "<input type='checkbox'  title='Meal Choice' id='ddattdin2' name='ddattdin2' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='ddattdin2' name='ddattdin2' >";
                  }
                  echo '</div>'; // end of form item                
            } else {
                echo "<input type='hidden'   id='ddattdin2' name='ddattdin2' value".$partnerReg->ddattenddinner.">";
            }
             if ($partnerReg->ddattenddinner === '1') {
                   foreach ($mealChoices as $choice){
                     $mealChk2 = 'meal2'.$choice['id'];
                    echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";

                  if ($partnerReg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2."'>";
                  }
                  echo '</div>';
               
            }
             }
              

        
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               }  // end of got partner reg
               $_SESSION['guests'] = [];
                  $result = $guestEventReg->read_ByEventIdRegBy($event['id'], $_SESSION['username']);

                  $rowCount = $result->rowCount();
                  $num_guests  = $rowCount;
         
                  if ($num_guests > 0) {

                      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                          extract($row);
                          $guest_item = array(
                              'id' => $id,
                              'firstname' => $firstname,
                              'lastname' => $lastname,
                              'eventid' => $eventid,
                              'eventname' => $eventname,
                              'eventdate' => $eventdate,
                              'eventtype' => $eventtype,
                              'orgemail' => $orgemail,
                              'message' => $message,
                              'userid' => $userid,
                              'email' => $email,
                              'paid' => $paid,
                              'ddattenddance' => $ddattenddance,
                              'ddattenddinner' => $ddattenddinner,
                              'registeredby' => $registeredby,
                              'mealchoice' => $mealchoice,
                              'dietaryrestriction' => $dietaryrestriction,
                              'paidonline' => $paidonline,    
                              'mealname' => $mealname,     
                              'modifiedby' => $modifiedby,
                              'modifieddate' => $modifieddate,
                              'dwop' => $dwop,
                              'guest' => $guest,
                              'numhotdogs' => $numhotdogs,
                              'numhdbuns' => $numhdbuns,
                              'numhamburgers' => $numhamburgers,
                              'numhbbuns' => $numhbbuns,
                              'vegetarian' => $vegetarian,
                              'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
                          );
                          array_push($guests, $guest_item);
                          $_SESSION['guests'] = $guests;
                      } // end while
                      foreach ($guests as $guest) {
                        $regGuestID = "regGuestId".$guest['id'];
                        $mealChoiceGuest = "mcg".$guest['id'];
                        $guestAttDinner = "gad".$guest['id'];
                        $guestUpd = 'gup'.$guest['id'];
                        echo '<input type="hidden" name="'.$regGuestID.'" value='.$guest['id'].'>';
                        echo '<div class="form-grid-div">';
                        echo '<div class="form-grid">';
                        echo '<div class="form-item">';
                        echo "<h4>".$guest['firstname']."'s Information</h4>";
                        echo '</div>'; // end of form item
                        if (($guest['ddattenddinner'] ==='1') || ($guest['paid'] !== '1')) {
                          echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Update?</h4>";
                        echo "<input type='checkbox'  title='Update Guest' id='".$guestUpd."' name='".$guestUpd."' >";
                        echo '</div>'; // end of form item
                        } else {
                            echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Guest cannot be modified because dinner was not selected and registration was paid.</h4>";
            
                        echo '</div>'; // end of form item
                        }

                        if ($guest['paid'] !== '1') {
                              echo '<div class="form-item">';
                              echo "<h4 class='form-item-title'>Attend Dinner</h4>";
                              if ($guest['ddattenddinner'] === '1') {
                                    echo "<input type='checkbox'  title='Meal Choice' id='".$guestAttDinner."' name='".$guestAttDinner."' checked>";
                              } else {
                                echo "<input type='checkbox'  title='Meal Choice' id='".$guestAttDinner."' name='".$guestAttDinner."' >";
                              }
                              echo '</div>'; // end of form item                
                        } else {
                            echo "<input type='hidden'   id='".$guestAttDinner."' name='".$guestAttDinner."' value".$guest['ddattenddinner'].">";
                        } // guest not paid
                     if ($guest['ddattenddinner'] === '1') {
                       foreach ($mealChoices as $choice){
                        $guestMeal = 'guestmeal'.$guest['id'].$choice['id'];
                        echo '<div class="form-item">';
                       echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";

                      if ($guest['mealchoice'] === $choice['id']) {
                            echo "<input type='checkbox'  title='Meal Choice' id='".$guestMeal."' name='".$guestMeal."' checked>";
                      } else {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$guestMeal."' name='".$guestMeal."'>";
                      }
                      echo '</div>';
                  }
           

               
            } // each meal choice
                   echo '</div>'; // end form grid
                  echo '</div>'; // end form grid div
                      } // 
        

             } // for each guest
        
            echo '<button type="submit" name="submitModifyRegs">Modify Registration(s)</button>';
            echo '</div>'; // end of form container
              echo '</div>'; // end form container
              echo '</form>';
              }
            


?>

