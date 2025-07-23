<?php
session_start();

$allClasses = $_SESSION['allClasses'];


$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
// if (!isset($_SESSION['username'])) {
//     $redirect = "Location: ".$_SESSION['homeurl'];
//     header($redirect);
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Class Dates</title>
</head>
<body>
<nav class="nav">
    <div class="container">
        
     <ul> 
    <li><a href="index.php">Back to Home</a></li>
     </ul>
    </div>
</nav>

<?php
   echo "<div class='container'>";
if (isset($_GET['id'])) {
echo '<div class="container-section ">';
    echo '<section id="classes" class="content">';
    echo '<br><br><h1>Class Details</h1>';

        echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Name    </th>';
            echo '<th>Level    </th>';
            echo '<th>Instructors    </th>';
            echo '<th>Registration Email </th>';
            echo '<th>Time    </th>';
            echo '<th>Room    </th>';
            echo '<th>Notes</th>';
            echo '<th>Class Limit    </th>';
            echo '<th># Attending</th>';
            echo '</tr>';
            echo '</thead>';
          
            $classNumber = 0;
            foreach($allClasses as $class) {
                 if ($class["id"] === $_GET['id']) {
                    echo '<tbody>';
                  echo "<tr>";              
            
                  echo "<td>".$class['classname']."</td>";
                  echo "<td>".$class['classlevel']."</td>";
                  echo "<td>".$class['instructors']."</td>";
                  echo "<td>".$class['registrationemail']."</td>";
                  echo "<td>".$class['time']."</td>";
                  echo "<td>".$class['room']."</td>";

                  echo "<td>".$class['classnotes']."</td>";

                  echo "<td>".$class['classlimit']."</td>";
                  $hr = 'classMem.php?id=';
                 $hr .= $class["id"];

                 echo '<td><a href="'.$hr.'">'.$class["numregistered"].'</a></td>';
        
                //   echo "<td>".$class['numregistered']."</td>";
                  echo "</tr>";
                  echo "</tbody>";
            
                  echo "<thead>";
                  echo "<tr>";  
                  echo '<th>Start Date</th>';
                  echo '<th>Date 2</th>';
                  echo '<th>Date 3</th>';
                  echo '<th>Date 4</th>';
                  echo '<th>Date 5</th>';
                  echo '<th>Date 6</th>';
                  echo '<th>Date 7</th>';
                  echo '<th>Date 8</th>';
                  echo '<th>Date 9</th>';
                  echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  echo "<tr>";              
       
                  echo "<td>".$class['date']."</td>";
                  echo "<td>".$class['date2']."</td>";
                  echo "<td>".$class['date3']."</td>";
                  echo "<td>".$class['date4']."</td>";
                  echo "<td>".$class['date5']."</td>";
                  echo "<td>".$class['date6']."</td>";
                  echo "<td>".$class['date7']."</td>";
                  echo "<td>".$class['date8']."</td>";
                  echo "<td>".$class['date9']."</td>";
                  echo "</tr>";
                  echo "</tbody>";
           
                 }
                  
              }
 
            echo '</table>';
            echo '<br>';

 
                echo '<br><br>';
        
            echo '</div>'; 
            echo '</section>'; 
        }
        require 'footer.php';
        echo "</div>";

 
?>
</body>
</html>