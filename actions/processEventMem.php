<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/EventRegistration.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';

$upcomingEvents = $_SESSION['upcoming_events'];
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$reg = new EventRegistration($db);
$partnerReg = new EventRegistration($db);
$user = new User($db);
$mChoices = new DinnerMealChoices($db);
$mealChoices = [];
$mealChk = '';
$gotEventReg = 0;
$gotPartnerEventReg = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Process Events</title>
</head>
<body>
<nav class="nav">
        <div class="container">      
        <ul>  
            <li><a href="../index.php">Back to Home</a></li>
            <li><a href="../SBDCEventst.php">Back to Upcoming Events</a></li>
        </ul>
        </div>
</nav>
 <br><br><br>
<section id="processevents" class="content">
<div class="section-back">

<?php
       
          
foreach ($upcomingEvents as $event) {
    $upChk = "up".$event['id'];
    $rpChk = "rp".$event['id'];
    $delChk = "del".$event['id'];
    $regChk = "reg".$event['id'];
    $payChk = "pay".$event['id'];

    if ($event['id'] === $_POST['eventId']) {

        if (isset($_POST["$rpChk"])) {
            // unset($_POST["$rpChk"]);
            echo "<h4>Generated Report for  ".$event['eventname']."  ".$event['eventdate']."</h4>";
            echo "<form  name='reportEventForm'   method='POST' action='reportEvent.php'> ";
            echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
            echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
            unset($_POST["$rpChk"]);
            break;
            }
     switch ($event['eventtype']) {
        case "Dinner Dance":
           include_once 'processEventMemDinnerDance.php';
            
        break;

        case "BBQ Picnic": 
           include_once 'processEventMemBBQ.php';
        break;
   
        case "Dance Party": 
           include_once 'processEventMemDanceParty.php';
        break;   

    
        case "Meeting":
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
              
                echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                      
                          echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['visitorfirstname']."</h4>";
                 } else {
                      
                      echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                 }
        
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID1."' name='".$remID1."' checked>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
                } // got eventreg

                if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
              
                 if ($gotPartnerEventReg) {
                    $remID2 = "rem".$partnerReg->id;
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='".$remID2."' name='".$remID2."' checked>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
                 }
    
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo  '</form>';
                echo '</div>'; // end of form container
             } // end of delete check
            }
             if (isset($_POST["$regChk"])) {
              
                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Register for ".$event['eventname']." on ".$event['eventdate']."</h1>";
                echo  '<form method="POST" action="regEventt.php">  ';
                echo '<input type="hidden" name="eventid" value='.$event['id'].'>';

         
                if ($gotEventReg === 0) {
                   echo '<div class="form-grid-div">';
                  echo '<div class="form-grid">';
                  if ($_SESSION['role'] === 'visitor') {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['visitorfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['visitorlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['visitoremail'].'>';
                  } else {
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';
                  }
              
                echo '<div class="form-item">';
               if ($_SESSION['role'] === 'visitor') {
                 echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['visitorfirstname']." ".$_SESSION['visitorlastname']." ".$_SESSION['useremail']."</h4>";
               } else {
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
               }
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem1CHK' name='mem1Chk' checked>";
                echo '</div>';
               
        
                echo '</div>'; // form grid
                echo '</div>'; // form grid div
              }
                 if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                if (!$gotPartnerEventReg) {
                    echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                    echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                    echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";
                echo "<input type='checkbox'  title='Check add Reservation ' id='mem2CHK' name='mem2Chk' checked>";
                echo '</div>';

                 echo '</div>'; // form grid
                echo '</div>'; // form grid div
                 }
                }
                    echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container 
                echo '</form>';
               }
               
        break;

        default:
        break;

        } // end switch

    } // eventid matches
} // foreach upcoming event


?>
</div>
</section>
<footer >
    <?php
    require '../footer.php';
   ?>
</footer>
</body>
</html>
  <script>
        function displayMeals1() {

        // Select the element
        if (document.getElementById('ddattdin1').checked) {
        var element1 = document.getElementById('memMealChoice1');
        element1.classList.remove('hidden');

        }
        else {
            var element1 = document.getElementById('memMealChoice1');
            element1.classList.add('hidden');
            }
        }
        function displayMeals2() {

        // Select the element
        if (document.getElementById('ddattdin2').checked) {
        var element1 = document.getElementById('memMealChoice2');
        element1.classList.remove('hidden');
        }
        else {
            var element1 = document.getElementById('memMealChoice2');
            element1.classList.add('hidden');
            }
        }
    
      function togglePay1() {
    
        // Select the element
        if (document.getElementById('payonline').checked) {

             document.getElementById('paylater').checked = false;
        }
      }
      function togglePay2() {

         if (document.getElementById('paylater').checked) {
    
          document.getElementById('payonline').checked = false;
         }
  
       
        } 
     
   function displayguests() {


        // Select the element
        if (document.getElementById('addguests').checked) {
        var element1 = document.getElementById('displayguests');
        element1.classList.remove('hidden');
        var guestinfo = document.getElementById('guestinfo');
        // const formData = new FormData(formElement);

        // var numguests = document.forms['regEvent'].elements['numguests'].value;
        // console.log(numguests);
        // if (numguests > 0) {
        //  for (let step = 0; step < numguests; step++) {
        //      createInput(step, guestinfo);
        //   }
        //   }
        }
        else {
            var element1 = document.getElementById('displayguests');
            element1.classList.add('hidden');
            }
        }
  function displayG1Meals() {
       console.log('in displayg1')
        // Select the element
        if (document.getElementById('guest1dinner').checked) {
        var element1 = document.getElementById('guestMealChoice1');
        element1.classList.remove('hidden');

        }
        else {
            var element1 = document.getElementById('guestMealChoice1');
            element1.classList.add('hidden');
            }
        }
             
  function displayG2Meals() {
       console.log('in displayg2')
        // Select the element
        if (document.getElementById('guest2dinner').checked) {
        var element2 = document.getElementById('guestMealChoice2');
        element2.classList.remove('hidden');

        }
        else {
            var element2 = document.getElementById('guestMealChoice2');
            element2.classList.add('hidden');
            }
        }

  </script>
