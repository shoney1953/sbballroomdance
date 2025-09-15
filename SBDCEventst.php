<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/EventRegistration.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/User.php';

$upcomingEvents = $_SESSION['upcoming_events'];
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnereventReg = new EventRegistration($db);
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$numActions = 0;
$gotEventReg = 0;
$gotPartnerEventReg = 0;
$hr = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Events Test Mode</title>
</head>
<body>
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
    </ul>
     </div>
</nav>  
   <?php
    if (isset($_GET['error'])) {
            echo '<div class="container-error">';
            echo '<h4 class="error"> Registration ERROR:  '.$_GET['error'].'. Please Reenter Data</h4>';
            echo '</div>';
            unset($_GET['error']);
        } else {
            $_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
            unset($_GET['error']);
        }
   ?>
    <div class="container-section">
    <section id="events" class="content">
      <br><br>
      <h1>Upcoming Events</h1>
      <h4>You can click on the Event Name to get complete details on the event.</h4>
      
        <?php 
       
   
        if (isset($_SESSION['username'])) {
              echo '<h4>If you do not see the action you need perform on the event, please contact the event coordinator.</h4>';
          }
        if (!(isset($_SESSION['username']))) {
          echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Click Here Login as a Member or Visitor to Register or Manage Event Registrations</a></h4>';
        }
                 
               
          foreach ($upcomingEvents as $event) {
           
                $upChk = "up".$event['id'];
                $rpChk = "rp".$event['id'];
                $delChk = "del".$event['id'];
                $regChk = "reg".$event['id'];
                $payChk = "pay".$event['id'];
                 $ed = 'eventDesc.php?id=';
                 $ed .= $event["id"];
                 $numActions = 0;
          
                $eventCutOff = strtotime($event['eventdate'].'-7 days');

                $comparedateTS = strtotime($compareDate);
                $eventRegEnd = strtotime($event['eventregend']);
                echo '<div class="form-container">';
                   $hr = 'eventMem.php?id=';
                   $hr .= $event["id"];

                  if ($event['eventform']) {          
                    echo "<h4 class='form-title-left' title='Click for complete event description'><a href='".$ed."'>".$event['eventtype'].": ".$event['eventname']." on ".$event['eventdate']."</a>
                            -----> <a href='".$event['eventform']."'><em>Click to PRINT EVENT FORM</em></h4>";
                    } else {
                       echo "<h4 class='form-title-left' title='Click for complete event description'><a href='".$ed."'>".$event['eventtype'].": ".$event['eventname']." on ".$event['eventdate']."</a>
                      </h4>";
                    }
                    echo "<h5 class='form-title-left'> <a href='".$hr."'> Number Registered: ".$event['eventnumregistered']."</a></h5>";
                  if (isset($_SESSION['username'])) {
                      $comparedateTS = strtotime($compareDate);
                        $eventRegOpen = strtotime($event['eventregopen']);
                    if ($comparedateTS >= $eventRegOpen) {
                    echo "<p><form  target='_blank' name='reportEventForm'   method='POST' action='actions/reportEvent.php'> ";
                    echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
                    echo "<button class='button-tiny' type='submit'>Report</button></p>";
                    }
                    echo '</form>';
               
                    }
            
                if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {
                    echo "<h5 class='form-title-left'> Last Day to sign up for dinner or modify dinner choices: ".date('Y-m-d', $eventCutOff).".  Registration ends: ".$event['eventregend'].".</h5>";
                } else {
                     echo "<h5 class='form-title-left'> Registration ends: ".$event['eventregend'].". </h5>";
                    
                }
                echo '<div class="form-grid">';
          

               $gotEventReg = 0;
                if (isset($_SESSION['username'])) {
                  if ($_SESSION['role'] === 'visitor') {
                   if ($eventReg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                     $gotEventReg = 1;
                   } ;
    
                  } else {
                    if ($eventReg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                       $gotEventReg = 1;
                    } ;
                  }
                  $gotPartnerEventReg = 0;
                   if (isset($_SESSION['partnerid']) && ($_SESSION['partnerid'] != 0)) {
                      if ($partnereventReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {  
                        $gotPartnerEventReg = 1;

                      }
                    }

                   if ($gotEventReg)  {
        
                    echo '<div class="form-item">';
                    echo "<h4 class='form-item-title'>You registered for this event on: <br> ".substr($eventReg->dateregistered,0,10)."</h4>";
                    echo '</div>'; // end of form item
                    if ($event['eventtype'] === 'Dance Party') {
        
                      if ($eventReg->ddattenddinner === '1') {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen to attend dinner. </h4>";
                        echo '</div>'; // end of form item
                        
                      } else {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen not to attend dinner. </h4>";
                        echo '</div>'; // end of form item
                      }
                    }
                    if ($eventReg->mealchoice !== '0') {
                 
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You selected the meal: <br> ".$eventReg->mealname."</h4>";
                        echo '</div>'; // end of form item                   
                    } // meal choice
                     if ($event['eventcost'] !== '0') {
                      if ($eventReg->paid !== '0') {
                          echo '<div class="form-item">';
                          if ($eventReg->paidonline === '1') {
                              echo "<h4 class='form-item-title'>You have paid online for this event.</h4>"; 
                          } else {
                             echo "<h4 class='form-item-title'>You have paid for this event.</h4>";
                          }
                 
                          echo '</div>'; // end of form item
                      } else {
                          echo '<div class="form-item">';
                          echo "<h4 class='form-item-title'>You have not paid for this event.</h4>";
                          echo '</div>'; // end of form item
                      
                       } // event paid
                      } // eventcost
                        if ($event['eventtype'] === 'BBQ Picnic') {
             
                     if ($eventReg->ddattenddinner === '1') {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen to attend lunch. </h4>";
                        echo '</div>'; // end of form item
                      } else {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen not to attend lunch. </h4>";
                        echo '</div>'; // end of form item
                      } // attenddinner
              
                    if ($eventReg->cornhole === '1') {
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen to play cornhole. </h4>";
                        echo '</div>'; // end of form item
                    }
                      if ($eventReg->softball === '1') {
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>You have chosen to play softball. </h4>";
                        echo '</div>'; // end of form item
                    }
                  } // bbq picnic
                  }  // end got eventreg
                    // only partner registered
                    if ($gotPartnerEventReg) {
   
                      echo '<div class="form-item">';
                      echo "<h4 class='form-item-title'>Your partner registered for this event on: <br> ".substr($eventReg->dateregistered,0,10)."</h4>";
                      echo '</div>'; // end of form item
                      if ($event['eventtype'] === 'Dance Party') {
                    
                        if ($partnereventReg->ddattenddinner === '1') {
                          echo '<div class="form-item">';
                          echo "<h4 class='form-item-title'>Your partner has chosen to attend dinner. </h4>";
                          echo '</div>'; // end of form item
                        } else {
                          echo '<div class="form-item">';
                          echo "<h4 class='form-item-title'>Your partner has chosen not to attend dinner. </h4>";
                          echo '</div>'; // end of form item
                        }
                      }
                         if ($partnereventReg->mealchoice !== '0') {
                          echo '<div class="form-item">';
                          echo "<h4 class='form-item-title'>Your partner selected the meal: <br> ".$partnereventReg->mealname."</h4>";
                          echo '</div>'; // end of form item
                         }  // partner meal choice
                      if ($event['eventcost'] !== '0') {
                        if ($partnereventReg->paid !== '0') {
                            echo '<div class="form-item">';
                            if ($partnereventReg->paidonline === '1') {
                                echo "<h4 class='form-item-title'>Your partner has paid online for this event.</h4>";
                            } else {
                               echo "<h4 class='form-item-title'>Your partner has paid for this event.</h4>";
                            }
                  
                            echo '</div>'; // end of form item
                        } else {
                            echo '<div class="form-item">';
                            echo "<h4 class='form-item-title'>Your partner has not paid for this event.</h4>";
                            echo '</div>'; // end of form item
                        
                        } // event paid
                       } // eventcost

                        if ($event['eventtype'] === 'BBQ Picnic') {
                        if ($partnereventReg->ddattenddinner === '1') {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Your partner has chosen to attend lunch. </h4>";
                        echo '</div>'; // end of form item
                      } else {
                         echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Your partner has chosen not to attend lunch. </h4>";
                        echo '</div>'; // end of form item
                      } // attenddinner
              
                      if ($partnereventReg->cornhole === '1') {
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Your partner has chosen to play cornhole. </h4>";
                        echo '</div>'; // end of form item
                      }
                      if ($partnereventReg->softball === '1') {
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Your partner has chosen to play softball. </h4>";
                        echo '</div>'; // end of form item
                        }
                      } // got partner softball
                    } // got partner
                  
     
                   echo "</div>"; // end of form grid
      
                echo "<form name='processEventMem'   method='POST' action='actions/processEventMem.php'> "; 
                echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
                  echo '<div class="form-grid">';
                        $comparedateTS = strtotime($compareDate);
                        $eventRegOpen = strtotime($event['eventregopen']);
                    
 
                     if (($gotEventReg) || ($gotPartnerEventReg)) {  
                          $eventCutOff = strtotime($event['eventdate'].'-7 days');
                          $comparedateTS = strtotime($compareDate);
                          $eventRegEnd = strtotime($event['eventregend']);
             
                          if ($comparedateTS <= $eventCutOff) { 
                           if (($event['eventtype'] === 'Dance Party') || ($event['eventtype'] === 'Dinner Dance'))  {
                              if ($event['eventtype'] === 'Dance Party') {
                                if ((($gotEventReg) && ($eventReg->ddattenddinner === '1')) ||
                                   (($gotPartnerEventReg) && ($partnereventReg->ddattenddinner === '1')))
                                   {
                                    echo '<div class="form-item">';
                                    echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                                    echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                                    echo '</div>';
                                    $numActions++;
                                }
                                if ($event['eventtype'] === 'Dinner Dance') {
                                    echo '<div class="form-item">';
                                    echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                                    echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                                    echo '</div>';
                                    $numActions++;
                                }

                              } else {

                                  echo '<div class="form-item">';
                                  echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                                  echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                                  echo '</div>';
                                  $numActions++;
 
                              }
  
                           } else {
                                if ($event['eventtype'] !== 'Meeting') {
                              echo '<div class="form-item">';
                              echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                              echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                              echo '</div>';
                                $numActions++;
                                }
                               }
                           } // end if cutoff

                          if ($gotEventReg) {
                            if ($eventReg->paid !== '1') {
                               echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Remove Registrations</h4>';
                            echo "<input type='checkbox' title='Select to Remove Registrations(s)' name='".$delChk."'>";   
                            echo '</div>';
                              $numActions++;
                            } 
                           } else {
                              if ($gotPartnerEventReg) {
                              if ($partnereventReg->paid !== '1') {
                                    echo '<div class="form-item">';
                                  echo '<h4 class="form-item-title">Remove Registrations</h4>';
                                  echo "<input type='checkbox' title='Select to Remove Registrations(s)' name='".$delChk."'>";   
                                  echo '</div>';
                                  $numActions++;
                                } // partner not paid
                              }  // got partner
                            } // end of else goteventreg
                          // }
                            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0') && (!($gotPartnerEventReg))) {
                              $comparedateTS = strtotime($compareDate);
                              $eventRegOpen = strtotime($event['eventregopen']);
                                 if ($comparedateTS >= $eventRegOpen) {
                                  echo '<div class="form-item">';
                                  echo '<h4 class="form-item-title">Register?</h4>';
                                  echo "<input type='checkbox' title='Select register for this event' name='".$regChk."'>";   
                                  echo '</div>';
                                    $numActions++;
                            }
                           }
                            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0') && ($gotPartnerEventReg)) {
                              if (!($gotEventReg)) {
                              $comparedateTS = strtotime($compareDate);
                              $eventRegOpen = strtotime($event['eventregopen']);
                                if ($comparedateTS >= $eventRegOpen) {
                                echo '<div class="form-item">';
                                echo '<h4 class="form-item-title">Register?</h4>';
                                echo "<input type='checkbox' title='Select register for this event' name='".$regChk."'>";   
                                echo '</div>';
                                  $numActions++;
                              }

                            }
                           }
                            //  else below goes to registered
                          }  else {
                    
                          $comparedateTS = strtotime($compareDate);
                          $eventRegOpen = strtotime($event['eventregopen']);
                          if ($comparedateTS >= $eventRegOpen) {
                            echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Register?</h4>';
                            echo "<input type='checkbox' title='Select register for this event' name='".$regChk."'>";   
                            echo '</div>';
                              $numActions++;
                            
                          } else {
                            echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Registration  is not Open for this event yet</h4>';
                            echo '</div>';
                          }

              
                      
                      }  // registered
                        if ($numActions > 0) {
                              echo '<div class="form-item">';
                               echo "<button type='submit' name='subMemEvent'>Process</button>";
                               echo '</div>';
                        }
                    
                        // echo '</div>';

  
                        // echo '</div>';
                      }
                       echo '</div>';                     
                       echo '</form>';
                
         
              echo "</div>"; // end of form container
                
            } // end of foreach
 
        ?>

    <br>
    </section>
      </div>
</div>
<?php
  include 'footer.php';
?>
</body>
</html>