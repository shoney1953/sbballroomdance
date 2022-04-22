<?php

session_start();
require_once '../config/Database.php';
require_once '../models/DanceClass.php';
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
$database = new Database();
$db = $database->connect();
$class = new DanceClass($db);


$updateClass = false;
$deleteClass = false;
$addClass = false;
$archiveClass = false;
$archMonth = 0;
$classesArch = [];
$num_archClasses = 0;

if (isset($_POST['classId'])) {
    $classId = htmlentities($_POST['classId']);
    if(isset($_POST['updateClass'])) {$updateClass = $_POST['updateClass'];}
    if(isset($_POST['deleteClass'])) {$deleteClass = $_POST['deleteClass'];}
    if(isset($_POST['addClass'])) {$addClass = $_POST['addClass'];}


    if ($updateClass || $deleteClass) {
        $class->id = $classId;
        $class->read_single();  
    } 
    
}
if (!isset($_POST['classId'])) {
    if(isset($_POST['addClass'])) {$addClass = $_POST['addClass'];}
    /* selected to archive classes */
}
if(isset($_POST['archiveClass'])) {

    $archiveClass = $_POST['archiveClass'];
     if (isset($_POST['archMonth'])) {
       $archMonth = $_POST['archMonth'];
       if ($archMonth < 10) {
           $archMonth = '0'.$archMonth;
       }
       $archDate = date("Y")."-".$archMonth."-01";

       $result = $class->read_ByArchDate($archDate);
       $rowCount = $result->rowCount();

       $num_archClasses = $rowCount;

           if ($rowCount > 0) {

               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                   extract($row);
                   $class_item = array(
                       'id' => $id,
                       'classname' => $classname,
                       'classlevel' => $classlevel,
                       'classlimit' => $classlimit,
                       'date' => $date,
                       'time' => $time,
                       'instructors' => $instructors,
                       "registrationemail" => $registrationemail,
                       "room" => $room,
                       "classnotes" => $classnotes,
                       'numregistered' => $numregistered
                   );
                   array_push($classesArch, $class_item);

               }

           $_SESSION['classesArch'] = $classesArch;
           } 

     }
 }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Class Administration</title>
