<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';

if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}

if(isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif(isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '.$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} 
else {
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI']; 
    $_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
}

$database = new Database();
$db = $database->connect();
$user = new User($db);


$updateUser = false;
$deleteUser = false;
$addUser = false;



if (isset($_POST['userId'])) {
    $userId = htmlentities($_POST['userId']);
    if(isset($_POST['updateUser'])) {$updateUser = $_POST['updateUser'];}
    if(isset($_POST['deleteUser'])) {$deleteUser = $_POST['deleteUser'];}
    if(isset($_POST['addUser'])) {$addUser = $_POST['addUser'];}

    if ($updateUser || $deleteUser) {
        $user->id = $userId;
        $user->read_single();  
    } 

}
if (!isset($_POST['userId'])) {
    if(isset($_POST['addUser'])) {$addUser = $_POST['addUser'];}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance Beta - Admin User Maintenance</title>
</head>
<body>

    <div class="section-back">
    <section id="users" class="container content">
   
      <br>
      <?php 
      if (isset($_POST['submitUser'])) {
        echo '<div class="container-section "> <br><br>';
           
        echo '<section id="seluser" class="content">';
        if ($updateUser || $deleteUser) {

        echo '<h1 class="section-header">Selected User</h1><br>';
        echo '<table>';
        echo '<tr>';
        
                echo '<th>ID   </th>';  
                echo '<th>User Name</th>';
                echo '<th>Role</th>';
                echo '<th>First Name </th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
   
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$user->id."</td>";
                    echo "<td>".$user->username."</td>";
                    echo "<td>".$user->role."</td>";
                    echo "<td>".$user->firstname."</td>";
                    echo "<td>".$user->lastname."</td>";
                    echo "<td>".$user->email."</td>";

                echo "</tr>";

          
        echo '</table><br>';
        }
       if($updateUser) {
        echo '<h1 class="section-header">Update User</h1><br>';
        echo '<div class="form-grid1">';
        echo '<form method="POST" action="updateUser.php">';
        echo '<div class="form-grid-div">';
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
        echo '<label for="role">Role</label><br>';
        echo '<select name="role"  >';
        if ($user->role === "MEMBER") {
            echo '<option value = "MEMBER" selected>Normal Member Functions</option>';
        } else {
            echo '<option value = "MEMBER">Normal Member Functions</option>';
        }
        if ($user->role === "ADMIN") {
            echo '<option value = "ADMIN" selected>Can Update all but Users</option>';
        } else {
            echo '<option value = "ADMIN">Can Update all but Users</option>';
        }
        if ($user->role === "SUPERADMIN") {
            echo '<option value = "SUPERADMIN" selected>Can Update All Tables</option>';
        } else {
            echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
        }

        
        echo '</select><br>';
        echo '<label for="resetPass">Reset Password</label><br>';
        echo '<input type="password" name="resetPass" minlength="8"><br>';
        echo '<label for="resetPass2">Retype Reset Password</label><br>';
        echo '<input type="password" name="resetPass2" minlength="8"><br>';
        echo '<input type="hidden" name="id" value="'.$user->id.'">';
        echo '<input type="hidden" name="username" value="'.$user->username.'">';
        echo '<input type="hidden" name="email" value="'.$user->email.'">';
        echo '<input type="hidden" name="password" value="'.$user->password.'">';
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
             Update the User</button><br>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    
        }
    

        if ($addUser) {

            echo '<h1 class="section-header">Add User</h1><br>';
            echo '<div class="form-grid1">';
            echo '<form method="POST" action="addUser.php">';
            echo '<div class="form-grid-div">';
            echo '<label for="firstname">First Name</label><br>';
            echo '<input type="text" name="firstname"><br>';
            echo '<label for="lastname">Last Name</label><br>';
            echo '<input type="text" name="lastname" ><br>';
            echo '<label for="email">Email -- Must not be a Duplicate</label><br>';
            echo '<input type="email" name="email" ><br>';
            echo '<label for="username">Username -- Must not be a duplicate</label><br>';
            echo '<input type="text" name="username" ><br>';
            echo '<label for="role">Role</label><br>';
            echo '<select name = "role">';
            echo '<option value = "MEMBER">Normal Member Functions</option>';
            echo '<option value = "ADMIN">Can Update all but Users</option>';
            echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
            echo '</select><br>';
            echo '<label for="initPass">Initial Password</label><br>';
            echo '<input type="password" name="initPass" minlength="8"><br>';
            echo '<label for="initPass2">Retype Initial Password</label><br>';
            echo '<input type="password" name="initPass2" minlength="8"><br>';
        
            echo '<label for="hoa">HOA</label><br>';
            echo '<select name = "hoa">';
            echo '<option value = "1">HOA 1</option>';
            echo '<option value = "2">HOA 2</option>';
            echo '</select><br>';
            echo '<label for="phone1">Enter primary phone number:</label><br>';
            echo '<input type="tel" id="phone1" name="phone"
                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                required>';
            echo '<small>Format: 123-456-7890</small><br>';
            echo '<label for="phone2">Enter secondary phone number (Optional):</label><br>';
            echo '<input type="tel" id="phone2" name="phone"
                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                >';
            echo '<small>Format: 123-456-7890</small><br>';
            echo '<label for="streetaddress">Street Address</label><br>';
            echo '<input type="text" name="streetaddress" ><br>';
            echo '<label for="city">City</label><br>';
            echo '<input type="text" name="city" ><br>';
            echo '<label for="state">State</label><br>';
            echo '<input type="text" name="state" maxsize="2"><br>';
            echo '<label for="zip">State</label><br>';
            echo '<input type="text" name="zip" maxsize="10"><br>';
            echo '<p> Notes</p><br>';
            echo '<textarea name="notes" cols="50" rows="5"></textarea><br><br>';
            echo '<br>';
            echo '<button type="submit" name="submitAddUser">
                Add the User</button><br>';
            echo '</div>';
            echo '</form>';
            echo '</div>';
  
        }
           
            
        if($deleteUser) {
            echo '<div class="form-grid1">';
            echo '<p> You have selected to delete user id: '.$user->id.'<br>';
            echo '<p> <strong>NOTE: Deleting a User will also delete all registrations!</strong><br>';
            echo 'First name:  '.$user->firstname.'   Last Name: '.$user->lastname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
        
            echo '<form method="POST" action="deleteUser.php">';
            echo '<input type="hidden" name="id" value="'.$user->id.'">';
       
            echo '<button type="submit" name="submitDeleteUser">Delete the User</button><br>';
            
            echo '</form>';  
            echo '</div>';
        }
        echo '</section>';
        echo '</div>';
    }
        ?> 
    </section>
    </div>
</body>
</html>

