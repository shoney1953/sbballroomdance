<?php
session_start();
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];

include_once 'config/Database.php';
include_once 'models/ClassRegistration.php';
include_once 'models/EventRegistration.php';
include_once 'models/User.php';

$classRegs = [];
$eventRegs = [];
$numEvents = 0;
$numClasses = 0;
$database = new Database();
$db = $database->connect();
$userid = $_SESSION['userid'];
$user = new User($db);
$user->id = $_SESSION['userid'];
$user->read_single();

/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read_ByUserid($_SESSION['userid']);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if($rowCount > 0) {
  
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classtime' => $classtime,
            'classdate' => $classdate,
            'email' => $email,
            "dateregistered" => $dateregistered
        );
        array_push( $classRegs, $reg_item);
    
    }
  

} else {
   echo 'NO CLASS REGISTRATIONS';

}
$eventReg = new EventRegistration($db);
$result = $eventReg->read_ByUserid($_SESSION['userid']);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if($rowCount > 0) {

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
            "dateregistered" => $dateregistered
        );
        array_push( $eventRegs, $reg_item);
  
    }
  

} else {
   echo 'NO EVENT REGISTRATIONS';

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance Beta - Profile</title>
</head>
<body>
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <h1 class="logo" style="background-color: rgba(161, 121, 133, 0.2); border-radius: 45%; width: 70px;align-items:center">
        <a href="index.html"><img src="img/logobox.png" alt="" style="width: 50px;align-items:center"></a></h1>
     <ul>
        <li><a href="index.php">Back to Home</a></li>

    </ul>
     </div>
</nav>
    
    <br>
   <br><br><br>
   
   
    
 
    <div class="content">
    
    
       <div class="list-box">
       <p><em>User Profile</em></p>
       <ul>
           <?php
          
           echo '<li class=li-none> Name:    <strong> '.$user->firstname.' '.$user->lastname.'</strong></li>';
           echo '<li class=li-none> Username:  <strong>'.$user->username.' </strong></li>';
           echo '<li class=li-none> Email:    <strong> '.$user->email.' </strong></li>';
           echo '<li class=li-none> Created:   <strong>'.$user->created.' </strong></li>';
           ?>
       </ul>
       </div>
   
    <div class="form-grid3">
    <div class="form-grid-div">
   
    <br><br>
        <h4 class="section-header">Class Registrations</h4><br>    
        <table>
            <tr>
                <th>Class Name</th>
                <th>Class Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>          
            </tr>
            <?php 
    
            foreach($classRegs as $classRegistration) {
        
    
                  echo "<tr>";
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
   
    <h4 class="section-header">Event Registrations</h4><br>    
        <table>
            <tr>
                
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Date Registered</th>          
            </tr>
            <?php 
    
            foreach($eventRegs as $eventRegistration) {
                $eventName = 'NONE';
            
                  echo "<tr>";
                    
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";           
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
 
    <br><br>
    </div>
    </div>
    </div>

</body>
</html>