</head>
<body>

    <div class="section-back container content-left">
  
   
      <br>
      <?php 
        if ($deleteClass) {
        echo '<h1 class="section-header">Selected Class</h1><br>';
        echo '<table>';
        echo '<tr>';
                echo '<th>Date    </th>';
                echo '<th>Time    </th>';
                echo '<th>Class    </th>';
                echo '<th>Level    </th>';
                echo '<th>Registration Email </th>';
                echo '<th>Instructors    </th>';
                echo '<th>Class Limit    </th>';
                echo '<th># Registered </th>';
                echo '<th>Room    </th>';
                echo '<th>ID   </th>';    
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$class->date."</td>";
                    echo "<td>".$class->time."</td>";
                    echo "<td>".$class->classname."</td>";
                    echo "<td>".$class->classlevel."</td>";
                    echo "<td>".$class->registrationemail."</td>";
                    echo "<td>".$class->instructors."</td>";
                    echo "<td>".$class->classlimit."</td>";
                    echo "<td>".$class->numregistered."</td>";
                    echo "<td>".$class->room."</td>";
                    echo "<td>".$class->id."</td>";
                echo "</tr>";

          
        echo '</table><br>';
        }
       if($updateClass) {
    
        echo '<div class="form-left content-left">';
        echo '<form method="POST" action="updateClass.php">';
        echo '<h3 class="heading-left">Update Class</h3>';
        echo '<label for="classname">Class Name</label><br>';
        echo '<input type="text" name="classname" value="'.$class->classname.'"><br>';
        echo '<label for="classlevel">Class Level</label><br>';
        echo '<select name = "classlevel" ><br>';
        if ($class->classlevel == 'Beginner') {
            echo '<option value = "Beginner" selected>Beginner </option>';
        } else {
            echo '<option value = "Beginner">Beginner </option>';
        }
        if ($class->classlevel == "Intermediate") {
            echo '<option value = "Intermediate" selected>Intermediate</option>';
        } else {
            echo '<option value = "Intermediate">Intermediate</option>';  
        }
        if ($class->classlevel == "Advanced") {
            echo '<option value = "Advanced" selected>Advanced</option>';
        } else {
            echo '<option value = "Advanced">Advanced</option>';
        }
        echo '</select><br>';
        echo '<label for="instructors">Instructors</label><br>';
        echo '<input type="text" name="instructors" value="'.$class->instructors.'"><br>';
        echo '<label for="registrationemail">Registration Email</label><br>';
        echo '<input type="text" name="registrationemail" value="'.$class->registrationemail.'"><br>';
        echo '<label for="room">Room</label><br>';
        echo '<input type="text" name="room" value="'.$class->room.'"><br>';
        echo '<label for="date">Start Date</label><br>';
        echo '<input type="date" name="date" value="'.$class->date.'"><br>';
        echo '<label for="time">Time</label><br>';
        echo '<input type="time" name="time" value="'.$class->time.'"><br>';
        echo '<label for="classlimit">Class Limit</label><br>';
        echo '<input type="number" name="classlimit" value="'.$class->classlimit.'"><br>';
        echo '<label for="numregistered"># Registered</label><br>';
        echo '<p> Notes</p><br>';
        echo '<textarea name="classnotes" cols="50" rows="5" 
            >'.$class->classnotes.'</textarea><br><br>';
        echo '<input type="number" name="numregistered" value="'.$class->numregistered.'"><br>';
        echo '<input type="hidden" name="id" value="'.$class->id.'">';
        echo '<button type="submit" name="submitUpdate">Update the Class</button><br>';
        echo '</form>';
        echo '</div>';
        }
    

        if ($addClass) {
            
            echo '<div class="form-left content-left">';
            echo '<form method="POST" action="addClass.php">';
            echo '<h1 class="heading-left">Add a New Class</h1><br>';
            echo '<label for="classname">Class Name</label><br>';
            echo '<input type="text" name="classname" required ><br>';
            echo '<label for="classlevel">Class Level</label><br>';
            echo '<select name = "classlevel"> <br>';
            echo '<option value = "Beginner">Beginner </option>';
            echo '<option value = "Intermediate">Intermediate</option>';
            echo '<option value = "Advanced">Advanced</option>';
            echo '</select><br>';
            echo '<label for="instructors" required>Instructors</label><br>';
            echo '<input type="text" name="instructors" ><br>';
            echo '<label for="registrationemail">Registration Email</label><br>';
            echo '<input type="email" name="registrationemail" required><br>';
            echo '<label for="room">Room</label><br>';
            echo '<input type="text" name="room" required><br>';
            echo '<label for="date">Start Date</label><br>';
            echo '<input type="date" name="date" ><br>';
            echo '<label for="time">Time</label><br>';
            echo '<input type="time" name="time" ><br>';
            echo '<label for="classlimit" >Class Limit</label><br>';
            echo '<input type="number" name="classlimit" value="30"><br>';
            echo '<p> Notes</p><br>';
            echo '<textarea name="classnotes" cols="50" rows="5"></textarea><br><br>';

            echo '<button type="submit" name="submitAdd">Add the Class</button><br>';
            echo '</form>';
            echo '</div>';
        }     
        if($deleteClass) {
           
            echo '<p> You have selected to delete class id: '.$class->id.'<br>';
            echo 'Class name:  '.$class->classname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteClass.php">';
            echo '<input type="hidden" name="id" value="'.$class->id.'">';
            echo '<button type="submit" name="submitDelete">Delete the Class</button><br>';
            echo '</form>';
          
        }
        if($archiveClass) {
            echo '<h3> You have selected to archive the following classes and their registrations: </h3><br>';
            echo '<table>';
            echo '<tr>';
                    echo '<th>Date    </th>';
                    echo '<th>Time    </th>';
                    echo '<th>Class    </th>';
                    echo '<th>Level    </th>';
                    echo '<th>Registration Email </th>';
                    echo '<th>Instructors    </th>';
                    echo '<th>Class Limit    </th>';
                    echo '<th># Registered </th>';
                    echo '<th>Room    </th>';
                    echo '<th>ID   </th>';    
                echo '</tr>';

            foreach($classesArch as $class) {
                echo "<tr>";
                echo "<td>".$class['date']."</td>";
                echo "<td>".$class['time']."</td>";
                echo "<td>".$class['classname']."</td>";
                echo "<td>".$class['classlevel']."</td>";
                echo "<td>".$class['registrationemail']."</td>";
                echo "<td>".$class['instructors']."</td>";
                echo "<td>".$class['classlimit']."</td>";
                echo "<td>".$class['numregistered']."</td>";
                echo "<td>".$class['room']."</td>";
                echo "<td>".$class['id']."</td>";
            echo "</tr>";
            }
            echo '</table><br>';
            echo '<form method="POST" action="archiveClass.php">';
            echo '<button type="submit" name="submitArchive">Archive these Class(es) and their registrations</button><br>';
            echo '</form>';
        }
        ?> 
   
    </div>
</body>
</html>

