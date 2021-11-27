<?php
session_start();

if(isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif(isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '.$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} 
else {
    $_SESSION['profileurl'] = $_SERVER['REQUEST_URI']; 
    $_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
}

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
    <br><br>
    <h1>User Profile</h1>
    <div class="form-grid3">
    <div class="form-grid-div">
       <div class="list-box">
       <ul>
           <?php
           echo '<li class=li-none> Name:    <strong> '.$user->firstname.' '.$user->lastname.'</strong></li>';
           echo '<li class=li-none> Username:  <strong>'.$user->username.' </strong></li>';
           echo '<li class=li-none> Email:    <strong> '.$user->email.' </strong></li>';
           echo '<li class=li-none> Created:   <strong>'.$user->created.' </strong></li>';
           echo '<li class=li-none> Password Last Changed: <strong>'.$user->passwordChanged.' </strong></li>';
           ?>
       </ul>
    </div>
    </div>
       <br>
       <div class="form-grid-div">
                    <form method="POST" action="actions/updateUserPass.php">
              
                    <h4>Change Password</h4>
                    <label for="oldpassword">Enter Old Password</label><br>
                    <input type="password" name="oldpassword" required minlength="8"><br>
                    <br>
                    <label for="newpassword">Enter New Password minimum 8</label><br>
                    <input type="password" name="newpassword" required minlength="8"><br>
                    <label for="pass2">Reenter New Password</label><br>
                    <input type="password" name="newpass2" required minlength="8"><br>
                  <?php
                     echo '<input type="hidden" name="currentpass" value="'.$user->password.'"><br>';
                     echo '<input type="hidden" name="id" value="'.$user->id.'"><br>';
                  ?>
                    <br>
                    <button type="submit" name="SubmitPassChange">Submit</button><br>
               
        </form>
        </div>
      
       </div>
   
    <div class="form-grid3">
    <div class="form-grid-div">
   
    <br><br>
        <h4 class="section-header">Class Registrations</h4><br>    
        <table>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>          
            </tr>
            <?php 
    
            foreach($classRegs as $classRegistration) {
        
    
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
    <form method='POST' action="actions/maintainClassReg.php">
        
      
        <h4>Manage Your Class Registration(s)</h4>
         
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete</label>
        <label for='regId'><em> &rarr; Specify Registration ID from Table to Delete Your Registration:  </em></label>
        <input type='text' class='text-small' name='regId' >
        <br>
       
        <button type='submit' name="submitReg">Submit</button>    
        </form>
        
    </div>

    <div class="form-grid-div">
    <br><br>
    <h4 class="section-header">Event Registrations</h4><br>    
        <table>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Date Registered</th>          
            </tr>
            <?php 
    
            foreach($eventRegs as $eventRegistration) {
                $eventName = 'NONE';
            
                  echo "<tr>";
                    echo "<td>".$eventRegistration['id']."</td>";
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";           
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
             
                  echo "</tr>";
              }
         
            ?> 
        </table>
 
    <br><br>
    <form method='POST' action="actions/maintaineventReg.php">
        
        <div class="form-grid-div">
        <h4>Manage Your Event Registration(s)</h4>
    
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete</label>
        <label for='regId'><em> &rarr; Specify Registration ID from Table above to Delete Your Registration:  </em></label>
        <input type='text' class='text-small' name='regId' >
        <br>
       
        <button type='submit' name="submitEventReg">Submit</button>   
        </div>   
        </form>
    </div>
    </div>
    </div>
    <footer >

<?php
  include 'includes/footer.php';
?>
</body>
</html>