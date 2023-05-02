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

        <h1 class="section-header">Register for Events</h1>

        <?php
   
        if ($eventNumber > 0) {
        $partner = new User($db);

        if (isset($_SESSION['username'])) {
     
            echo '<h4> This process generates an email to confirm your registration, so it takes a while. Please be patient.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.</h4>';
            echo '<div class="form-grid3">';
            echo '<div class="form-grid-div">  <br>';
            echo '<form method="POST"  action="actions/regEvent.php" target="_blank">';

        if (isset($_SESSION['role'])) {
          if ($_SESSION['role'] === 'visitor') {
      
            if (isset($_SESSION['visitorfirstname'])) {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['visitorfirstname'].'"><br>';
            } else {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" ><br>';
             }
            if (isset($_SESSION['visitorlastname'])) {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['visitorlastname'].'"><br>';
            } else {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" ><br>';
            }
            if (isset($_SESSION['useremail'])) {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
            } else {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" ><br><br>';
            }



          } else {
            if (isset($_SESSION['userfirstname'])) {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'"><br>';
            } else {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" ><br>';
            }
            if (isset($_SESSION['userlastname'])) {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'"><br>';
            } else {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" ><br>';
            }
            if (isset($_SESSION['useremail'])) {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
            } else {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" ><br><br>';
            }
            if (isset($_SESSION['partnerid'])) {
                $partner->id = $_SESSION['partnerid'];
                $partner->read_single();
            
            }
       
        }
           
      }
    
      
         echo' </div>';
        echo '<div class="form-grid-div"> <br>';
     
        if (isset($_SESSION['partnerid'])) {
    
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'"><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" value="'.$partner->email.'"><br> <br>';

           } else {
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" ><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" ><br> <br>';
           }

        echo '</div>';  
        echo '</div>';    
        echo '<div class="form-grid">';
        echo '<div class="form-grid-div">';
            echo '<ul class="list-box">';
            echo '<h4><em>
              To Register -- Please select One or More of the Events Listed along with associated information. <br>Then click on the Submit Registration(s) Button.</em></h4><br>
              <p class="small-p">Please note if the event is a Dinner Dance, there will be a form (click on VIEW) to select meal choices and determine the cost. 
              This should be printed and sent to the treasurer along with payment.
              Their address will appear on the form. If no form exists yet for the event, you will receive an email with the form when it becomes available.
              </p><br>';
              echo '<table>';
              echo '<thead>';
              echo '<tr>';
              echo '<th>Check <br> to <br> Register</th>';
              echo '<th>Event</th>';
              echo '<th>Date</th>';
              echo '<th>Type</th>';
              echo '<th>Cost<br>Est</th>';
              echo '<th>If Dine and Dance<br>Attend Dinner?</th>';
              echo '<th>Form/Flyer</th>';
              echo '<th>Message</em></th>';
              echo '</tr>'; 
              echo '</thead>';
              echo '<tbody>';
        foreach ($upcomingEvents as $event) {
             
                echo '<tr>';
               echo '<td>';
               $chkboxID = "ev".$event['id'];
               echo "<input type='checkbox' name='$chkboxID'";
               echo '</td>';
               echo '<td>';
               echo $event['eventname'];
               echo '</td>';
               echo '<td>';
               echo $event['eventdate'];
               echo '</td>';
               echo '<td>';
               echo $event['eventtype'];
               echo '</td>';
               echo '<td>';
               echo $event['eventcost'];
               echo '</td>';
               if ($event['eventtype'] === 'Dine and Dance') {
                echo '<td>';
                $chkboxID2 = "dd".$event['id'];
                echo "<input type='checkbox' name='$chkboxID2'";
                echo ' ';
                echo '</td>';
               } else {
                echo '<td>';
                echo ' ';
                echo '</td>';
               }
               if ($event['eventform']) {
                echo '<td><a href="'.$event['eventform'].'">VIEW</a></td>';
            } else {
                    echo "<td> </td>"; 
            }
            $messID = "mess".$event['id'];
            echo '<td>';
            echo "<textarea name='$messID' cols='35' rows='1'></textarea><br><br>";
            echo '</td>';
          echo "</tr>";

        }
            echo '</tbody>';
            echo '</table>';

            echo '<button name="submitEventReg" type="submit">Submit Registration(s)</button><br>';
            echo '</div>';     
            echo '</form>';
    
        } 
      }
        ?>
    </section>
    </div>
    
</body>
</html>