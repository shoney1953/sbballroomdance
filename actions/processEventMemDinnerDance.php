  <?php
  $gotEventReg = 0;
              $gotPartnerEventReg = 0;
                if ($_SESSION['role'] === 'visitor') {
                     if ($reg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                      $gotEventReg = 1;
                     }
                } else {
                     if ($reg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                       $gotEventReg = 1;
                     }
                }
              
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                 if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                      $gotPartnerEventReg = 1;
                 }
               }

               if (isset($_POST["$regChk"])) {
              

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
             

                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventPt.php">  ';
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
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Pay Online?</h4>";
                echo "<input type='checkbox'  title='Check to attend dinner' id='payonline' name='payonline'>";
                echo '</div>';
               

                 if ($event['eventform']) {
                echo '<div class="form-item" id="eventform">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>VIEW or PRINT FORM</a></h4>";
                echo '</div>'; // end of form item   
                   }
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Message to Event Organizer</h4>';
                  echo "<textarea  title='Enter any message to event organizer' name='message' rows='1' cols='20'></textarea>";
                  echo '</div>'; // form item
                // echo '</div>'; // form grid 
                echo '<div class="form-grid-div" id="memMealChoice">';
                 echo "<div class='form-item'>";
                if ($_SESSION['role'] === 'visitor') {
                 echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']." ".$_SESSION['useremail']."</h4>";
               } else {
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
               }
         
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem1CHK' name='mem1Chk' checked>";
                echo '</div>';
                    if ($_SESSION['role'] === 'visitor') {
                        echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname'].":</h4>";
                    } else {
                       echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname'].":</h4>";
                    }
                echo '<div class="form-grid">';

                foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
                  echo '<div class="form-item">';
           
                  echo "<h4 class='form-title-left'><input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk ."'>".$choice['mealname']." </h4>";
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
          
                echo '</div>'; // form grid
                  echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr1' value='".$_SESSION['dietaryrestriction']."' >"; 
                  echo "</div>";
                echo '</div>'; // form grid div

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                echo '<div class="form-grid-div" id="partMealChoice">';
                echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";
    
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem2CHK' name='mem2Chk' checked>";
                echo "<h4 class='form-title-left'>Meal Selection for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname'].":</h4>";
                echo '<div class="form-grid">';
       
                foreach ($mealChoices as $choice){
                  $mealChk2 = 'meal2'.$choice['id'];
                  echo '<div class="form-item">';
                  echo "<h4 class='form-title-left'><input type='checkbox'  title='Meal Choice' id='".$mealChk2."' name='".$mealChk2 ."'>".$choice['mealname']." </h4>";
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice

                  echo '</div>'; // form grid
                  // echo "<div class='form-item'>";
                  echo '<h4 class="form-item-title">Dietary Restriction?</h4>';
                  echo "<input type='text' title='Enter Member Dietary Restrictions' name='dietaryr2' value='".$_SESSION['partnerdietaryrestriction']."' >"; 
                  // echo "</div>";
                  echo '</div>'; // form grid div

                }
                
               echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container
              }


              if (isset($_POST["$delChk"])) {
   
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
               if ($gotEventReg) {
                 $remID1 = "rem".$reg->id;
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                if ($_SESSION['role'] === 'visitor') {
                    echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                } else {
                   echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                }
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>'; //form item
                 echo '</div>'; // form grid
                    echo '</div>'; // form grid div
              } // got eventreq

                 if ($gotPartnerEventReg) {
                  $remID2 = "rem".$partnerReg->id;
                   echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                echo '</div>';
                echo '</div>'; // form grid
                echo '</div>'; // form grid div
                } // end got partner
  
                
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo  '</form>';
                echo '</div>'; // end of form container
             } // end of delete check

            if (isset($_POST["$upChk"])) {

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
            echo '<div class="form-container"';
            echo "<h1 class='form-title'>Update Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
            echo '<form method="POST" name="MemberUpdateEventMeals" action="updateMealEventRegt.php">';  
            echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
            if ($gotEventReg) {
               echo '<input type="hidden" name="ddattdin1" value="1">';
                 echo '<div class="form-grid-div">';
            
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                    echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
               } else {
                echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
               }
        
            echo '</div>'; // end of form item
                 
            foreach ($mealChoices as $choice){
               $mealChk = 'meal'.$choice['id'];
            //    if ($reg->mealchoice !== '0') {
                     echo '<div class="form-item">';
                 echo "<h4 class='form-item-title'>Select ".$choice['mealname']."</h4>";
                  if ($reg->mealchoice === $choice['id']) {
                        echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."' checked>";
                  } else {
                     echo "<input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk."'>";
                  }
                    echo '</div>'; // end of form item
               
            }
       
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
            }
          
            if ($gotPartnerEventReg) {
                    echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                    echo '<input type="hidden" name="ddattdin2" value="1">';

           echo '<div class="form-grid-div">';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
            echo '</div>'; // end of form item
          
    
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
        
            echo '</div>'; // end form grid
             echo '</div>'; // end form grid div
               }
        
            echo '<button type="submit" name="submitModifyRegs">Modify Registration(s)</button>';
            echo '</div>'; // end of form container
              echo '</div>'; // end form container
              echo '</form>';
           }

           ?>