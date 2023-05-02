<?php
  session_start();
  require_once 'config/Database.php';
  require_once 'models/DanceClass.php';
  require_once 'models/ClassRegistration.php';
  require_once 'models/User.php';
  $classes = $_SESSION['classes'];
  $upcomingClasses = $_SESSION['upcoming_classes'];
  
  $classNumber = $_SESSION['numupcomingclasses'];
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Register for Events</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <br><br>
    <div class="container-section ">
    <section id="registerevent" class="content">  

        <h1 class="section-header">Register for Classes</h1>

        <?php
   
        if ($classNumber > 0) {
        $partner = new User($db);

        if (isset($_SESSION['username'])) {
     
            echo '<h4> This process generates an email to confirm your registration, so it takes a while. Please be patient.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.</h4>';
            echo '<div class="form-grid3">';
            echo '<div class="form-grid-div">  <br>';
            echo '<form method="POST"  action="actions/regClass.php" target="_blank">';

        if (isset($_SESSION['role'])) {
          if ($_SESSION['role'] === 'visitor') {
      
            if (isset($_SESSION['visitorfirstname'])) {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['visitorfirstname'].'"><br>';
            } else {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" ><br>';
             }
            if (isset($_SESSION['visitorlastname'])) {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['visitorlastname'].'"><br>';
            } else {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" ><br>';
            }
            if (isset($_SESSION['useremail'])) {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
            } else {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" ><br><br>';
            }



          } else {
            if (isset($_SESSION['userfirstname'])) {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'"><br>';
            } else {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" ><br>';
            }
            if (isset($_SESSION['userlastname'])) {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'"><br>';
            } else {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" ><br>';
            }
            if (isset($_SESSION['useremail'])) {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
            } else {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" ><br><br>';
            }
            if (isset($_SESSION['partnerid'])) {
                $partner->id = $_SESSION['partnerid'];
                $partner->read_single();
            
            }
       
        }
           
      }
    
      
         echo' </div>';
        echo '<div class="form-grid-div"> <br>';
     
        if (isset($_SESSION['partnerid'])) {
    
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'"><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" value="'.$partner->email.'"><br> <br>';

           } else {
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" ><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" ><br> <br>';
           }

        echo '</div>';  
      
    
        echo '</div>';    
        echo '<div class="form-grid">';
        echo '<div class="form-grid-div">';
        echo "<label for='message2ins'>Message to Instructor(s)</label><br>";
        echo "<textarea name='message2ins' cols='35' rows='2'></textarea><br><br>";
    
         
            echo '<h4><em>
              To Register -- Please select One or More of the Classes Listed along with associated information. <br>Then click on the Submit Registration(s) Button.</em></h4><br>';
            
              echo '<table>';
              echo '<thead>';
              echo '<tr>';
              echo '<th>Check <br> to <br> Register</th>';
              echo '<th>Class</th>';
              echo '<th>Start Date</th>';
              echo '<th>Level</th>';
              echo '<th>Instructors</th>';
              echo '<th>Notes</th>';
              echo '</tr>'; 
              echo '</thead>';
              echo '<tbody>';
        foreach ($upcomingClasses as $class) {
             
                echo '<tr>';
               echo '<td>';
               $chkboxID = "cb".$class['id'];
               echo "<input type='checkbox' name='$chkboxID'";
               echo '</td>';
               echo '<td>';
               echo $class['classname'];
               echo '</td>';
               echo '<td>';
               echo $class['date'];
               echo '</td>';
               echo '<td>';
               echo $class['classlevel'];
               echo '</td>';
               echo '<td>';
               echo $class['instructors'];
               echo '</td>';
               echo '<td>';
               echo $class['classnotes'];
               echo '</td>';
      
           
          echo "</tr>";

        }
            echo '</tbody>';
            echo '</table>';

            echo '<button name="submitRegClass" type="submit">Submit Registration(s)</button><br>';
            echo '</div>';     
            echo '</form>';
    
        } 
      }
        ?>
    </section>
    </div>
    
</body>
</html>