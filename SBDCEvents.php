<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/EventRegistration.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/User.php';
$upcomingEvents = $_SESSION['upcoming_events'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Events</title>
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

      <br>
        <h1 class="section-header">List of Upcoming Events</h1>
        <h4>To Register for events, click the Register for Events Button Below. You must be logged in as a Visitor or Member to register.</h4>
     
        <div class="form-grid2">
        <?php

         if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
            if (isset($_SESSION['role'])) {
        if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
            echo '<div class="form-grid-div">';
              echo "<button><a href='regForEventsOnline.php'><h4>Click to Register and Pay for Events Online</h4></a></button> ";
              echo '</div>';
        }
    
        echo '<div class="form-grid-div">';
        echo "<button><a href='regForEvents.php'><h4>Click to Register for Events</h4></a></button> ";
        echo '</div>';

      
            }
        } else {
            echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login as a Member or Visitor to Register</a></h4>';
        }
        ?>
       
        </div> <!-- end of form-grid2 -->
        
        <table>
            <thead>
            <?php
            $first_value = reset($upcomingEvents); // First element's value
         
            $first_event_year = substr($first_value['eventdate'], 0, 4);

            echo '<tr>';
            echo '<th colspan="13"><em>'.$first_event_year.'</em></th>';
            echo '</tr>';
   
            echo '<tr>';
     
                if (isset($_SESSION['username'])) {
                    echo '<th>Report?</th>';
                }
            ?>
                <th>Name (Click for Details)</th>
                <th>Description</th> 
                <th>Event<br>Dates</th>
                <th>Reg<br>Opens</th>
                <th>Reg<br>Closes</th>
                <th>Form</th>
                <th>Type    </th>
                <th>Room</th> 
                <th>DJ</th>            
                <th>Min<br>Cost</th>
                <th>Min<br>Guest Cost</th>
                <th># <br>Reg</th>

             </tr>

            </thead>
         
            <?php 
            $eventNumber = 0;
            foreach ($upcomingEvents as $event) {
                 $eventNumber++;
                 $event_year = substr($event['eventdate'], 0, 4);
             
                 if ($event_year > $first_event_year) {
                    echo '</tbody>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th  colspan="13"  ><em>'.$event_year.'</em></th>';
                    echo '</tr>';
                    echo '<tr>'; 
         
                    $first_event_year = $event_year;
                    echo '<tr>';
                    if (isset($_SESSION['username'])) {
                        echo '<th>Report?</th>';
                    }
                    echo '<th>Name (Click for Details) </th>';
                    echo '<th>Description</th>'; 
                    echo '<th>Event<br>Date</th>';
                    echo '<th>Reg<br>Opens</th>';
                    echo '<th>Dance Only Reg<br>Closes</th>';
                    echo '<th>Form</th>';
                    echo '<th>Name    </th>';
                    echo '<th>Type    </th>';

                    echo '<th>Room</th> ';
                    echo '<th>DJ</th>';            
                    echo '<th>Min<br>Cost</th>';
                    echo '<th>Min<br>Guest Cost</th>';
                    echo '<th># Reg</th>';
    
                echo '</tr>';
              
            echo '</thead>' ;
                echo '<tbody>';
                 }

        
                 $hr = 'eventMem.php?id=';
                 $hr .= $event["id"];
                 $ed = 'eventDesc.php?id=';
                 $ed .= $event["id"];
                  echo "<tr>";
                    if (isset($_SESSION['username'])) {
                    echo "<td>";
                    echo "<form  target='_blank' name='reportEventForm'   method='POST' action='actions/reportEvent.php'> ";
                    echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
                    echo "<button class='button-small' type='submit'>&#10004;</button>";
                    // echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
                    echo '</form>';
                    echo "</td>";
                    }
                    // echo "<td>".$event['eventname']."</td>";
                    echo '<td><a href="'.$ed.'">'.$event["eventname"].'</a></td>';
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".substr($event['eventdate'],5,5)."</td>";
                    echo "<td>".substr($event['eventregopen'],5,5)."</td>";
                    echo "<td>".substr($event['eventregend'],5,5)."</td>";
                    if ($event['eventform']) {
                        echo '<td><a href="'.$event['eventform'].'">PRINT</a></td>';
                    } else {
                            echo "<td> </td>"; 
                    }
                    echo "<td>".$event['eventtype']."</td>";

                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventdj']."</td>";            
  
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventguestcost']."</td>";
                    echo '<td><a href="'.$hr.'">'.$event["eventnumregistered"].'</a></td>';
                    // echo "<td>".$event['eventnumregistered']."</td>";
              
                  echo "</tr>";
            }
            $_SESSION['upcoming_eventnumber'] = $eventNumber;
            ?> 
            </tbody>
        </table>
        <br>

    </section>
    </div>
    </div>

   

<?php
  include 'footer.php';
?>
</body>
</html>