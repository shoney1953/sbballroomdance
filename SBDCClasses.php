<?php
$sess = session_start();
require_once 'config/Database.php';

require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';

$upcomingClasses = $_SESSION['upcoming_classes']
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Classes</title>
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

<div class="container-section ">
    <section id="classes" class="content">
   
      <br>
        <h1 class="section-header">Ongoing and Upcoming Classes</h1>
        <div class="form-grid2">
        <?php

        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
            if (isset($_SESSION['role'])) {
        echo '<div class="form-grid-div">';
        echo '<button>';
        echo '<a href="regForClasses.php">Register For Classes</a>';
        echo '</button>';  

    
        echo '</div>';
            }
        } else {
            echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login as a Member or Visitor to Register</a></h4>';
        }
        ?>
        <div class="form-grid-div">
        <form target="_blank" method="POST" action="actions/printClasses.php"> 
        <button type="submit" name="submitPrintClasses">Print Upcoming Classes</button>  
        </form>
        </div>
        </div>

        <table>
        <thead>
            <?php
                $first_class_value = reset($upcomingClasses); // First element's value
                $first_class_year = substr($first_class_value['date'], 0, 4);
                echo '<tr>';
                echo '<th  colspan="12"  ><em>'.$first_class_year.'</em></th>';
                echo '</tr>';
            ?>

            <tr>
            <th>Click for </th>
                <th>Start</th>
                <th>Start</th>
                <th>Class    </th>
                <th>Class</th>
                <th></th>
                <th>Registration</th>

                <!-- <th>Class</th> -->
                <th></th>
               
            </tr>
            <tr>
            <th>Details</th>               
                <th>Date</th>

                <th>Time    </th>
                <th>Name    </th>
                <th>Level    </th>
                <th>Room    </th>
                <th>Email    </th>

                <!-- <th>Limit</th> -->
                <th># Reg </th>
               
            </tr>
            </thead>
            <tbody>
            <?php 
            $classNumber = 0;
            $first_class_value = reset($upcomingClasses); // First element's value
     
            $first_class_year = substr($first_class_value['date'], 0, 4);
         
            foreach ($upcomingClasses as $class) {
     
                $class_year = substr($class['date'],0,4);
                if ($class_year > $first_class_year) {
                    echo '</tbody>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th  colspan="12"  ><em>'.$class_year.'</em></th>';
                    echo '</tr>';
                    echo '<tr>'; 
         
                    $first_class_year = $class_year;
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Click for </th>";
                        echo "<th>Start</th>";
                        echo "<th>Start</th>";
                        echo "<th>Class    </th>";
                        echo "<th>Class</th>";
                        echo "<th></th>";
                        echo "<th>Registration</th>";
    
                        echo "<th></th>";
                       
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th>Details</th>";               
                        echo "<th>Date</th>";
        
                        echo "<th>Time    </th>";
                        echo "<th>Name    </th>";
                        echo "<th>Level    </th>";
                        echo "<th>Room    </th>";
                        echo "<th>Email    </th>";
        
                        echo "<th># Reg </th>";
                       
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                }
                 $classNumber++;
                 echo "<tr>";
                 $hr = 'classMem.php?id=';
                 $hr .= $class["id"];
                 $ad = 'class.php?id=';
                 $ad .= $class["id"];
                 echo '<td><a href="'.$ad.'">'.$class["id"].'</a></td>';
                    echo "<td>". substr($class['date'],5,5)."</td>";

                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    if ($class['classlevel'] === 'Novice') {
                        echo "<td class='table-novice'>".$class['classlevel']."</td>"; 
                    
                    } elseif ($class['classlevel'] === 'Intermediate') {
                        echo "<td class='table-intermediate'>".$class['classlevel']."</td>";
                    } else {
                        echo "<td>".$class['classlevel']."</td>"; 
                    }
                   
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
               

                    // echo "<td>".$class['classlimit']."</td>";
                    echo '<td><a href="'.$hr.'">'.$class["numregistered"].'</a></td>';
                    // echo "<td>".$class['numregistered']."</td>";
         

                  echo "</tr>";
            }
              
            ?> 
            </tbody>
        </table>
        <br>

    </section>
</div>
</div>

   

<?php
  include 'footer.php';
?>
</body>
</html>