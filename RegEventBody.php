 <section id="registerevent" class="content">  


      <?php
      if ($_SESSION['regtype'] == 'manual') {
         echo '<h1 class="section-header">Register for Upcoming Events</h1>';
      }

      if ($_SESSION['regtype'] == 'online') {
         echo '<h1 class="section-header">Register for Upcoming Events and Pay Online</h1>';
      }


    
        if (isset($_GET['error'])) {

          echo '<br><h4 class="error"> ERROR:  '.$_GET['error'].'</h4><br>';
          unset($_GET['error']);
        } else {
            $_SESSION['regeventurl'] = $_SERVER['REQUEST_URI'];
      }

        echo '<div class="form-container">';
        if ($eventNumber > 0) {
        $partner = new User($db);

        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
            if ($_SESSION['regtype'] === 'online') {
              echo '<h4 class="form-title"> Please select only 1 event. You will be sent to a confirmation page and then to a payment page. Once payment is
              successful, your registration will be recorded and a confirmation email will be sent to you.</h4><br>';
            }
          
            echo '<h4 class="form-title"> This process generates an email to confirm your registration, so it takes a while. Please be patient.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so that the automatic emails do not end up in the 
            spam/junk folder.</h4><br>';

        
          if ($_SESSION['role'] === 'visitor') {
            echo '<input type="hidden" name="regUserid1" value="0"';
            echo '<h4 class="form-title">Visitor Registration</h4>';
            echo '<div class="form-grid">';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">First Name</h4>';
            if (isset($_SESSION['visitorfirstname'])) {
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['visitorfirstname'].'">';
            } else {        
                    echo '<input type="text" name="regFirstName1" >';
            }
            echo '</div>';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Last Name</h4>';
            if (isset($_SESSION['visitorlastname'])) {
                echo '<input type="text" name="regLastName1" value="'.$_SESSION['visitorlastname'].'">';
            } else {
                    echo '<input type="text" name="regLastName1" >';
            }
            echo '</div>';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Email</h4>';
            if (isset($_SESSION['visitorlastname'])) {
                echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'">';
            } else {
                    echo '<input type="email" name="regEmail1" >';
            }
            echo '</div>';

            echo '</div>';
            // echo '</div>';
            echo '<input type="hidden" name="visitor" value="1">';
          } //end visitor 

          if ($_SESSION['role'] != 'visitor') {
              echo '<input type="hidden" name="visitor" value="0">';
            if (isset($_SESSION['partnerid'])) {
              $partner->id = $_SESSION['partnerid'];
              $partner->read_single();
             }
        
            echo '<h4 class="form-title">Member(s) Registration</h4>';
            echo '<div class="form-grid">';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">First Name</h4>';
            if (isset($_SESSION['userfirstname'])) {
              echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'">';
            } else {
                  echo '<input type="text" name="regFirstName1" >';  
            }
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Last Name</h4>';
            if (isset($_SESSION['userlastname'])) {
                echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'">';
            } else {
                    echo '<input type="text" name="regLastName1" >';
            }
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Email</h4>';
            if (isset($_SESSION['userlastname'])) {
                echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'">';
            } else {
                    echo '<input type="email" name="regEmail1" >';
            }
            echo "<input type='hidden' name='regUserid1' value='".$_SESSION['userid']."' </input>";;
           echo '</div>';
           if (isset($_SESSION['partnerid'])) {

            if ($_SESSION['partnerid'] > 0) {
               echo "<input type='hidden' name='regUserid2' value='".$_SESSION['partnerid']."' </input>";;
              echo '<div class="form-item">';
              echo '<h4 class="form-item-title">Partner First Name</h4>';
              echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'">';
              echo '</div>';

              echo '<div class="form-item">';
              echo '<h4 class="form-item-title">Partner Last Name</h4>';
              echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'">';
              echo '</div>';

              echo '<div class="form-item">';
              echo '<h4 class="form-item-title">Partner Email</h4>';
              echo '<input type="email" name="regEmail2" value="'.$partner->email.'">';
              echo '</div>';
           }
          }
         echo '</div>';
   
          }
            echo '</div>';
            if ($_SESSION['regtype'] == 'online ') {
               echo '<h4 class="form-title"><em>
              To Register -- Please select One of the Events Listed along with associated information. <br>Then click on the Submit Registration(s) Button.</em></h4><br>

              </p><br>';
            }
            if ($_SESSION['regtype'] == 'manual ') {
            echo '<h4 class="form-title"><em>
              To Register -- Please select One or More of the Events Listed along with associated information. <br>Then click on the Submit Registration(s) Button.</em></h4><br>

              <p>Please note if the event is a Dinner Dance or a Dance Party, there will be a form (click on PRINT) to select meal choices and determine the cost. 
              This should be printed and sent to the treasurer along with payment.
              Their address will appear on the form. If no form exists yet for the event, you will receive an email with the form when it becomes available.
              As of 2025, for Dance Parties, there will be a minimum charge of $5 per member or $10 per visitor to attend the dance portion only. Please submit this with the form prior to the date registration ends.
              </p><br>';
            }

            
        foreach ($upcomingEvents as $event) {
          if ($_SESSION['regtype'] )
           if (($compareDate <= $event['eventregend']) &&
             ($compareDate >= $event['eventregopen']))
            {
                  $evNameID = "evn".$event['id']; 
                  $evDateID = "evd".$event['id'];   
              echo "<input type='hidden' name='".$evNameID."' value='".$event['eventname']."' </input>";
              echo "<input type='hidden' name='".$evDateID."' value='".$event['eventdate']."' </input>";
             echo '<div class="form-container">'; 
             echo '<div class="form-grid">';

              echo '<div class="form-item">';
              echo '<h4 class="form-item-title-emp">Click to Register</h4>';
               $chkboxID = "ev".$event['id'];    
               $evCostID = "ec".$event['id'];
               $evTypeID = "et".$event['id'];
               $eventID = "ei".$event['id'];
                if ($_SESSION['role'] === 'visitor') {
                        echo "<input type='hidden' name='".$evCostID."' value='".$event['eventguestcost']."'>";
                } else {
                      echo "<input type='hidden' name='".$evCostID."' value='".$event['eventcost']."'>";
                }

               echo "<input type='hidden' name='".$evTypeID."' value='".$event['eventtype']."'>";
                echo "<input type='hidden' name='".$eventID."' value='".$event['id']."'>";
               if ($event['eventtype'] === 'Dinner Dance') {   
               echo "<input type='checkbox' id='".$chkboxID."' name='".$chkboxID."' onclick='displayMeals2(".$event['id'].")'>";
                  }
              else {
                echo "<input type='checkbox' id='".$chkboxID."'name='$chkboxID'>";
              }
              echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Event Name</h4>';
               echo $event['eventname'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Event Date</h4>';
               echo $event['eventdate'];
               echo '</div>';
               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Registration Opens</h4>';
               echo $event['eventregopen'];
               echo '</div>';
               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Dance Only Registration Ends</h4>';
               echo $event['eventregend'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Event Type</h4>';
               echo $event['eventtype'];
               echo '</div>';

               echo '<div class="form-item">';
               if ($event['eventtype'] === 'Dinner Dance') {   
                 echo '<h4 class="form-item-title">Event Minimum Cost</h4>';
               } else if ($event['eventtype'] === 'Dance Party') {
                 echo '<h4 class="form-item-title">Event Dance Only Cost</h4>';
               } else {
                  echo '<h4 class="form-item-title">Event Cost</h4>';
               }

                if ($_SESSION['role'] === 'visitor') {
                 
                    echo "$".number_format($event['eventguestcost'], 2);
                 } else {
                    echo "$".number_format($event['eventcost'], 2);
                 }
            
               echo '</div>';
 

                if ($event['eventtype'] === 'BBQ Picnic') {

                      $chkboxID2 = "dd".$event['id'];
              
                      echo '<div class="form-item">';
                      echo '<h4 class="form-item-title">Attend Meal?</h4>';
                      echo "<input type='checkbox' name='$chkboxID2'>";
                      echo '</div> ';
                      $chkboxID3 = "ch".$event['id'];
              
                      echo '<div class="form-item">';
                      echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                      echo "<input type='checkbox' name='$chkboxID3'>";
                      echo '</div> ';
                      $chkboxID4 = "sb".$event['id'];
              
                      echo '<div class="form-item">';
                      echo '<h4 class="form-item-title">Play Softball?</h4>';
                      echo "<input type='checkbox' name='$chkboxID4'>";
                      echo '</div> ';
                  } 
                if ($event['eventtype'] === 'Dance Party') {

                  $chkboxID2 = "dd".$event['id'];
                  echo '<div class="form-item">';
                  echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                  echo "<input type='checkbox' id='$chkboxID2' name='$chkboxID2' onclick='displayMeals(".$event['id'].")'>";
                  echo '</div> ';
                } 

             
            if (($event['eventtype'] === 'Dance Party') || ($event['eventtype'] === 'Dinner Dance')) {
              $mealChoices = [];
              $mChoice->eventid = $event['id'];
              $result = $mChoice->read_ByEventId($event['id']);

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
                    }
                   
               
                    if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) { 
                      $mcfcD = 'mcfcD'.$event['id'];
                      $mcfcP = 'mcfcP'.$event['id'];
                      if ($event['eventtype'] === 'Dinner Dance') {
                         echo "<div id='".$mcfcD."' class='form-container hidden'>";   
                      } else {
                         echo "<div id='".$mcfcP."' class='form-container hidden'>"; 
                      }

                      if ($_SESSION['role'] === 'visitor') {
                            echo '<h4 class="form-item-title">Meal Selection</h4>';
                      } else {
                          echo "<h4 class='form-item-title'>Selection for ".$_SESSION['userfirstname']."</h4>";
                      }

                
                    foreach ($mealChoices as $choice) {
                      $price = '';
                   
                        if ($_SESSION['role'] === 'visitor') {
                          $price = number_format($choice['guestprice']/100,2);
                        }
                        else {
                          $price = number_format($choice['memberprice']/100,2);                   
                        }

                       echo '<div class="form-item">';
                       $smealCHK1 = 'sm1'.$choice['id'];
 
                          echo '<h4 id="mem1mealchoice1" class="form-item-title-bold">'.$choice['mealname'].' @ '.$price.'</h4>';
                             echo "<input type='checkbox' id='".$smealCHK1."' name='".$smealCHK1."'  title='Check to select this meal option for Member 1'>";
                          echo "<h5 class='form-item-title'><em>".$choice['mealdescription']."</em></h5>";

                    
               
                       echo '</div>';
                    }
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title-bold">Dietary Restriction</h4>';
                    $drID1 = "dr1".$event['id'];
       
                    echo "<textarea name='$drID1' cols='10' rows='1'>".$_SESSION['dietaryrestriction']."</textarea>";
                    echo '</div>';
                    echo '</div>';
                    if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] > 0)) {               
                            $pcfcd = 'pcfcD'.$event['id'];
                            $pcfcp = 'pcfcP'.$event['id'];
                            if ($event['eventtype'] === 'Dinner Dance') {
                                echo "<div  id='".$pcfcd."'  class='form-container hidden'>"; 
                            } else {
                                echo "<div  id='".$pcfcp."'  class='form-container hidden'>"; 
                            }
                              echo "<h4 class='form-item-title'>Selection for ".$partner->firstname."</h4>";
                       
                         foreach ($mealChoices as $choice) {
                      
                              $price = number_format($choice['memberprice']/100,2);
                             
                           echo '<div class="form-item">';
                              $smealCHK2 = 'sm2'.$choice['id'];
                            echo '<h4 class="form-item-title-bold">'.$choice['mealname'].' @ '.$price.'</h4>';
                            echo "<input type='checkbox' id='".$smealCHK2."' name='".$smealCHK2."'  title='Check to select this meal option for partner'>";
                            echo "<h5 class='form-item-title'><em>".$choice['mealdescription']."</em></h5>";

                            echo '</div>';
                            
                          
                    }        
                            echo '<div class="form-item">';
                            echo '<h4 class="form-item-title-bold">Dietary Restriction</h4>';
                            $drID2 = "dr2".$event['id'];
                            echo "<textarea name='$drID2' cols='10' rows='1'>".$_SESSION['partnerdietaryrestriction']."</textarea>";
                            echo '</div>';            

                       echo '</div>'; 
                          
                  }
                
                   

                  } //test mode
                 
                }
                 
            }
            if ((isset($_SESSION['regtype'])) && ($_SESSION['regtype'] === 'manual')) {
            if ($event['eventform']) {
    
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Event Form</h4>';
                echo '<a href="'.$event['eventform'].'">PRINT</a>';
                echo '</div>';
            } 
          }
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Message to Event Coordinator</h4>';
            $messID = "mess".$event['id'];
            echo "<textarea name='$messID' cols='35' rows='1'></textarea>";
       
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4><button name="submitEventReg" type="submit">Submit Registration(s)</button></h4>';
            echo '</div>'; 
            echo '</div>'; 
             echo '</div>'; 
          //  echo '</div>'; 
              }

        } // for each event
                

            echo '</form>';

            // echo '</div>'; 
            // echo '</div>'; 
            //  echo '</div>'; 
        } 
      }
    
        ?>
                  <script>
                  function displayMeals(eventid) {
   
                  membermealchoice = 'mcfcP'+eventid; 
                  partnermealchoice = 'pcfcP'+eventid; 
                  var cboxname = 'dd'+eventid;
              
                  // Select the element
                  if (document.getElementById(cboxname).checked) {

                    var element1 = document.getElementById(membermealchoice);
                    element1.classList.remove('hidden');
                    element2 = document.getElementById(partnermealchoice);
          
                    element2.classList.remove('hidden');
                    }
                    else {
                      var element1 = document.getElementById(membermealchoice);
                      element1.classList.add('hidden');
                      element2 = document.getElementById(partnermealchoice);
    
                      element2.classList.add('hidden');
                      }
                  }

                  function displayMeals2(eventid) {
                  membermealchoice = 'mcfcD'+eventid; 
                  partnermealchoice = 'pcfcD'+eventid; 
                  var cboxname = 'ev'+eventid;
              
                  // Select the element
                  if (document.getElementById(cboxname).checked) {

                    var element1 = document.getElementById(membermealchoice);
                    element1.classList.remove('hidden');
                    element2 = document.getElementById(partnermealchoice);
          
                    element2.classList.remove('hidden');
                    }
                    else {
                      var element1 = document.getElementById(membermealchoice);
                      element1.classList.add('hidden');
                      element2 = document.getElementById(partnermealchoice);
    
                      element2.classList.add('hidden');
                      }
                  }

                  </script>
    </section>