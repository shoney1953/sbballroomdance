<?php
session_start();
require_once 'config/Database.php';

$allEvents = $_SESSION['allEvents'];
$eventRegistrations = $_SESSION['eventRegistrations'];

$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$database = new Database();
$db = $database->connect();

$mealChoices = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Admin Event</title>
</head>
<body>
<nav class="nav">
    <div class="container">
        
     <ul> 
    <li><a href="administration.php">Back to Admin</a></li>
     </ul>
    </div>
</nav>

<?php
if (isset($_GET['id'])) {
echo '<div class="container-section ">';
    echo '<section id="events" class="content">';
    echo '<br><br><h1>Selected Events</h1>';

        echo '<table>';
            echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Date</th>';
                echo '<th>Name    </th>';
                echo '<th>Type    </th>';
                echo '<th>Description</th>';
                echo '<th>DJ</th>';  
                echo '<th>ORG Email</th>';         
                echo '<th>Room</th>';
                echo '<th>Cost</th>';
                echo '<th># Attending</th>';
            echo '</tr>';
     
            $eventNumber = 0;
            foreach($allEvents as $event) {
                 if ($event["id"] === $_GET['id']) {
                  echo "<tr>";
               
                    echo '<td>'.$event["id"].'</td>';
                    echo "<td>".$event['eventdate']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".$event['eventdj']."</td>";  
                    echo "<td>".$event['orgemail']."</td>";          
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                  echo "</tr>";
                  echo '</table>';
                  echo '<br>';
             
            
        
              echo '<h3>Registrations</h3>';
                echo '<table>';
                    echo '<tr>';
                     
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                
                        if ($event['eventtype'] === 'Dinner Dance') {
                           echo '<th>Paid</th>';
                        }
                        if ($event['eventtype'] === 'Dance Party') {
                            echo '<th>Paid</th>';
                         }
                        if ($event['eventtype'] === 'Dine and Dance') {
                            echo '<th>Attend<br>Dinner?</th>';
                            echo '<th>Attend<br>Dance?</th>';
                        }
                        if ($event['eventtype'] === 'BBQ Picnic') {
                            echo '<th>Attend<br>Meal?</th>';
                            echo '<th>Attend<br>Cornhole?</th>';
                            echo '<th>Attend<br>Softball?</th>';
                        }
                        echo '<th>Message</th>';
                        echo '<th>Date Reg</th> '; 
                        echo '<th>Reg By</th> ';         
                    echo '</tr>';
                    
            
                    foreach($eventRegistrations as $eventRegistration) {
                  
                         if ($eventRegistration['eventid'] === $_GET['id']) {
                  
                          echo "<tr>";
                        
                            echo "<td>".$eventRegistration['firstname']."</td>";
                            echo "<td>".$eventRegistration['lastname']."</td>";
                            echo "<td>".$eventRegistration['email']."</td>"; 
                            if ($event['eventtype'] === 'Dinner Dance') {
                            if ($eventRegistration['paid'] == true ) {
                                echo "<td>&#10004;</td>"; 
                              } else {
                                  echo "<td>&times;</td>"; 
                              } 
                            }
                            if ($event['eventtype'] === 'Dance Party') {
                                if ($eventRegistration['paid'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                  } else {
                                      echo "<td>&times;</td>"; 
                                  } 
                                }
                                if ($eventRegistration['ddattenddinner'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                                if ($eventRegistration['ddattenddance'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                        
                            if ($event['eventtype'] === 'Dine and Dance') {
                                if ($eventRegistration['ddattenddinner'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                                if ($eventRegistration['ddattenddance'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                        
                            }
                            if ($event['eventtype'] === 'BBQ Picnic') {
                                if ($eventRegistration['ddattenddinner'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                                if ($eventRegistration['cornhole'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                                if ($eventRegistration['softball'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                }
                        
                            }
                            echo "<td>".$eventRegistration['message']."</td>";         
                            echo "<td>".$eventRegistration['dateregistered']."</td>";
                            echo "<td>".$eventRegistration['registeredby']."</td>";
                     
                          echo "</tr>";
                      }
                    }
                    
                echo '</table>';
                echo '<br><br>';
            }
        }
            echo '</section>'; 
            echo '</div>'; 
    }

 ?> 

     <footer >
    <?php
    require 'footer.php';
   ?>
</body>
</html>