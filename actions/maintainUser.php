<?php

session_start();
include_once '../config/Database.php';
include_once '../models/User.php';

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
                echo '<th>Member Id    </th>';
   
       
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$user->id."</td>";
                    echo "<td>".$user->username."</td>";
                    echo "<td>".$user->role."</td>";
                    echo "<td>".$user->firstname."</td>";
                    echo "<td>".$user->lastname."</td>";
                    echo "<td>".$user->email."</td>";
                    echo "<td>".$user->memberid."</td>";

                echo "</tr>";

          
        echo '</table><br>';
        }
       if($updateUser) {
        echo '<h1 class="section-header">Update User</h1><br>';
        echo '<div class="form-grid1">';
        echo '<form method="POST" action="updateUser.php">';
        echo '<div class="form-grid-div">';
        echo '<label for="memberid">Memberid Id</label>';
        echo '<input type="text" name="memberid" value="'.$user->memberid.'"><br>';
        echo '<label for="firstname">First Name</label>';
        echo '<input type="text" name="firstname" value="'.$user->firstname.'"><br>';
        echo '<label for="lastnames">Last Name</label>';
        echo '<input type="text" name="lastname" value="'.$user->lastname.'"><br>';
        echo '<label for="newemail">New Email -- Must Not Be a Duplicate</label>';
        echo '<input type="email" name="newemail" value="'.$user->email.'" ><br>';
        echo '<label for="newuser">Username -- Must not be a Duplicate</label>';
        echo '<input type="text" name="newuser" value="'.$user->username.'"><br>';
        echo '<label for="role">Role</label>';
        echo '<select name = "role" value="'.$user->role.'">';
        echo '<option value = "MEMBER">Normal Member Functions</option>';
        echo '<option value = "ADMIN">Can Update all but Users</option>';
        echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
        echo '</select>';
        echo '<label for="resetPass">Reset Password</label>';
        echo '<input type="password" name="resetPass" minlength="8"><br>';
        echo '<label for="resetPass2">Retype Reset Password</label>';
        echo '<input type="password" name="resetPass2" minlength="8"><br>';
        echo '<input type="hidden" name="id" value="'.$user->id.'">';
        echo '<input type="hidden" name="username" value="'.$user->username.'">';
        echo '<input type="hidden" name="email" value="'.$user->email.'">';
        echo '<input type="hidden" name="password" value="'.$user->password.'">';
        echo '<button type="submit" name="submitUpdateUser">Update the User</button><br>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    
        }
    

        if ($addUser) {

            echo '<h1 class="section-header">Add User</h1><br>';
            echo '<div class="form-grid1">';
            echo '<form method="POST" action="addUser.php">';
            echo '<div class="form-grid-div">';
            echo '<label for="firstname">First Name</label>';
            echo '<input type="text" name="firstname"><br>';
            echo '<label for="lastname">Last Name</label>';
            echo '<input type="text" name="lastname" ><br>';
            echo '<label for="email">Email -- Must not be a Duplicate</label>';
            echo '<input type="email" name="email" ><br>';
            echo '<label for="username">Username -- Must not be a duplicate</label>';
            echo '<input type="text" name="username" ><br>';
            echo '<label for="role">Role</label>';
            echo '<select name = "role">';
            echo '<option value = "MEMBER">Normal Member Functions</option>';
            echo '<option value = "ADMIN">Can Update all but Users</option>';
            echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
            echo '</select><br>';
            echo '<label for="initPass">Initial Password</label>';
            echo '<input type="password" name="initPass" minlength="8"><br>';
            echo '<label for="initPass2">Retype Initial Password</label>';
            echo '<input type="password" name="initPass2" minlength="8"><br>';
            echo '<br>';
            echo '<button type="submit" name="submitAddUser">Add the User</button><br>';
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

