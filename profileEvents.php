<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '
    .$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif (isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '
    .$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} else {
    $_SESSION['profileurl'] = $_SERVER['REQUEST_URI']; 
    $_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
   
}


$eventRegs = [];
$peventRegs = [];

$numEvents = 0;
$pnumEvents = 0;
$numClasses = 0;
$pnumClasses = 0;
$database = new Database();
$db = $database->connect();
$userid = $_SESSION['userid'];
$user = new User($db);
$partner = new User($db);
$user->id = $_SESSION['userid'];
$user->read_single();
if ($user->partnerId !== 0) {
    $partner->id = $user->partnerId;
    $partner->read_single();
}
/* */
$eventReg = new EventRegistration($db);
$result = $eventReg->read_ByUserid($_SESSION['userid']);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'orgemail' => $orgemail,
            'ddattenddinner' => $ddattenddinner,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'email' => $email,
            'paid' => $paid,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegs, $reg_item);

    }
} 
$_SESSION['eventregistrations'] = $eventRegs;

if ($user->partnerId > 0) {
    $peventReg = new EventRegistration($db);
$presult = $peventReg->read_ByUserid($user->partnerId);

$prowCount = $presult->rowCount();
$pnumClasses = $prowCount;

if ($prowCount > 0) {

    while ($row = $presult->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'orgemail' => $orgemail,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'ddattenddinner' => $ddattenddinner,
            'email' => $email,
            'paid' => $paid,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($peventRegs, $reg_item);
        array_push($_SESSION['eventregistrations'], $reg_item);

    }
    
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
    <title>SBDC - Profile Event Registrations</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="#classregistrations">Your Class Registrations</a></li>
        <li><a href="#eventregistrations">Your Event Registrations</a></li>
        <li><a href="#membership">Membership Status</a></li>
    </ul>
    </div>
</nav> 


<div class="container-section" >
<div class="content">
    <br><br>
    
    
    </div>
       </section>

    <section id="eventregistrations" class="content">
        <h2>Event Registrations</h2>
    <div class="form-grid2">

    <div class="form-grid-div">
 

    <form method='POST' action="actions/deleteEventReg.php">  
        <table>
            <thead>
            <tr>
                <th colspan="6" style="text-align: center">Your Event Registrations</th>
            </tr>
            <tr>
                <th>Delete?</th>
      
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Paid</th>
                <th>Date Registered</th>   
                <th>Registered By</th>         
            </tr>
            </thead>
            <tbody>
            <?php 
    
            foreach ($eventRegs as $eventRegistration) {
                $eventName = 'NONE';
                $delID = "del".$eventRegistration['id'];
              
                  echo "<tr>";
                  echo "<td><input type='checkbox' 
                       title='Check to Delete Event Registration' 
                       name='".$delID."'> </td>";
 
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";  
                    if ($eventRegistration['paid'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
                    echo "<td>".$eventRegistration['registeredby']."</td>";
                    echo '<input type="hidden" name="eventid" value="'.$eventRegistration['eventid'].'">';
                  
                    
          
                  echo "</tr>";
            }
         
            ?> 
       
        </div>
            </tbody>
        </table>
        <button type='submit' name="submitDeleteReg">Delete Your Event Registrations</button>   
        
    </form>
    </div>
    <?php
     if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';
 

    echo '<form method="POST" action="actions/deleteEventReg.php">';  
        echo "<table>";
            echo "<thead>";
            echo "<tr>";
                echo '<th colspan="6" style="text-align: center">Your Partners Event Registrations</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Delete?</th>';
      
                echo '<th>Event Name</th>';
                echo '<th>Event Date</th>';
                echo '<th>Paid</th>';
                echo '<th>Date Registered</th>';   
                echo '<th>Registered By</th> ';        
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        
    
            foreach ($peventRegs as $eventRegistration) {
                $eventName = 'NONE';
                $delID = "del".$eventRegistration['id'];
              
                  echo "<tr>";
                  echo "<td><input type='checkbox' 
                       title='Check to Delete Event Registration' 
                       name='".$delID."'> </td>";
 
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";  
                    if ($eventRegistration['paid'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
                    echo "<td>".$eventRegistration['registeredby']."</td>";
                    echo '<input type="hidden" name="eventid" value="'.$eventRegistration['eventid'].'">';
                  
                    
          
                  echo "</tr>";
            }
       
       
        echo '</div>';
            echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitDeleteReg">Delete Your Partners Event Registrations</button> ';  
        
    echo '</form>';
    echo '</div>';
     }
    ?>
    <div class="form-grid-div">
 

 <form method='POST' action="actions/updateBBQEventReg.php">  
     <table>
         <thead>
         <tr>
             <th colspan="6" style="text-align: center">Modify Your Event Registrations</th>
         </tr>
         <tr>
             <th>Update?</th>
             <th>Event Name</th>
             <th>Event Type</th>
             <th>Event Date</th>
             <th>Date Registered</th>   
             <th>Registered By</th>         
         </tr>
         </thead>
         <tbody>
         <?php 
 
         foreach ($eventRegs as $reg) {
            if ($reg['eventtype'] == 'BBQ Picnic') {
                $updID = "upd".$reg['id'];
                $chID = "ch".$reg['id'];
                $sbID = "sb".$reg['id'];
                $updID = "upd".$reg['id'];
                $dddinID = "dddin".$reg['id'];
           
               echo "<tr>";
               echo "<td><input type='checkbox' 
                    title='Check to Update Event Registration' 
                    name='".$updID."'> </td>";

                 echo "<td>".$reg['eventname']."</td>";
                 echo "<td>".$reg['eventtype']."</td>";
                 echo "<td>".$reg['eventdate']."</td>";  
                 echo "<td>".$reg['dateregistered']."</td>";
                 echo "<td>".$reg['registeredby']."</td>";
                 echo '<input type="hidden" name="id" value="'.$reg['id'].'">';

       
               echo "</tr>";
               echo "<tr>";
               if ($reg['eventtype'] === 'BBQ Picnic') {
                $ad = 0;
                if ($reg['ddattenddinner']) {
                    $ad = $reg['ddattenddinner'];
                }
                else {
                    $ad = 0;
                }
                echo '<td>';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                echo "<input type='number'  title='Enter 1 for Attend dinner' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
                echo '</div>'; // end of form item
                echo '</td>';
                
                $ch = 0;
                if ($reg['cornhole']) {
                    $ch = $reg['cornhole'];
                }
                else {
                    $ch = 0;
                }
                echo '<td>';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                echo "<input type='number'  title='Enter 1 for Play Cornhole' name='".$chID."' min='0' max='1' value='".$ch."'>";
                echo '</div>'; // end of form item
                echo '</td>';

                $sb = 0;
                if ($reg['softball']) {
                    $sb = $reg['softball'];
                }
                else {
                    $sb = 0;
                }
    
               echo '<td>';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
                echo "<input type='number'  title='Enter 1 for Play Softball' name='".$sbID."' min='0' max='1' value='".$sb."'>";
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid
                echo "</tr>";
                echo '</td>';
            }
            
            }
          }
      
         ?> 
   
     </div>
         </tbody>
     </table>
     <button type='submit' name="submitUpdateReg">Update Your Event Registrations</button>   
     
 </form>
 </div>
 <?php
 if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';

    echo "<form method='POST' action='actions/updateBBQEventReg.php'>"; 
        echo '<table>';
           echo  '<thead>';
            echo '<tr>';
            echo '<th colspan="6" style="text-align: center">Modify Your Partners Event Registrations</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Update?</th>';
                echo '<th>Event Name</th>';
                echo '<th>Event Type</th>';
                echo '<th>Event Date</th>';
                echo '<th>Date Registered</th>';   
                echo '<th>Registered By</th>';         
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($peventRegs as $reg) {
               if ($reg['eventtype'] == 'BBQ Picnic') {
                   $updID = "upd".$reg['id'];
                   $chID = "ch".$reg['id'];
                   $sbID = "sb".$reg['id'];
                   $updID = "upd".$reg['id'];
                   $dddinID = "dddin".$reg['id'];
              
                  echo "<tr>";
                  echo "<td><input type='checkbox' 
                       title='Check to Update Event Registration' 
                       name='".$updID."'> </td>";
   
                    echo "<td>".$reg['eventname']."</td>";
                    echo "<td>".$reg['eventtype']."</td>";
                    echo "<td>".$reg['eventdate']."</td>";  
                    echo "<td>".$reg['dateregistered']."</td>";
                    echo "<td>".$reg['registeredby']."</td>";
                    echo '<input type="hidden" name="id" value="'.$reg['id'].'">';
   
          
                  echo "</tr>";
                  echo "<tr>";
            
                   $ad = 0;
                   if ($reg['ddattenddinner']) {
                       $ad = $reg['ddattenddinner'];
                   }
                   else {
                       $ad = 0;
                   }
                   echo '<td>';
                   echo '<div class="form-grid">';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                   echo "<input type='number'  title='Enter 1 for Attend dinner' name='".$dddinID."' min='0' max='1' value='".$ad."'>";
                   echo '</div>'; // end of form item
                   echo '</td>';
                   
                   $ch = 0;
                   if ($reg['cornhole']) {
                       $ch = $reg['cornhole'];
                   }
                   else {
                       $ch = 0;
                   }
                   echo '<td>';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                   echo "<input type='number'  title='Enter 1 for Play Cornhole' name='".$chID."' min='0' max='1' value='".$ch."'>";
                   echo '</div>'; // end of form item
                   echo '</td>';
   
                   $sb = 0;
                   if ($reg['softball']) {
                       $sb = $reg['softball'];
                   }
                   else {
                       $sb = 0;
                   }
       
                  echo '<td>';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Play Softball?</h4>';
                   echo "<input type='number'  title='Enter 1 for Play Softball' name='".$sbID."' min='0' max='1' value='".$sb."'>";
                   echo '</div>'; // end of form item
                   echo '</div>'; // end of form grid
                   echo "</tr>";
                   echo '</td>';
               
              
               } // if bbq
             } // foreach
             } // for eventreg
         
     
      
        echo '</div>';
        echo '</tbody>';
        echo '</table>';
        echo "<button type='submit' name='submitUpdateReg'>Update Your Partners Registrations</button>";   
        
    echo '</form>';
    echo '</div>';
    // echo '</div>';

    ?>



    </div>
    </section>
   

    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>