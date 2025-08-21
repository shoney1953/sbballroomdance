<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/EventRegistration.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/User.php';
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$upcomingEvents = $_SESSION['upcoming_events'];
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnereventReg = new EventRegistration($db);
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$numActions = 0;
$gotEventReg = 0;
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

    <div class="container-section">
    <section id="events" class="content">
      <br><br>
      <h1>Upcoming Events</h1>
      <h4>You can click on the Event Name to get complete details on the event.</h4>
      

        <?php 
        if (!(isset($_SESSION['username']))) {
          echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login as a Member or Visitor to Register or Manage Event Registrations</a></h4>';
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
                echo "<h4 class='form-title-left' title='Click for complete event description'><a href='".$ed."'>".$event['eventtype'].": ".$event['eventname']." on ".$event['eventdate']."</a></h4>";
                if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {
                echo "<h5 class='form-title-left'> Last Day to sign up for dinner or modify dinner choices: ".date('Y-m-d', $eventCutOff).".  Registration ends: ".$event['eventregend'].".</h5>";
                } else {
                     echo "<h5 class='form-title-left'> Registration ends: ".$event['eventregend'].".</h5>";
                }
               
                echo '<div class="form-grid">';
          
                if ($event['eventform']) {
                   echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Form: <a href='".$event['eventform']."'>PRINT</a></h4>";
                echo '</div>'; // end of form item       
              } 
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
                  
                   if ($gotEventReg) {
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
                    }
                     if (isset($_SESSION['partnerid'])) {
                      if ($partnereventReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
                        if ($partnereventReg->mealchoice !== '0') {
                        echo '<div class="form-item">';
                        echo "<h4 class='form-item-title'>Your partner selected the meal: <br> ".$partnereventReg->mealname."</h4>";
                        echo '</div>'; // end of form item
                    }  // partner meal choice
                  }  // got partner reg
                } // partnerid
                    if ($event['eventcost'] !== '0') {
                      if ($eventReg->paid !== '0') {
                          echo '<div class="form-item">';
                          echo "<h4 class='form-item-title'>You have paid for this event.</h4>";
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
                    if (isset($_SESSION['partnerid'])) {

                      if ($partnereventReg->read_ByEventIdUser($event['id'],$_SESSION['partnerid'])) {
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
                      }
                    }
                  } // bbq
                  } // registration found
                      echo "</div>"; // end of form grid
      
                    
                } // logged in

 
              if (isset($_SESSION['username'])) {
                echo "<form name='processEventMem'   method='POST' action='actions/processEventMem.php'> "; 
                echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
                  echo '<div class="form-grid">';
                        $comparedateTS = strtotime($compareDate);
                        $eventRegOpen = strtotime($event['eventregopen']);
                     if ($comparedateTS >= $eventRegOpen) {
                      echo '<div class="form-item">';
                      echo '<h4 class="form-item-title">Report?</h4>';
                      echo "<input type='checkbox' title='Select to Report on Event' name='".$rpChk."'>";   
  
                      echo '</div>';
                      } // registration open 
 
                 $gotEventReg = 0;
                  if ($_SESSION['role'] === 'visitor') {
              
                   if ($eventReg->read_ByEventIdVisitor($event['id'],$_SESSION['username'])) {
                     $gotEventReg = 1;
                   } ;
                    
                  } else {
                    if ($eventReg->read_ByEventIdUser($event['id'],$_SESSION['userid'])) {
                       $gotEventReg = 1;
                    } ;
                  }
                     if ($gotEventReg) {
                          $eventCutOff = strtotime($event['eventdate'].'-7 days');
                          $comparedateTS = strtotime($compareDate);
                          $eventRegEnd = strtotime($event['eventregend']);
                           if ((($event['eventtype'] === 'Dance Party') && ($eventReg->ddattenddinner === '1')) || 
                                ($event['eventtype'] === 'Dinner Dance'))  {
                               if ($comparedateTS <= $eventCutOff) {                            
                                echo '<div class="form-item">';
                                echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                                echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                                echo '</div>';
                                $numActions++;
                               }
                           } else {
                              if ($comparedateTS <= $eventCutOff) { 
                              echo '<div class="form-item">';
                              echo '<h4 class="form-item-title">Modify Registrations?</h4>';
                              echo "<input type='checkbox' title='Select to Modify Registrations(s)' name='".$upChk."'>";   
                              echo '</div>';
                                $numActions++;
                               }
          
                           }

                          if ($eventReg->paid !== '1') {
                               echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Remove Registrations</h4>';
                            echo "<input type='checkbox' title='Select to Remove Registrations(s)' name='".$delChk."'>";   
                            echo '</div>';
                              $numActions++;
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

                        }// registered
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