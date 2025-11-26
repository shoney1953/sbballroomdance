<?php
session_start();

$allClasses = $_SESSION['allArchClasses'];
$classRegistrations = $_SESSION['archClassRegistrations'];

$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Admin Class</title>
</head>
<body>
<nav class="nav">
    <div class="container">
        
     <ul> 
    <li><a href="administration.php">Back to Admin</a></li>
    <li><a href="archives.php">Back to Archives</a></li>
    <li><a href="index.php">Back to Home</a></li>
     </ul>
    </div>
</nav>

<?php
if (isset($_GET['id'])) {
echo '<div class="container-section ">';
    echo '<section id="classes" class="content">';
    echo '<br><br><h1>Selected Classes</h1>';
        echo '<table>';
            echo '<tr>';
            echo '<th>ID   </th>';   
            echo '<th>Start Date</th>';
            echo '<th>Time    </th>';
            echo '<th>Room    </th>';
            echo '<th>Class    </th>';
            echo '<th>Level    </th>';
            echo '<th>Registration Email </th>';
            echo '<th>Notes</th>';
            echo '<th>Instructors    </th>';
            echo '<th>Class Limit    </th>';
            echo '<th># Attending</th>';
            echo '</tr>';
     
            $classNumber = 0;
            foreach($allClasses as $class) {
                 if ($class["id"] === $_GET['id']) {
                  echo "<tr>";              
                  echo "<td>".$class['id']."</td>";
                  echo "<td>".$class['date']."</td>";
                  echo "<td>".$class['time']."</td>";
                  echo "<td>".$class['room']."</td>";
                  echo "<td>".$class['classname']."</td>";
                  echo "<td>".$class['classlevel']."</td>";
                  echo "<td>".$class['registrationemail']."</td>";
                  echo "<td>".$class['classnotes']."</td>";
                  echo "<td>".$class['instructors']."</td>";
                  echo "<td>".$class['classlimit']."</td>";
                  echo "<td>".$class['numregistered']."</td>";
                  echo "</tr>";
              }
         
        
            }
            echo '</table>';
            echo '<br>';

               
                echo '<table>';
                    echo '<tr>';
                     
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';

                        echo '<th>Date Reg</th> ';    
                        echo '<th>Reg By</th> ';        
                    echo '</tr>';
                    
            
                    foreach($classRegistrations as $classRegistration) {
                  
                         if ($classRegistration['classid'] === $_GET['id']) {
                          echo "<tr>";
                        
                            echo "<td>".$classRegistration['firstname']."</td>";
                            echo "<td>".$classRegistration['lastname']."</td>";
                            echo "<td>".$classRegistration['email']."</td>"; 
                 
       
                            echo "<td>".$classRegistration['dateregistered']."</td>";
                            
                            echo "<td>".$classRegistration['registeredby']."</td>";
                     
                          echo "</tr>";
                      }
                    }
                    
                echo '</table>';
                echo '<br><br>';
            }
            echo '</section>'; 
            echo '</div>'; 
 ?> 

     <footer >
    <?php
    require 'footer.php';
   ?>
</body>
</html>