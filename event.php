<?php
session_start();

$allEvents = $_SESSION['allEvents'];
$eventRegistrations = $_SESSION['eventRegistrations'];
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
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
                echo '<th>Event Date</th>';
                echo '<th>Event Name    </th>';
                echo '<th>Event Type    </th>';
                echo '<th>Event Description</th>';
                echo '<th>Event DJ</th>';         
                echo '<th>Event Room</th>';
                echo '<th>Event Cost</th>';
                echo '<th># Reg </th>';
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
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                  echo "</tr>";
              }
         
        

            }
            echo '</table>';
            echo '<br>';

               
                echo '<table>';
                    echo '<tr>';
                     
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                        echo '<th>Paid</th>';
                        echo '<th>Message</th>';
                        echo '<th>Date Reg</th> ';         
                    echo '</tr>';
                    
            
                    foreach($eventRegistrations as $eventRegistration) {
                  
                         if ($eventRegistration['eventid'] === $_GET['id']) {
                          echo "<tr>";
                        
                            echo "<td>".$eventRegistration['firstname']."</td>";
                            echo "<td>".$eventRegistration['lastname']."</td>";
                            echo "<td>".$eventRegistration['email']."</td>"; 
                 
                            if ($eventRegistration['paid'] == true ) {
                                echo "<td>&#10004;</td>"; 
                              } else {
                                  echo "<td>&times;</td>"; 
                              } 
                            echo "<td>".$eventRegistration['message']."</td>";         
                            echo "<td>".$eventRegistration['dateregistered']."</td>";
                     
                          echo "</tr>";
                      }
                    }
                    
                echo '</table>';
                echo '<br><br>';
            }
            echo '</section>'; 
            echo '</div>'; 
 ?> 

     <footer >
    <?php
    require 'footer.php';
   ?>
</body>
</html>