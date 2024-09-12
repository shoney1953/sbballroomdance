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

$classRegs = [];
$eventRegs = [];
$numEvents = 0;
$numClasses = 0;
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

/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read_ByUserid($_SESSION['userid']);

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
$_SESSION['classregistrations'] = $classRegs;
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
            'email' => $email,
            'paid' => $paid,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegs, $reg_item);

    }
} 
$_SESSION['eventregistrations'] = $eventRegs;
$eventReg = new MemberPaid($db);
$yearsPaid = [];
$result = $eventReg->read_byUserid($_SESSION['userid']);

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
    <title>SBDC Ballroom Dance - Profile</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="#yourregistrations">Your Registrations</a></li>
        <li><a href="#membership">Membership Status</a></li>
    </ul>
    </div>
</nav> 


<div class="container-section" >
<div class="content">
    <br><br>
    <h3>Member Profile</h3>
    <form method='POST' action='actions/updateUserInfo.php'>
  
    <div class="form-container">
    <h4 class="form-title">Your Profile Information</h4>
        <div class="form-grid">
            <div class="form-item">
            <h4 class="form-item-title">First Name</h4>
             <input type='text' name='firstname' value='<?php echo $user->firstname ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Last Name</h4>
             <input type='text' name='lastname' value='<?php echo $user->lastname ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Email</h4>
             <input type='email' name='newemail' value='<?php echo $user->email?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">User Name</h4>
             <input type='text' name='newuser' value='<?php echo $user->username ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Directory List: </h4>
             <input type='number' name='directorylist' min='0' max='1' value='<?php echo $user->directorylist ?>'>
             <br><label style='font: smaller;color: darkviolet' for='directorylist'><em>1 to list, 0 to Remove</em></label>
            </div>
       
           
            <div class="form-item">
            <h4 class="form-item-title">HOA</h4>
            <select name = 'hoa' value='<?php echo $user->hoa ?>'>
            <?php
                if ($user->hoa == '1') {
                    echo "<option value = '1' selected>HOA 1</option>";
                } else {
                    echo "<option value = '1' >HOA 1</option>";
                }
                if ($user->hoa == 2) {
                    echo "<option value = '2' selected>HOA 2</option>";
                } else {
                    echo "<option value = '2' >HOA 2</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Fulltime Resident: </h4>
             <!-- <input type='number' name='fulltime' min='0' max='1' value='<?php echo $user->fulltime ?>'> -->
             <!-- <br><label style='font: smaller;color: darkviolet' for='fulltime'><em>1 Yes, 0 Gone for the Summer</em></label> -->
             <select name = 'fulltime' value='<?php echo $user->fulltime ?>'>
            <?php
                if ($user->fulltime == '1') {
                    echo "<option value = 1 selected>Fulltime</option>";
                } else {
                    echo "<option value = '1' >Fulltime</option>";
                }
                if ($user->fulltime == '0') {
                    echo "<option value = '0' selected>Gone for the Summer</option>";
                } else {
                    echo "<option value = '0' >Gone for the Summer</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Primary Phone: </h4>
            <input type='tel'  name='phone1' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' value='<?php echo $user->phone1 ?>'><br>
             <small> Format: 123-456-7890</small>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Secondary Phone: </h4>
            <input type='tel'  name='phone2' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' value='<?php echo $user->phone2 ?>'><br>
             <small> Format: 123-456-7890</small>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Street Address: </h4>
            <input type='text' name='streetaddress' value='<?php echo $user->streetAddress ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">City: </h4>
            <input type='text' name='city' value='<?php echo $user->city ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">State: </h4>
            <input class='text-small' type='text' name='state' maxsize='2' value='<?php echo $user->state ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">zip: </h4>
            <input  type='text' name='zip' maxsize='10' value='<?php echo $user->zip ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Partner ID: </h4>
            <input type='number' name='partnerid' value='<?php echo $user->partnerId ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Notes: </h4>
            <textarea  name='notes' rows='4' cols='50'><?php echo $user->notes ?></textarea>
            </div>

        </div>
        <input type='hidden' name='id' value='<?php echo $user->id?>'>
        <input type='hidden' name='username' value='<?php echo $user->username?>'>
        <input type='hidden' name='email' value='<?php echo $user->email?>'>
        <input type='hidden' name='password' value='<?php echo $user->password ?>'>
        <input type='hidden' name='role' value='<?php echo $user->role ?>'>
      
        <button type="submit" name="submitUpdateUser">Update Your Information</button>
        </form>
    </div>
        
        <div class="form-container">
        <form method="POST" action="actions/updateUserPass.php">
        <h4 class="form-title">Change Your Password</h4>
        <div class="form-grid">
            <div class="form-item">
            <h4 class="form-item-title">Enter Your Current Password</h4>
            <input type="password" name="oldpassword" required minlength="8">
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Enter Your New Password</h4>
            <input type="password" name="newpassword" required minlength="8">
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Re-Enter Your New Password</h4>
            <input type="password" name="newpass2" required minlength="8">
            </div>
        </div>
        <input type="hidden" name="currentpass" value='<?php echo $user->password ?>'>
        <input type="hidden" name="id" value='<?php echo $user->id ?>'>   
        <button type='submit' name='SubmitPassChange'>Change Your Password</button>
        </form>
        </div>
     
        
    
       
   
       </div>
       <br>
       <section id="yourregistrations" class="content">
       
    <div class="form-grid2">
    <div class="form-grid-div">

 
        <table>
        <thead>  
            <tr>
                <th colspan='6' style='text-align: center;color: darkviolet'>Your Class Registrations</th>
            </tr>
            <tr>
             
                <th>Delete?</th>
     
                <th>Class Name</th>
                <th>Start Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>     
                <th>Registered By</th>       
            </tr>
        </thead>
        <tbody>
        <form method='POST' action="actions/deleteClassReg.php">
            <?php 
 
            foreach ($classRegs as $classRegistration) {
                $delID = "del".$classRegistration['id'];     
                  echo "<tr>";
                    echo "<td><input type='checkbox' title='Check to Delete Class Registration' 
                    name='".$delID."'></td>";
       
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
    

        <button type='submit' name="submitDeleteReg">Delete Your Class Registrations</button>    
        </form>
    </div>
    </div>


    <div class="form-grid2">

    <div class="form-grid-div">
 

    <form method='POST' action="actions/deleteEventReg.php">  
        <table>
            <thead>
            <tr>
                <th colspan="6" style="text-align: center;color: darkviolet">Your Event Registrations</th>
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
    </div>
    <?php
    echo '<section id="membership" class="content">';
    echo '<div class="form-grid3">';
    echo "<div class='form-grid-div'>";

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=2 style="text-align: center;color: darkviolet">Membership Status</th>';
    echo '</tr>';
    echo "<tr>";
    echo "<td>YEAR</td>";
    echo "<td>PAID?</td>";
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
    echo '<br><br>';
    echo '</div>';
    echo '</div>';
    ?>
        </section>
    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>