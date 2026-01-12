                <?php
        
                $gotEventReg = 0;
                $gotPartnerEventReg = 0;
                var_dump($_SESSION['partnerid']);
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
   
            if (isset($_POST["$regChk"])) {
              
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
                echo '<div class="form-grid-div">';
              
                echo '<div class="form-grid">';
              
               if (!$gotEventReg) {
                  if (isset($_SESSION['username'])) {
                  if ($_SESSION['role'] === 'visitor') {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                  } else {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                  }
  
                }

                echo '<div class="form-item">';

               if ($_SESSION['role'] === 'visitor') {
                 echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']." ".$_SESSION['useremail']."</h4>";
               } else {
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
               }
         
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem1CHK' name='mem1Chk' checked>";
                echo '</div>';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Meal?</h4>";
                echo "<input type='checkbox'  title='Check to attend meal' id='ddattm1' name='ddattm1' onclick='displayMealsB1()'>";
            
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
      echo '</div>';
           
                  echo '</div>'; // end form grid div hidden
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Cornhole?</h4>";
                echo "<input type='checkbox'  title='Check to play Cornhole' id='ch1' name='ch1'>";
                echo '</div>';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Softball?</h4>";
                echo "<input type='checkbox'  title='Check to play Softball' id='sb1' name='sb1'>";
                echo '</div>';
                echo '</div>'; // form grid
                echo '</div>'; // form grid div
              }
          
                if ($gotPartnerEventReg === 0) {
                    echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                    echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                    echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem2CHK' name='mem2Chk' checked>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Attend Meal?</h4>";
                echo "<input type='checkbox'  title='Check to attend meal' id='ddattm2' name='ddattm2' onclick='displayMealsB2()'>";
              

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
                  echo '</div>'; // form grid

               echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Cornhole?</h4>";
                echo "<input type='checkbox'  title='Check to play Cornhole' id='ch2' name='ch2'>";
                echo '</div>';
                 echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Play Softball?</h4>";
                echo "<input type='checkbox'  title='Check to play Softball' id='sb2' name='sb2'>";
                echo '</div>';

                 echo '</div>'; // form grid
                echo '</div>'; // form grid div
                 }
                    echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container 
                echo '</form>';
               }
               
 

             if (isset($_POST["$delChk"])) {
             
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                 echo  '<form method="POST" action="deleteEventRegt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';
       
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
          

                if ($_SESSION['role'] === 'visitor') {
                   if ($reg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                        $remID1 = "rem".$reg->id;
                        echo '<form-item>';
                      echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                  
                      echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                      echo '</div>';
                 
                   }
                  
                } else {
                    if ($reg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                          $remID1 = "rem".$reg->id;
                      echo '<form-item>';
                      echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
            
                      echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                      echo '</div>';
         
                    }
                     
                }
              echo '</div>';
              
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                 
                  if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                   $remID2 = "rem".$partnerReg->id;
               
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
              
                echo '</div>';
                 }
                }
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                  echo  '</form>';
                echo '</div>'; // end of form container
             } // end of delete check

            if (isset($_POST["$upChk"])) {
              $gotEventReg = 0;
              if ($_SESSION['role'] === 'visitor') {
                  if ($reg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                    $gotEventReg = 1;
                  }
              } else {
                  if ($reg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                       $gotEventReg = 1;
                  }
              }
        
        
            echo  '<form method="POST" action="updateBBQEventRegt.php">  ';
       
                $chID = "ch".$reg->id;
                $sbID = "sb".$reg->id;
                $updID = "upd".$reg->id;
                $dddinID = "dddin".$reg->id;
                echo '<input type="hidden" name="regID1" value='.$reg->id.'>';
                $gotPartnerEventReg = 0;
             if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if ($partnerReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                    $gotPartnerEventReg = 1;
                
         
                $chID2 = "ch2".$partnerReg->id;
                $sbID2 = "sb2".$partnerReg->id;
                $dddinID2 = "dddin2".$partnerReg->id;
                }
             }
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Modify BBQ Picnic Reservations for ".$event['eventname']." on ".$event['eventdate']."</h1>";
       
                if ($gotEventReg) {
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                 echo '<div class="form-item">';
                 if ($_SESSION['role'] === 'visitor') {
                    echo "<h4>".$_SESSION['visitorfirstname']."'s Information</h4>";
                 } else {
                   echo "<h4>".$_SESSION['userfirstname']."'s Information</h4>";
                 }

                echo '</div>';

                 echo '<div class="form-item">';
                 echo '<h4 class="form-item-title">Attend Dinner?</h4>';
          
                if ($reg->ddattenddinner === '1') {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked >";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' >";
                  }
                      
                echo '<div class="form-container" id="memMealChoiceU1">';
                echo '<div class="form-grid">';
         
                foreach ($mealChoices as $choice){
                  $mealChk = 'meal'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($reg->mealchoice === $choice['id']) {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$mealChk."' checked name='".$mealChk ."'>".$choice['mealname']."</h4>";
                  } else {
                      echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$mealChk."' name='".$mealChk ."'>".$choice['mealname']."</h4>";
                  }
                     
                  
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 } // for each mealchoice
            
                   echo '</div>'; // end form grid
               echo '</div>';
                echo '</div>'; // end of form item
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
         
                if ($reg->cornhole === '1') {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."'>";
                  }
               echo '</div>'; // end of form item
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
        
                if ($reg->softball === '1') {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."'>";
                }
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid
                echo '</div>'; // end of form grid div
              }
              // partner
              if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if ($gotPartnerEventReg !== 0) {

                 echo '<input type="hidden" name="regID2" value='.$partnerReg->id.'>';
                   echo '<div class="form-grid-div">';
                   echo '<div class="form-grid">';
          
                echo '<div class="form-item">';
                echo "<h4>".$_SESSION['partnerfirstname']."'s Information</h4>";
                echo '</div>';
               
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                if ($partnerReg->ddattenddinner === '1') {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID2."' name='".$dddinID2."' checked >";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID2."' name='".$dddinID2."' >";
                  }
             
                     echo '<div class="form-container " id="memMealChoiceU2">';
                  echo '<div class="form-grid">';
               foreach ($mealChoices as $choice){
                $meal2Chk = 'meal2'.$choice['id'];
                  echo '<div class="form-item">';
                  if ($partnerReg->mealchoice === $choice['id']) {
                     echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$meal2Chk."' checked name='".$meal2Chk ."'>".$choice['mealname']."</h4>";
                  } else {
                      echo "<h4 class='form-title-left'> <input type='checkbox'  title='Meal Choice' id='".$meal2Chk."' name='".$meal2Chk ."'>".$choice['mealname']."</h4>";
                  }                   
                 
                  echo "<p class='small-p'><em>".$choice['mealdescription']."</em></p>";
                  echo '</div>'; // end of form item         
                 
                 } // for each mealchoice
                  echo '</div>';
                    echo '</div>'; // end of form item
              echo '</div>';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                if ($partnerReg->cornhole === '1') {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID2."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID2."'>";
                  }
               echo '</div>'; // end of form item
                 echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
                if ($partnerReg->softball === '1') {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID2."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID2."'>";
                }
                echo '</div>'; // end of form grid
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid div
                    }
                  }
                echo '<button type="submit" name="submitUpdateBBQReg">Submit Updates</button>';
                echo '</div>'; // end of form container
                echo '</form>';
            }
     
        ?>