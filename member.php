<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/ClassRegistrationArch.php';
require_once 'models/EventRegistration.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$userid = 0;
if (isset($_GET['id'])) {
    $userid = $_GET['id'];
} else {
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
}


$classRegs = [];
$classRegsArch = [];
$eventRegs = [];
$eventRegsArch = [];
$numEvents = 0;
$numEventsArch = 0;
$numClasses = 0;
$numClassesArch = 0;
$database = new Database();
$db = $database->connect();

$user = new User($db);
$partner = new User($db);
$user->id = $userid;
$user->read_single();
if ($user->partnerId !== 0) {
    $partner->id = $user->partnerId;
    $partner->read_single();
}

/* get class registrations */
$classReg = new ClassRegistration($db);
$classRegArch = new ClassRegistrationArch($db);
$result = $classReg->read_ByUserid($userid);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if ($rowCount > 0) {
  
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'classdate' => $classdate,
            'email' => $email,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($classRegs, $reg_item);
    
    }
} 
$result = $classRegArch->read_ByUserid($userid);

$rowCount = $result->rowCount();
$numClassesArch = $rowCount;

if ($rowCount > 0) {
  
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'classdate' => $classdate,
            'email' => $email,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($classRegsArch, $reg_item);
    
    }
} 
$eventReg = new EventRegistration($db);
$eventRegArch = new EventRegistrationArch($db);
$result = $eventReg->read_ByUserid($userid);

$rowCount = $result->rowCount();
$numEvents = $rowCount;

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
            'email' => $email,
            'paid' => $paid,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegs, $reg_item);

    }
} 

$result = $eventRegArch->read_ByUserid($userid);

$rowCount = $result->rowCount();
$numEventsArch = $rowCount;

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
            'email' => $email,
            'paid' => $paid,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegsArch, $reg_item);

    }
}

$eventReg = new MemberPaid($db);
$yearsPaid = [];
$result = $eventReg->read_byUserid($userid);

$rowCount = $result->rowCount();

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paid_item = array(
            'id' => $id,
            'paid' => $paid,
            'year' => $year

        );
        array_push($yearsPaid, $paid_item);

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
    <title>SBDC Ballroom Dance - Admin Member Profile</title>
