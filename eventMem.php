<?php
session_start();
require_once 'config/Database.php';
require_once 'models/DinnerMealChoices.php';
require_once 'models/EventRegistration.php';
$allEvents = $_SESSION['upcoming_events'];
$eventRegistrations = [];


$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {

           if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
}
$database = new Database();
$db = $database->connect();

/* get event registrations */
$eventReg = new EventRegistration($db);
$result = $eventReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegistrations'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    }
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
    <li><a href="index.php">Back to Home</a></li>
    <?php
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'MEMBER') && ($_SESSION['role'] != 'visitor') ) {
        echo '<li><a href="administration.php">Back to Administration</a></li>';
        }
    }
    ?>
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
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                  echo "</tr>";
                  echo '</table>';
                  echo '<br>';
          

              echo '<h3>Registrations</h3>';
                echo '<table>';
                    echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                       

                        if ($event['eventtype'] === 'Dance Party') {
                            echo '<th>Attend<br>Dinner?</th>';
                            echo '<th>Attend<br>Dance?</th>';
                            if ($event['eventcost'] > 0) {
                                echo '<th>Paid?</th>';
                                echo '<th>Online?</th>';
                                echo  '<th>Meal Selected</th>';
                            }
                        }
                        
                        if ($event['eventtype'] === 'BBQ Picnic') {
                            echo '<th>Attend<br>Dinner?</th>';
                     
              
                        

                        }
                        if ($event['eventtype'] === 'Dinner Dance') {

                            if ($event['eventcost'] > 0) {
                                echo '<th>Paid?</th>';
                                echo '<th>Online?</th>';
                                echo  '<th>Meal Selected</th>';
                            }
                        }

                        echo '<th>Reg Date</th>';
                        echo '<th>Reg By</th>';
                        echo '<th>Message</th>';
                        echo '<th>Mod Date</th>';
                        echo '<th>Mod By</th>';
                    echo '</tr>';
                    
            
                    foreach($eventRegistrations as $eventRegistration) {
                  
                         if ($eventRegistration['eventid'] === $_GET['id']) {
                  
                          echo "<tr>";
                          $hr = 'member.php?id=';
                          $hr .= $eventRegistration["userid"];

                          echo '<td> <a href="'.$hr.'">'.$eventRegistration["userid"].'</a></td>';
                            echo "<td>".$eventRegistration['firstname']."</td>";
                            echo "<td>".$eventRegistration['lastname']."</td>";
                            echo "<td>".$eventRegistration['email']."</td>"; 
                    
                           if ($event['eventtype'] === 'BBQ Picnic') {
                                if ($eventRegistration['ddattenddinner'] == true ) {
                                    echo "<td>&#10004;</td>"; 
                                } else {
                                    echo "<td>&times;</td>"; 
                                } 
                           }
                            if ($event['eventtype'] === 'Dance Party') {
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
                             
                                   
                                    if ($eventRegistration['paid'] == true ) {
                                        echo "<td>&#10004;</td>"; 
                                    } else {
                                        echo "<td>&times;</td>"; 
                                    } 
                                   if ($eventRegistration['paidonline'] == true ) {
                                        echo "<td>&#10004;</td>"; 
                                    } else {
                                        echo "<td>&times;</td>"; 
                                    } 
                               
                               
                                
                            }
                            if ($event['eventtype'] === 'Dinner Dance') {
                                if ($event['eventcost'] > 0) {
                              
                                    if ($eventRegistration['paid'] == true ) {
                                        echo "<td>&#10004;</td>"; 
                                    } else {
                                        echo "<td>&times;</td>"; 
                                    } 
                                  if ($eventRegistration['paidonline'] == true ) {
                                        echo "<td>&#10004;</td>"; 
                                    } else {
                                        echo "<td>&times;</td>"; 
                                    } 

                                }
                            }
                            if ($event['eventtype'] !== 'BBQ Picnic') {
                               
                            echo "<td>".$eventRegistration['mealname']."</td>"; 
                            }
                            echo "<td>".$eventRegistration['dateregistered']."</td>"; 
                            echo "<td>".$eventRegistration['registeredby']."</td>"; 
                            echo "<td>".$eventRegistration['message']."</td>"; 
                            echo "<td>".$eventRegistration['modifiedby']."</td>"; 
                            echo "<td>".$eventRegistration['modifieddate']."</td>"; 
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