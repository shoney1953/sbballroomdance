<?php
session_start();
require_once 'config/Database.php';
require_once 'models/DinnerMealChoices.php';
require_once 'models/EventRegistration.php';
$allEvents = $_SESSION['upcoming_events'];
$eventRegistrations = [];
$questionpos = 0;
$eventid = 0;
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


$eventReg = new EventRegistration($db);

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
if (isset($_GET['sort'])) {

    if (isset($_GET['id'])) {
    $eventid = $_GET['id'];
    }
   $sortVal = $_GET['sort'];

   $questionpos = stripos($_SERVER['REQUEST_URI'], '?');

if ($questionpos !== 0) {
    $_SESSION['eventmemurl'] = substr($_SERVER['REQUEST_URI'],0,$questionpos);

}
if ($sortVal === 'RegDate') {
     $eventRegistrations = $_SESSION['eventRegByRegDate'];
}
if ($sortVal === 'paid') {
     $eventRegistrations = $_SESSION['eventRegByPaid'];
}
if ($sortVal === 'meal') {
     $eventRegistrations = $_SESSION['eventRegByMeal'];
}  
if ($sortVal === 'moddate') {
     $eventRegistrations = $_SESSION['eventRegByModDate'];
}  
if ($sortVal === 'attenddinner') {
     $eventRegistrations = $_SESSION['eventRegByAttendDinner'];
} 
if ($sortVal === 'email') {
     $eventRegistrations = $_SESSION['eventRegByEmail'];
} 
if ($sortVal === 'firstname') {
     $eventRegistrations = $_SESSION['eventRegByFirstName'];
} 
if ($sortVal === 'lastname') {
     $eventRegistrations = $_SESSION['eventRegByLastName'];
} 
if ($sortVal === 'cornhole') {
     $eventRegistrations = $_SESSION['eventRegByCornHole'];
} 
if ($sortVal === 'softball') {
     $eventRegistrations = $_SESSION['eventRegBySoftBall'];
} 
} //sort set



if ((isset($_GET['id'])) && (!isset($_GET['sort']))) {
    $eventid = $_GET['id'];
    $result = $eventReg->read_ByEventId($_GET['id']);
    
    unset($_GET['id']);
   $questionpos = stripos($_SERVER['REQUEST_URI'], '?');

if ($questionpos !== 0) {
    $_SESSION['eventmemurl'] = substr($_SERVER['REQUEST_URI'],0,$questionpos);

}
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
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    }

}
} // id set no sort
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
                 if ($event["id"] === $eventid) {
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
                    echo '<form id="sortform" method="POST" action="actions/sorteventreg.php">';
                    echo "<input type='hidden' name='eventid' value='".$event['id']."'>";
                echo '<table>';
            
                    echo '<tr>';
                        echo '<th>ID</th>';
                            echo '<input type="hidden" id="firstname" name="firstname" value="0">';
                        echo '<th onclick="submitFN()">First Name</th>';
                            echo '<input type="hidden" id="lastname" name="lastname" value="0">';
                        echo '<th onclick="submitLN()">Last Name    </th>';
                              echo '<input type="hidden" id="email" name="email" value="0">';
                        echo '<th onclick="submitEM()">Email</th>';
                       

                        if ($event['eventtype'] === 'Dance Party') {
                              echo '<input type="hidden" id="attenddinner" name="attenddinner" value="0">';
                            echo '<th onclick="submitAD()">Attend<br>Dinner?</th>';
                            echo '<th>Attend<br>Dance?</th>';
                            if ($event['eventcost'] > 0) {
                                echo '<input type="hidden" id="paid" name="paid" value="0">';
                                echo '<th onclick="submitPD()">Paid?</th>';
                       
                                echo '<th>Online?</th>';
                                   echo '<input type="hidden" id="meal" name="meal" value="0">';
                                echo  '<th onclick="submitMS()">Meal Selected</th>';
                            }
                        }
                        
                        if ($event['eventtype'] === 'BBQ Picnic') {
                             echo '<input type="hidden" id="attenddinner" name="attenddinner" value="0">';
                            echo '<th onclick="submitAD()">Attend<br>Lunch?</th>';
                              echo '<input type="hidden" id="cornhole" name="cornhole" value="0">';
                            echo '<th onclick="submitCH()">Play<br>Cornhole?</th>';
                              echo '<input type="hidden" id="softball" name="softball" value="0">';
                            echo '<th onclick="submitSB()">Play<br>Softball?</th>';

                        }
                        if ($event['eventtype'] === 'Dinner Dance') {

                            if ($event['eventcost'] > 0) {
                                  echo '<input type="hidden" id="paid" name="paid" value="0">';
                                echo '<th onclick="submitPD()">Paid?</th>';
                                echo '<th>Online?</th>';
                                echo '<input type="hidden" id="meal" name="meal" value="0">';
                                echo  '<th onclick="submitMS()">Meal Selected</th>';
                             
                            }
                        }

                        echo '<input type="hidden" id="regdate" name="regdate" value="0">';
                         echo '<th  onclick="submitRD()" > Reg Date</th>';
                        echo '<th>Reg By</th>';
                        echo '<th>Message</th>';
                        echo '<input type="hidden" id="moddate" name="moddate" value="0">';
                         echo '<th  onclick="submitMD()" > Mod Date</th>';
          
                        echo '<th>Mod By</th>';
                    echo '</tr>';
                    echo '</form>';
                    foreach($eventRegistrations as $eventRegistration) {
                  

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
                  
                            echo "<td>".$eventRegistration['modifieddate']."</td>"; 
                            echo "<td>".$eventRegistration['modifiedby']."</td>"; 
                          echo "</tr>";
                      
                    
                
                    
                }
                    
                echo '</table>';
                echo '<br><br>';
            }
        }
            echo '</section>'; 
            echo '</div>'; 
    

 ?> 

     <footer >
    <?php
    require 'footer.php';
   ?>
   <script>
        function submitRD() {
            let form = document.getElementById("sortform");
            document.getElementById("regdate").value = "1";
            form.submit();
       
        }
         function submitPD() {
            let form = document.getElementById("sortform");
            document.getElementById("paid").value = "1";
            form.submit();
       
        }
         function submitMS() {
            let form = document.getElementById("sortform");
            document.getElementById("meal").value = "1";
            form.submit();
       
        }
        function submitMD() {
            let form = document.getElementById("sortform");
            document.getElementById("moddate").value = "1";
            form.submit();
       
        }
         function submitAD() {
            let form = document.getElementById("sortform");
            document.getElementById("attenddinner").value = "1";
            form.submit();
       
        }
        function submitEM() {
            let form = document.getElementById("sortform");
            document.getElementById("email").value = "1";
            form.submit();
       
        }
         function submitFN() {
            let form = document.getElementById("sortform");
            document.getElementById("firstname").value = "1";
            form.submit();
       
        }
        function submitLN() {
            let form = document.getElementById("sortform");
            document.getElementById("lastname").value = "1";
            form.submit();
       
        }
         function submitCH() {
            let form = document.getElementById("sortform");
            document.getElementById("cornhole").value = "1";
            form.submit();

        }
        function submitSB() {
            let form = document.getElementById("sortform");
            document.getElementById("softball").value = "1";
            form.submit();
       
        }
    </script>
</body>
</html>