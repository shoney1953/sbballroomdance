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

        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
     
            echo '<h4> This process generates an email to confirm your registration, so it takes a while. Please be patient.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.</h4>';
        
            
            echo '<form method="POST"  action="actions/regClass.php" target="_blank">';
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'visitor') {
                 echo '<div class="form-container">';
                 echo '<h4 class="form-title">Visitor Registration</h4>';
            
                 echo '<div class="form-grid">';
                if (isset($_SESSION['visitorfirstname'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Visitor First Name</h4>';
                    echo '<input type="text" name="regFirstName1" value="'.$_SESSION['visitorfirstname'].'">';
                    echo '</div>';
                } else { 
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title">Visitor First Name</h4>';       
                        echo '<input type="text" name="regFirstName1" >';
                        echo '</div>';
                }
              
                  if (isset($_SESSION['visitorlastname'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Visitor Last Name</h4>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['visitorlastname'].'">';
                    echo '</div>';
                } else {
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title"?Visitor Last Name</h4>';
                        echo '<input type="text" name="regLastName1" >';
                        echo '</div>';
                 }
            
                  if (isset($_SESSION['useremail'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Visitor Email</h4>';
                    echo '<input type="text" name="regEmail1" value="'.$_SESSION['useremail'].'">';
                    echo '</div>';
                } else {
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title">Visitor Email</h4>';
                        echo '<input type="text" name="regEmail1" >';
                        echo '</div>';
                 }
                 echo '</div>'; // end form grid
                 echo '</div>'; // end form container

                } //end visitor 
      
                if ($_SESSION['role'] != 'visitor') {
      
                  if (isset($_SESSION['partnerid'])) {
                    $partner->id = $_SESSION['partnerid'];
                    $partner->read_single();
                   }
                   echo '<div class="form-container">';
                   echo '<h4 class="form-title">Member(s) Registration</h4>';
                  echo '<div class="form-grid">';
                  if (isset($_SESSION['userfirstname'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Member First Name</h4>';
                    echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'">';
                    echo '</div>';
                  } else {
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title">Member First Name</h4>';
                        echo '<input type="text" name="regFirstName1" >';
                        echo '</div>';
                   }

                  if (isset($_SESSION['userlastname'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Member Last Name</h4>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'">';
                    echo '</div>';
                } else {
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title">Member Last Name</h4>';
                        echo '<input type="text" name="regLastName1" >';
                        echo '</div>';
                       
                 }

                  if (isset($_SESSION['useremail'])) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Member Email</h4>';
                    echo '<input type="text" name="regEmail1" value="'.$_SESSION['useremail'].'">';
                    echo '</div>';
                } else {
                        echo '<div class="form-item">';
                        echo '<h4 class="form-item-title">Member Email</h4>';
                        echo '<input type="text" name="regEmail1" >';
                        echo '</div>';
                        
                 }
                
                 if (isset($_SESSION['partnerid'])) {
                  if ($_SESSION['partnerid'] > 0) {
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Partner First Name</h4>';
                    echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'">';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Partner Last Name</h4>';
                    echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'">';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Partner Email</h4>';
                    echo '<input type="text" name="regEmail2" value="'.$partner->email.'">';
                    echo '</div>';
                    echo '<div class="form-item">';
                    echo '<h4 class="form-item-title">Message To Instructor</h4>';
                    echo "<textarea name='message2ins' cols='100' rows='4'></textarea><br><br>";
                    echo '</div>';
                 }
                }
                echo '</div>';
                echo '</div>';
            }
 

        echo '<h4 class="form-title"><em>
        To Register -- Please select a class, click on Register, and then Submit Registration Or<br> Click on multiple classes and then scroll to the bottom and click on the Submit Registration(s) Button.</em></h4>';
          
             
        foreach ($upcomingClasses as $class) {
             echo '<div class="form-container">';
            
             echo '<div class="form-grid">';


             echo '<div class="form-item">';
             echo '<h4 class="form-item-title-emp">Click To Register</h4>'; 
               $chkboxID = "cb".$class['id'];
               echo "<input type='checkbox' name='".$chkboxID."'>";
              echo '</div>';

              echo '<div class="form-item">';
              echo '<h4 class="form-item-title">Class Name</h4>'; 
               echo $class['classname'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Start Date</h4>'; 
               echo $class['date'];
               echo '</div>';
              if ($class['classlevel'] === 'Novice') {
                echo '<div class="form-item table-novice">';
                echo '<h4 class="form-item-title">Class Level</h4>'; 
                echo $class['classlevel'];
                echo '</div>';
              } elseif (($class['classlevel'] === 'Intermediate')) {
                echo '<div class="form-item table-intermediate">';
                echo '<h4 class="form-item-title">Class Level</h4>'; 
                echo $class['classlevel'];
                echo '</div>';
              }
              else {
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Class Level</h4>'; 
                echo $class['classlevel'];
                echo '</div>';
              }
        

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Class Instructors</h4>'; 
               echo $class['instructors'];
               echo '</div>';

               echo '<div class="form-item">';
               echo '<h4 class="form-item-title">Class Notes</h4>'; 
               echo $class['classnotes'];
               echo '</div>';
              echo '<div class="form-item">';
              echo '<button name="submitRegClass" type="submit">Submit Registration</button><br>';
              echo '</div>';
               echo '</div>';
               echo '</div>'; 
    

        }


            echo '<button name="submitRegClass" type="submit">Submit Registration(s)</button><br>';
         
            echo '</form>';
    
        } 
      }
    }

        ?>
    </section>
    </div>
<?php
  require 'footer.php';
?>   
</body>
</html>