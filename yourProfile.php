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
$pclassRegs = [];
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

if ($user->partnerId > 0) {
$pclassReg = new ClassRegistration($db);
$presult = $classReg->read_ByUserid($partner->id);
$prowCount = $result->rowCount();
$pnumClasses = $rowCount;
if ($prowCount > 0) {
  
    while ($row = $presult->fetch(PDO::FETCH_ASSOC)) {
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
        array_push($pclassRegs, $reg_item);
        array_push($_SESSION['classregistrations'], $reg_item);

    }
} 

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
        <li><a href="#classregistrations">Your Class Registrations</a></li>
        <li><a href="#eventregistrations">Your Event Registrations</a></li>
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
             <br><label style='font: smaller' for='directorylist'><em>1 to list, 0 to Remove</em></label>
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
       <br><br>
       
       <section id="classregistrations" class="content">
       <h2>Class Registrations</h2>
       
    <div class="form-grid2">
    <div class="form-grid-div">

        <table>
        <thead>  
            <tr>
                <th colspan='6' style='text-align: center'>Your Class Registrations</th>
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
    <?php
    if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';

        echo "<table>";
        echo "<thead>";  
            echo "<tr>";
                echo "<th colspan='6' style='text-align: center'>Your Partners Class Registrations</th>";
            echo "</tr>";
            echo "<tr>";
             
                echo "<th>Delete?</th>";
     
                echo "<th>Class Name</th>";
                echo "<th>Start Date</th>";
                echo "<th>Class Time</th>";
                echo "<th>Date Registered</th>";     
                echo "<th>Registered By</th>";       
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo '<form method="POST" action="actions/deleteClassReg.php">';
      
 
            foreach ($pclassRegs as $classRegistration) {
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
         
           
        echo "</tbody>";
        echo "</table>";
    
        echo "<button type='submit' name='submitDeleteReg'>Delete Your Partners Class Registrations</button>";    
        echo "</form>";
    echo "</div>";
        }
     ?> 
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
    echo '</div>';

    ?>



    </div>
    </section>
    <?php
    echo '<section id="membership" class="content">';
    echo '<div class="form-grid3">';
    echo "<div class='form-grid-div'>";

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=2 style="text-align: center">Membership Status</th>';
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

    echo "<div class='form-grid-div'>";

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=1 style="text-align: center">Membership Form</th>';
    echo '</tr>';
    echo "<tr>";
    echo "<td>FORM</td>";
 
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    echo "<tr>";
    echo '<td><a href="'.$user->regformlink.'">VIEW REGISTRATION FORM</a></td>';
    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo '</div>';

    echo '</div>';
    echo '<br><br>';
    ?>
        </section>
    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>