</head>
<body>
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="administration.php">Back to Admin</a></li>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
    <div class="content">
    <br>
    <h1>Admin Member Profile</h1>
    <div class="form-grid2">
    <div class="form-grid-div">
   

           <?php
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="4" style="color: darkviolet;text-align:center">Membership Data</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>First Name</td>';
            echo '<th>Last Name</td>';
            echo '<th>Email</th>';
            echo '<th>User Name</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo "<td>$user->firstname</td>";
            echo "<td>$user->lastname</td>";
            echo "<td>$user->email</td>";
            echo "<td>$user->username</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Created</td>';
            echo '<th>Last Login</td>';
            echo '<th>Num Logins</th>';
            echo '<th>PWD Last Changed</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo "<td>$user->created</td>";
            echo "<td>$user->lastLogin</td>";
            echo "<td>$user->numlogins</td>";
            echo "<td>$user->passwordChanged</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Partner ID</td>';
            echo '<th>Partner Name</td>';
            echo '<th>Primary Phone</th>';
            echo '<th>Secondary Phone</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo "<td>$user->partnerId</td>";
            echo "<td>$partner->firstname $partner->lastname </td>";
            echo "<td>$user->phone1</td>";
            echo "<td>$user->phone2</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
       
            echo '<th>Street Address</td>';
            echo '<th>City</th>';
            echo '<th>State</th>';
            echo '<th>Zip</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
     
            echo "<td>$user->streetAddress</td>";
            echo "<td>$user->city</td>";
            echo "<td>$user->state</td>";
            echo "<td>$user->zip</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
       
            echo '<th>HOA</td>';
            echo '<th>Directory</th>';
            echo '<th>Fulltime</th>';
            echo '<th colspan="2">Notes</th>';
 
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
     
            echo "<td>$user->hoa</td>";
            echo "<td>$user->directorylist</td>";
            echo "<td>$user->fulltime</td>";
            echo "<td colspan='2'>$user->notes</td>";

            echo '</tr>';
            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
  
            echo '<th>Current Classes</td>';
            echo '<th>Past Classes</th>';
            echo '<th>Current Events</th>';
            echo '<th>Past Events</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
     
            echo "<td>$numClasses</td>";
            echo "<td>$numClassesArch</td>";
            echo "<td>$numEvents</td>";
            echo "<td>$numEventsArch</td>";

            echo '</tr>';

            echo '</tbody>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ROBO DJ Num Logins</td>';
            echo '<th>ROBO DJ Last Login</th>';
            echo '</tr>';

            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo "<td>$user->robodjnumlogins</td>";
            echo "<td>$user->robodjlastlogin</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        
           
          
    echo "<br><br>";
    echo '</div>';
    echo '<div class="form-grid-div">';
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan="2" style="color: darkviolet;text-align:center">Membership Status</th>';
    echo '</tr>';
    echo '<tr>';
    echo "<th>YEAR</th>";
    echo "<th>PAID?</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($yearsPaid as $year) {
        echo "<tr>";
        echo "<td>".$year['year']."</td>";
        
        if ($year['paid'] == true ) {
            echo "<td>&#10004;</td>"; 
          } else {
              echo "<td>&times;</td>"; 
          }  
        echo "</tr>";
    }
    echo "</tbody>";
    echo '</table>';
    echo '</div>';

  
     ?>
    
      

   

    <div class="form-grid-div">
   
  
        <table>
            <thead>
            <tr>
            <th colspan="5" style="color: darkviolet;text-align:center">Active Classes</th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>  
                <th>Registered By</th>           
            </tr>
           </thead>
           <tbody>

            <?php 
    
            foreach ($classRegs as $classRegistration) {
        
    
                  echo "<tr>";
                    echo "<td>".$classRegistration['id']."</td>";
                    echo "<td>".$classRegistration['classname']."</td>";
                    echo "<td>".$classRegistration['classdate']."</td>";  
                    echo "<td>".$classRegistration['classtime']."</td>";         
                    echo "<td>".$classRegistration['dateregistered']."</td>";
                    echo "<td>".$classRegistration['registeredby']."</td>";
             
                  echo "</tr>";
            }
         
            ?> 
           </tbody>
        </table>

        
    </div>

    <div class="form-grid-div">

   
        <table>
        <thead>
            <tr>
            <th colspan="5" style="color: darkviolet;text-align:center">Active Events</th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Paid</th>
                <th>Date Registered</th>          
            </tr>
        </thead>
        <tbody>
            <?php 
    
            foreach ($eventRegs as $eventRegistration) {
                $eventName = 'NONE';
            
                  echo "<tr>";
                    echo "<td>".$eventRegistration['id']."</td>";
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";  
                    if ($eventRegistration['paid'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
            }
         
            ?> 
        </tbody>
        </table>
    </div>
    <div class="form-grid-div">
   
       <table>
       <thead>
            <tr>
            <th colspan="5" style="color: darkviolet;text-align:center">Past Class Registrations</th>'
            </tr>
           <tr>
               <th>ID</th>
               <th>Class Name</th>
               <th>Class Date</th>
               <th>Class Time</th>
               <th>Date Registered</th>    
               <th>Registered By</th>       
           </tr>
       </thead>
       <tbody>
           <?php 
   
           foreach ($classRegsArch as $classRegistration) {
       
   
                 echo "<tr>";
                   echo "<td>".$classRegistration['id']."</td>";
                   echo "<td>".$classRegistration['classname']."</td>";
                   echo "<td>".$classRegistration['classdate']."</td>";  
                   echo "<td>".$classRegistration['classtime']."</td>";         
                   echo "<td>".$classRegistration['dateregistered']."</td>";
                   echo "<td>".$classRegistration['registeredby']."</td>";
            
                 echo "</tr>";
           }
        
           ?> 
       </tbody>
       </table>
   <br><br>

       
   </div>

   <div class="form-grid-div">
   <table>
   <thead>
            <tr>
            <th colspan="5" style="color: darkviolet;text-align:center">Past Event Registrations</th>'
            </tr> 

           <tr>
               <th>ID</th>
               <th>Event Name</th>
               <th>Event Date</th>
               <th>Paid</th>
               <th>Date Registered</th>  
               <th>Registered By</th>          
           </tr>
   </thead>
   <tbody>
           <?php 
   
           foreach ($eventRegsArch as $eventRegistration) {
               $eventName = 'NONE';
           
                 echo "<tr>";
                   echo "<td>".$eventRegistration['id']."</td>";
                   echo "<td>".$eventRegistration['eventname']."</td>";
                   echo "<td>".$eventRegistration['eventdate']."</td>";  
                   if ($eventRegistration['paid'] == true ) {
                     echo "<td>&#10004;</td>"; 
                   } else {
                       echo "<td>&times;</td>"; 
                   }
                   echo "<td>".$eventRegistration['dateregistered']."</td>";
                   echo "<td>".$eventRegistration['registeredby']."</td>";
            
                 echo "</tr>";
           }
        
           ?> 
   </tbody>
       </table>

   </div>
    </div>
    <footer >

<?php
  require 'footer.php';
?>
</body>
</html>