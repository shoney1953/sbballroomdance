<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';

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
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($classRegs, $reg_item);
    
    }
} 
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
            'eventdate' => $eventdate,
            'email' => $email,
            'paid' => $paid,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegs, $reg_item);

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
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="index.php">Back to Home</a></li>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
    <div class="content">
    <br>
    <h1>User Profile</h1>
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
    echo '</div>';
    echo '<div class="form-grid-div">';

        echo '<form method="POST" action="actions/updateUserInfo.php">';
        echo '<label for="firstname">First Name</label><br>';
        echo '<input type="text" name="firstname" value="'.$user->firstname.'"><br>';
        echo '<label for="lastname">Last Name</label><br>';
        echo '<input type="text" name="lastname" value="'.$user->lastname.'"><br>';
        echo '<label for="partnerid">Partner Id</label><br>';
        echo '<input type="text" name="partnerid" value="'.$user->partnerId.'"><br>';
        echo '<label for="newemail">New Email -- Must Not Be a Duplicate</label><br>';
        echo '<input type="email" name="newemail" value="'.$user->email.'" ><br>';
        echo '<label for="newuser">Username -- Must not be a Duplicate</label><br>';
        echo '<input type="text" name="newuser" value="'.$user->username.'"><br>';
        echo '<input type="hidden" name="id" value="'.$user->id.'">';
        echo '<input type="hidden" name="username" value="'.$user->username.'">';
        echo '<input type="hidden" name="email" value="'.$user->email.'">';
        echo '<input type="hidden" name="password" value="'.$user->password.'">';
        echo '<input type="hidden" name="role" value="'.$user->role.'">';
        echo '<label for="hoa">HOA</label><br>';
        echo '<select name = "hoa" value="'.$user->hoa.'">';
        echo '<option value = "1">HOA 1</option>';
        echo '<option value = "2">HOA 2</option>';
        echo '</select><br>';
        echo '<label for="phone1" >Enter primary phone number: </label><br>';
        echo '<input type="tel"  name="phone1"
            pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
            required value="'.$user->phone1.'">';
        echo '<small>Format: 123-456-7890</small><br>';
        echo '<label for="phone2">Enter secondary phone number (Optional): </label><br>';
        echo '<input type="tel"  name="phone2"
            pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
            value="'.$user->phone2.'">' ;
        echo '<small>Format: 123-456-7890</small><br>';
        echo '<label for="streetaddress">Street Address</label><br>';
        echo '<input type="text" name="streetaddress" 
            value="'.$user->streetAddress.'"><br>';
        echo '<label for="city">City</label><br>';
        echo '<input type="text" name="city" value="'.$user->city.'">  ';
        echo '<label for="state">State</label><br>';
        echo '<input type="text" name="state" maxsize="2" 
            value="'.$user->state.'">  ';
        echo '<label for="zip">Zip</label><br>';
        echo '<input type="text" name="zip" maxsize="10" 
            value="'.$user->zip.'"><br>';

        echo '<p> Notes</p><br>';
        echo '<textarea name="notes" cols="50" rows="5" 
            >'.$user->notes.'</textarea><br><br>';
        echo '<button type="submit" name="submitUpdateUser">
             Update Your Information</button><br>';
  
        echo '</form>';
        echo '</div>';
  
     ?>
    
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
            echo '<input type="hidden" name="currentpass" 
            value="'.$user->password.'"><br>';
            echo '<input type="hidden" name="id" value="'.$user->id.'"><br>';
        ?>
        <br>
        <button type="submit" name="SubmitPassChange">Change Your Password</button><br>
    
        </form>
        </div>
      
       </div>
   
    <div class="form-grid3">
    <div class="form-grid-div">
   
    <br>
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
    <form method='POST' action="actions/maintainClassReg.php">
        
      
        <h4>Manage Your Class Registration(s)</h4>
         
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete</label>
        <label for='regId'>
        <em> &rarr; Specify Registration ID from Table to Delete Your Registration:  
        </em></label>
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
    <form method='POST' action="actions/maintaineventReg.php">
        
        <div class="form-grid-div">
        <h4>Manage Your Event Registration(s)</h4>
    
        <input type='checkbox' name='deleteReg'>
        <label for='deleteReg'>Delete</label>
        <label for='regId'><em> &rarr; 
        Specify Registration ID from Table above to Delete Your Registration:  
        </em></label>
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
  require 'footer.php';
?>
</body>
</html>