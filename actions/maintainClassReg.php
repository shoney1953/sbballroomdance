<?php
session_start();
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$upcomingClasses = [];
$upcomingClasses = $_SESSION['upcoming_classes'] ;
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$user = new User($db);
$users = [];
$_SESSION['regUsers'] = [];
$num_users = 0;

        $result = $user->read();
        
        $rowCount = $result->rowCount();
        $num_users = $rowCount;
        if($rowCount > 0) {
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    'id' => $id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'partnerId' => $partnerid
                    
                );
                array_push( $users, $user_item);
        
            }
            
        }
    
$_SESSION['regUsers'] = $users;
$updateReg = false;
$deleteReg = false;
$addReg = false;



if (isset($_POST['regId'])) {
    $regId = htmlentities($_POST['regId']);
    if(isset($_POST['updateReg'])) {$updateReg = $_POST['updateReg'];}
    if(isset($_POST['deleteReg'])) {$deleteReg = $_POST['deleteReg'];}
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}

    if ($updateReg || $deleteReg) {
        $classReg->id = $regId;
        $classReg->read_single();  
    } 

}
if (!isset($_POST['regId'])) {
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Class Registration Administration</title>
</head>
<body>

    <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
      <?php 
        if ($updateReg || $deleteReg) {
        echo '<h1 class="section-header">Selected Registration</h1><br>';
        echo '<table>';
        echo '<tr>';
        
                echo '<th>Class Id    </th>';
                echo '<th>Class Name</th>';
                echo '<th>First Name </th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Userid</th>';
                echo '<th>Registration ID   </th>';    
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$classReg->classid."</td>";
                    echo "<td>".$classReg->classname."</td>";
                    echo "<td>".$classReg->firstname."</td>";
                    echo "<td>".$classReg->lastname."</td>";
                    echo "<td>".$classReg->email."</td>";
                    echo "<td>".$classReg->userid."</td>";
                    echo "<td>".$classReg->id."</td>";
                echo "</tr>";

          
        echo '</table><br>';

       if($updateReg) {
        echo '<form method="POST" action="updateClassReg.php">';
        echo '<label for="classid">Class Id</label>';
        echo '<input type="text" name="classid" value="'.$classReg->classid.'"><br>';
        echo '<label for="firstname">First Name</label>';
        echo '<input type="text" name="firstname" value="'.$classReg->firstname.'"><br>';
        echo '<label for="lastnames">Last Name</label>';
        echo '<input type="text" name="lastname" value="'.$classReg->lastname.'"><br>';
        echo '<label for="email">Email</label>';
        echo '<input type="text" name="email" value="'.$classReg->email.'"><br>';
        echo '<label for="userid">Userid</label>';
        echo '<input type="text" name="userid" value="'.$classReg->userid.'"><br>';
        echo '<input type="hidden" name="id" value="'.$classReg->id.'">';
        echo '<button type="submit" name="submitUpdateReg">Update the Registration</button><br>';
        echo '</form>';
    
        }
    }

        if ($addReg) {
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="addClassReg.php">';
            echo '<ul class="list-box">';
            echo '<h4 style="text-decoration: underline;color: black"><em>
              To Enroll -- Please select One or More of the Classes Listed</em></h4><br>';
        foreach ($upcomingClasses as $class) {
                echo '<li class="list-none">';
                $chkboxID = "cb".$class['id'];
                $classString = " ".$class['classname']." ".$class['classlevel']." ".$class['date']." ";
                echo "<input type='checkbox' name='$chkboxID'>";
                echo "<label for='$chkboxID'> Select Class:
                    <strong>$classString </strong></label><br>";
                echo '</li>';
        }
           
            
            echo '</ul> <br><br>';
            echo '<ul class="list-box">';
            echo '<h4 style="text-decoration: underline;color: black"><em>
             Select one or more of the members Listed</em></h4><br>';
             foreach ($users as $usr) {
                echo '<li lass="list-none">';
                $usrID = "us".$usr['id'];
                $userString = " ".$usr['firstname']." ".$usr['lastname']." ".$usr['email']." ";
                echo "<input type='checkbox' name='$usrID'>";
                echo "<label for='$usrID'>$userString </label><br>";
             echo '</li>';
             }
             echo '</ul>';
   
            echo '<button type="submit" name="submitAddReg">Add the Registration(s)</button><br>';
            echo '</form>';
        }     
        if($deleteReg) {
            echo '<p> You have selected to delete class registration id: '.$classReg->id.'<br>';
            echo 'First name:  '.$classReg->firstname.' Last Name '.$classReg->lastname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteClassReg.php">';
            echo '<input type="hidden" name="id" value="'.$classReg->id.'">';
            echo '<input type="hidden" name="classid" value="'.$classReg->classid.'">';
            echo '<button type="submit" name="submitDeleteReg">Delete the Registration</button><br>';
            echo '</form>';
        }
        ?> 
    </section>
    </div>
</body>
</html>

