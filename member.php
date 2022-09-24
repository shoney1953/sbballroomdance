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
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($classRegsArch, $reg_item);
    
    }
} 
$eventReg = new EventRegistration($db);
$eventRegArch = new EventRegistrationArch($db);
$result = $eventRegArch->read_ByUserid($userid);

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
    <div class="form-grid3">
    <div class="form-grid-div">
       <div class="list-box">
       <ul>
           <?php
            echo '<li class=li-none> Name:    <strong> '.$user->firstname.' '
            .$user->lastname.'</strong></li>';
            echo '<li class=li-none> Username:  <strong>'.$user->username.' 
            </strong></li>';
            echo '<li class=li-none> Email:    <strong> '.$user->email.' 
            </strong></li>';
            echo '<li class=li-none> Created:   <strong>'.$user->created.' 
            </strong></li>';
            echo '<li class=li-none> Last Login:  <strong>'.$user->lastLogin.' 
            </strong></li>';
            echo '<li class=li-none> Number Logins:  <strong>'.$user->numlogins.' 
            </strong></li>';
            echo '<li class=li-none> Password Last Changed: 
            <strong>'.$user->passwordChanged.' 
            </strong></li>';
            echo '<li class=li-none> Partner Id: 
            <strong>'.$user->partnerId.' 
            </strong></li>';
            if ($user->partnerId !== 0) {
                echo '<li class=li-none> Partner Name: 
                <strong>'.$partner->firstname.' '.$partner->lastname.' 
                </strong></li>';
            }
            echo '<li class=li-none> Primary Phone: 
            <strong>'.$user->phone1.' 
            </strong></li>';
            echo '<li class=li-none> Secondary Phone: 
            <strong>'.$user->phone2.' 
            </strong></li>';
            echo '<li class=li-none> HOA: 
            <strong>'.$user->hoa.' 
            </strong></li>';
            echo '<li class=li-none> Street Address: 
            <strong>'.$user->streetAddress.' 
            </strong></li>';
            echo '<li class=li-none> City: 
            <strong>'.$user->city.' 
            </strong></li>';
            echo '<li class=li-none> State: 
            <strong>'.$user->state.' 
            </strong></li>';
            echo '<li class=li-none> Zip: 
            <strong>'.$user->zip.' 
            </strong></li>';
            echo '<li class=li-none> Notes: 
            <strong>'.$user->notes.' 
            </strong></li>';
            echo '<li class=li-none> Current Events: 
            <strong>'.$numEvents.' 
            </strong></li>';
            echo '<li class=li-none> Current Classes: 
            <strong>'.$numClasses.' 
            </strong></li>';
            echo '<li class=li-none> Past Events: 
            <strong>'.$numEventsArch.' 
            </strong></li>';
            echo '<li class=li-none> Past Classes: 
            <strong>'.$numClassesArch.' 
            </strong></li>';
            
        echo '</ul><br>';
    echo '<h4>Membership Status</h4>';
    echo '<table>';
    echo "<tr>";
    echo "<td>YEAR</td>";
    echo "<td>PAID?</td>";
    echo "</tr>";
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
    echo '</table>';
    echo '</div>';

 
  
     ?>
    
      
       </div>
   

    <div class="form-grid-div">
   
    <br>
        <h4 class="section-header">Active Class Registrations</h4><br>    
        <table>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>          
            </tr>
            <?php 
    
            foreach ($classRegs as $classRegistration) {
        
    
                  echo "<tr>";
                    echo "<td>".$classRegistration['id']."</td>";
                    echo "<td>".$classRegistration['classname']."</td>";
                    echo "<td>".$classRegistration['classdate']."</td>";  
                    echo "<td>".$classRegistration['classtime']."</td>";         
                    echo "<td>".$classRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
            }
         
            ?> 
        </table>
    <br><br>

        
    </div>

    <div class="form-grid-div">
    <br><br>
    <h4 class="section-header">Active Event Registrations</h4><br>    
        <table>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Paid</th>
                <th>Date Registered</th>          
            </tr>
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
        </table>
 
    <br><br>
    </div>
    <div class="form-grid-div">
   
   <br>
       <h4 class="section-header">Past Class Registrations</h4><br>    
       <table>
           <tr>
               <th>ID</th>
               <th>Class Name</th>
               <th>Class Date</th>
               <th>Class Time</th>
               <th>Date Registered</th>          
           </tr>
           <?php 
   
           foreach ($classRegsArch as $classRegistration) {
       
   
                 echo "<tr>";
                   echo "<td>".$classRegistration['id']."</td>";
                   echo "<td>".$classRegistration['classname']."</td>";
                   echo "<td>".$classRegistration['classdate']."</td>";  
                   echo "<td>".$classRegistration['classtime']."</td>";         
                   echo "<td>".$classRegistration['dateregistered']."</td>";
            
                 echo "</tr>";
           }
        
           ?> 
       </table>
   <br><br>

       
   </div>

   <div class="form-grid-div">
   <br><br>
   <h4 class="section-header">Past Event Registrations</h4><br>    
       <table>
           <tr>
               <th>ID</th>
               <th>Event Name</th>
               <th>Event Date</th>
               <th>Paid</th>
               <th>Date Registered</th>          
           </tr>
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
            
                 echo "</tr>";
           }
        
           ?> 
       </table>

   <br><br>
   </div>
    </div>
    <footer >

<?php
  require 'footer.php';
?>
</body>
</html>