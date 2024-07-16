<?php
  session_start();
  require_once 'config/Database.php';
  require_once 'models/Event.php';
  require_once 'models/User.php';
  $events =  $_SESSION['upcoming_events'] ;
  $upcomingEvents = $_SESSION['upcoming_events'];
  $eventNumber = $_SESSION['upcoming_eventnumber'];
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Register for Events</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <br><br>
    <div class="container-section ">
    <section id="registerevent" class="content">  

        <h1 class="section-header">Register for Upcoming Events</h1>

        <?php
   
        if ($eventNumber > 0) {
        $partner = new User($db);

        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
     
            
            
            echo '<h4 class="form-title"> This process generates an email to confirm your registration, so it takes a while. Please be patient.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so that the automatic emails do not end up in the 
            spam/junk folder.</h4>';
            echo '<form method="POST"  action="actions/regEvent.php" target="_blank">';
            
            

        if (isset($_SESSION['role'])) {
          if ($_SESSION['role'] === 'visitor') {
            echo '<div class="form-container">';
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
            echo '</div>';
          } //end visitor 

          if ($_SESSION['role'] != 'visitor') {

            if (isset($_SESSION['partnerid'])) {
              $partner->id = $_SESSION['partnerid'];
              $partner->read_single();
             }
            echo '<div class="form-container">'; 
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
           echo '</div>';
           if (isset($_SESSION['partnerid'])) {
            if ($_SESSION['partnerid'] > 0) {
    
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
         echo '</div>';
          }

            
            echo '<h4 class="form-title"><em>
              To Register -- Please select One or More of the Events Listed along with associated information. <br>Then click on the Submit Registration(s) Button.</em></h4><br>
              <p class="small-p">Please note if the event is a Dinner Dance or a Dance Party, there will be a form (click on VIEW) to select meal choices and determine the cost. 
              This should be printed and sent to the treasurer along with payment.
              Their address will appear on the form. If no form exists yet for the event, you will receive an email with the form when it becomes available.
              </p><br>';
            
        foreach ($upcomingEvents as $event) {
   
           if (($compareDate <= $event['eventregend']) &&
             ($compareDate >= $event['eventregopen']))
            {
             echo '<div class="form-container">'; 
             echo '<div class="form-grid">';

             echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Select?</h4>';
               $chkboxID = "ev".$event['id'];
               echo "<input type='checkbox' name='$chkboxID'>";
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
               echo '<h4 class="form-item-title">Registration Ends</h4>';
               echo $event['eventregend'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Event Type</h4>';
               echo $event['eventtype'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Event Cost</h4>';
               echo $event['eventcost'];
               echo '</div>';
 
               if ($event['eventtype'] === 'Dine and Dance') {

                $chkboxID2 = "dd".$event['id'];
        
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                echo "<input type='checkbox' name='$chkboxID2'>";
                echo '</div> ';

                  } 
                if ($event['eventtype'] === 'Dance Party') {

                  $chkboxID2 = "dd".$event['id'];
          
                  echo '<div class="form-item">';
                  echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                  echo "<input type='checkbox' name='$chkboxID2'>";
                  echo '</div> ';
  
                    } 
               if ($event['eventform']) {
    
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Event Form or Flyer</h4>';
                echo '<a href="'.$event['eventform'].'">VIEW</a>';
                echo '</div>';
                } 
     
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Event Message</h4>';
            $messID = "mess".$event['id'];
            echo "<textarea name='$messID' cols='35' rows='1'></textarea>";
            // echo '</div>';
            echo '</div>';
            echo '</div>'; 
            echo '</div>'; 
              }
        }
           

            echo '<button name="submitEventReg" type="submit">Submit Registration(s)</button><br>';
    
            echo '</form>';

            echo '</div>'; 
            echo '</div>'; 
        } 
      }
    }
        ?>
    </section>
    </div>
    
</body>
</html>