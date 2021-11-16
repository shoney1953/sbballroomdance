<?php

session_start();
include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
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
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);


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
    <title>SBDC Ballroom Dance Beta - Admin Class Registrations</title>
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
                echo '<th>Registration ID   </th>';    
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$classReg->classid."</td>";
                    echo "<td>".$classReg->classname."</td>";
                    echo "<td>".$classReg->firstname."</td>";
                    echo "<td>".$classReg->lastname."</td>";
                    echo "<td>".$classReg->email."</td>";
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
        echo '<input type="hidden" name="id" value="'.$classReg->id.'">';
        echo '<button type="submit" name="submitUpdateReg">Update the Registration</button><br>';
        echo '</form>';
    
        }
    }

        if ($addReg) {
            echo '<form method="POST" action="addClassReg.php">';
            echo '<label for="classid">Class Id</label>';
            echo '<input type="text" name="classid" required><br>';
            echo '<label for="firstname">First Name</label>';
            echo '<input type="text" name="firstname" required><br>';
            echo '<label for="lastname">Last Name</label>';
            echo '<input type="text" name="lastname" required ><br>';
            echo '<label for="email">Email</label>';
            echo '<input type="text" name="email" required><br>';
        
            echo '<button type="submit" name="submitAddReg">Add the Class</button><br>';
            echo '</form>';
        }     
        if($deleteReg) {
            echo '<p> You have selected to delete class id: '.$classReg->id.'<br>';
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

