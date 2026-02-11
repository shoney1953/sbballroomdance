l<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

if (!isset($_SESSION['username']))
{
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
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
       } else {
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
}
$addUser = false;



$database = new Database();
$db = $database->connect();
$user = new User($db);


$passdefault = 'test1234';
$userdefault = '';


if (isset($_POST['submitAddUser'])) {
    $addUser = true;
}

$addUser = true;
$errorExists = false;
$errorMessage = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Add New Member</title>
</head>
<body>
<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
           echo "<li><a href='../index.php'>Back to Home</a></li> ";
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='../SBDCAMembers.php'>Back to Members</a></li> ";
                echo "<li><a href='../administration.php'>Back to Administration</a></li> ";


            }

?>
    </ul>
     </div>
</nav>  
    <br><br><br>
    <section id="users" class="container content">

      <?php
if(isset($_GET['error'])) {
    $errorMessage = '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
    $errorExists = true;
    $addUser = true;
} elseif(isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '.$_GET['success'].'</h4><br>';
    unset($_GET['success']);
    $addUser = true;
} elseif (isset($_GET['userstatus'])) {
 
     if ($_GET['userstatus'] === 'NotArchived') {
         $errorMessage = '<br><h4 style="text-align: center"> Member was not archived Please Add the member. </h4><br>';
          $addUser = true;
            $errorExists = true;
     }
     if ($_GET['userstatus'] === 'SuccessfullyUnArchived') {
         $errorMessage = '<br><h4 style="text-align: center"> Member was restored. Please go to Maintain Member to Check the information for validity. </h4><br>';
         $errorExists = true;
     }
      if ($_GET['userstatus'] === 'MemberExists') {
         $errorMessage = "<br><h4 style='text-align: center'> A Current Member exists with this email: ".$_GET['email']."</h4><br>";
         $errorExists = true;
     }
    unset($_GET['userstatus']);
}

else {
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI']; 

}       

if ($errorExists) {
    echo '<div class="form-container">';
    echo $errorMessage;
    echo '<div>';

}
        if ($addUser) {
                  echo '<div class="form-container">';
               
            echo '<form method="POST" action="checkUserArchive.php">';
            echo '<legend>Enter Email to check if they were a former member and UnArchive them.</legend>';
            echo '<h4>You will need to add their partner separately  (if there is one) and relink them</h4>';
                  echo '<div class="form-grid">';  
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Archived Email</h4>';
            echo "<input type='email' id='archemail' name='archemail' title='Member Email ' placeholder='Email to check and restore' required><br>";
            echo '</div>';

              echo '<button type="submit" name="submitCheckArch">Check if previous member</button><br>';
                echo '</div>';
            echo '</div>';
            echo '</form>';

          echo '</br>';
       
            echo '<div class="form-container">';
           
            echo '<form method="POST" action="addUser.php">';
            echo '<legend>Enter Member Information then click on the Add Member button</legend>';
            echo '<h4 class="form-title">Note: City, State, Zip and passwords are all defaulted</h4>';
            echo '<div class="form-grid">';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">First Name</h4>';
            echo "<input type='text' id='addfirstname' title='Member First Name' name='firstname' required>";
            echo '</div>';
 
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Last Name</h4>';
            echo "<input type='text' id='addlastname' title='Member Last Name' name='lastname' required>";
            echo '</div>';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Email</h4>';
            echo "<input type='email' id='addemail' name='email' title='Member Email if none exists use firstname@xxxxx.com' placeholder='Must not be a duplicate' required><br>";
            echo '</div>';

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">User Name</h4>';
            echo "<input type='text' name='username' title='Capital letter of first name, then the rest of first name, followed by Capital letter of last name' value='".$userdefault."' required >";
            echo '</div>'; 

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Primary Phone</h4>';
            echo '<input type="tel" title="Member primary phone with dashes" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone1" ><br>';
      
            echo '</div>'; 

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Secondary Phone</h4>';
            echo '<input type="tel" title="Member secondary phone with dashes" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone2" ><br>';
          
            echo '</div>'; 

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">SaddleBrooke Street Address</h4>';
            echo '<input type="text" title="Member street address" name="streetaddress" required >';
            echo '</div>'; 

            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">City</h4>';
            echo '<input type="hidden"  name="city" value="Tucson" >';
            // echo '</div>'; 

            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">State</h4>';
            echo '<input type="hidden"  name="state" value="AZ" >';
            // echo '</div>'; 

            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">Zip</h4>';
            echo '<input type="hidden"name="zip"  value="85739">';
            // echo '</div>'; 

            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">HOA</h4>';
            echo '<select name = "hoa">';
            echo '<option value = "1">HOA 1</option>';
            echo '<option value = "2">HOA 2</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Fulltime</h4>';
            echo '<select name = "fulltime">';
            echo '<option value = "1">Fulltime</option>';
            echo '<option value = "0">Gone for the Summer</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Directory List?</h4>';
            echo '<select name = "directorylist">';
            echo '<option value = "1">Yes</option>';
            echo '<option value = "0">Omit from Directory</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Role</h4>';
            echo "<select name = 'role'>";
            echo '<option value = "MEMBER">Normal Member Functions</option>';
            echo '<option value = "ADMIN">Can Update all but Members</option>';
            echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
            echo '<option value = "EVENTADMIN">Can Add/Delete/Email Events, maintain meals</option>';
            echo '<option value = "INSTRUCTOR">Can Maintain Classes</option>';
             echo '<option value = "DJ">Can Email and Report on Events</option>';
            echo '</select>';
            echo '</div>';

            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">Initial Password<br><small><em>Defaulted</em></small></h4>';
            echo "<td><input type='hidden' name='initPass' minlength='8' value='".$passdefault."' required></td>";
            // echo '</div>'; 

            // echo '<div class="form-item">';
            // echo '<h4 class="form-item-title">Retype Password<br><small><em>Defaulted</em></small></h4>';
            echo "<td><input type='hidden' name='initPass2' minlength='8' value='".$passdefault."' required></td>";
            // echo '</div>'; 
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Dietary Restriction<br></h4>';
            echo '<textarea name="dietaryrestriction" cols="50" rows="1" placeholder="Dietary restrictions if known"></textarea>';
            echo '</div>'; 
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Notes<br></h4>';
            echo '<textarea name="notes" cols="50" rows="3" placeholder="Usually Partners name if there is one"></textarea>';
            echo '</div>'; 
         
            echo '</div>'; // end form-grid
            echo '<button type="submit" name="submitAddUser">Add the Member</button><br>';
            echo '</form>'; // end adduser form
            echo '</div>'; // end form container
  
        }

        ?> 
    </section>
    </div>
</body>
</html>


