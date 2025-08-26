<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';
$upcomingClasses = $_SESSION['upcoming_classes'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$partnerClassReg = new ClassRegistration($db);
$classInst = new DanceClass($db);
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$numActions = 0;
$gotClassReg = 0;
$gotPartnerClassReg = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Events Test Mode</title>
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

    <div class="container-section">
    <section id="classes" class="content">
      <br><br>
      <h1>Upcoming Classes</h1>
      <h4>You can click on the Class Name to get complete details on the Class.</h4>
      
        <?php 
        if (isset($_SESSION['username'])) {
              echo '<h4>If you do not see the action you need perform on the class, please contact the instructor.</h4>';
          }
        if (!(isset($_SESSION['username']))) {
          echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Click Here Login as a Member or Visitor to Register or Manage Event Registrations</a></h4>';
        }
                 
               
          foreach ($upcomingClasses as $class) {

                $rpChk = "rp".$class['id'];
                $delChk = "del".$class['id'];
                $regChk = "reg".$class['id'];
                 $cd = 'class.php?id=';
                 $cd .= $class["id"];
                 $numActions = 0;

                 echo '<div class="form-container">';
                 echo "<h4 class='form-title-left' title='Click for complete class description'><a href='".$cd."'>".$class['classlevel'].": ".$class['classname']." on ".$class['date']."</a></h4>";
 
                  if (isset($_SESSION['username'])) {
                    echo "<p><form  target='_blank' name='reportClassForm'   method='POST' action='actions/reportClass.php'> ";
                    echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
                    echo "<button class='button-tiny' type='submit'>Report</button></p>";
                    echo '</form>';
                    }

                echo '<div class="form-grid">';

               $gotClassReg = 0;
                if (isset($_SESSION['username'])) {
                    if ($classReg->read_ByClassIdUser($class['id'],$_SESSION['userid'])) {
                       $gotClassReg = 1;
                
                    } ;
                  
                  $gotPartnerClassReg = 0;
                   if (isset($_SESSION['partnerid'])) {
                      if ($partnerClassReg->read_ByClassIdUser($class['id'],$_SESSION['partnerid'])) {  
                
                        $gotPartnerClassReg = 1;
                       
                      }
                    }
        
                   if ($gotClassReg)  {
        
                    echo '<div class="form-item">';
                    echo "<h4 class='form-item-title'>You registered for this class on: <br> ".substr($classReg->dateregistered,0,10)."</h4>";
                    echo '</div>'; // end of form item

                  }  // end got class reg
                   
                    if ($gotPartnerClassReg) {
   
                      echo '<div class="form-item">';
                      echo "<h4 class='form-item-title'>Your partner registered for this class on: <br> ".substr($partnerClassReg->dateregistered,0,10)."</h4>";
                      echo '</div>'; // end of form item 

                    } // got partner
                  
     
                   echo "</div>"; // end of form grid
      
                echo "<form name='processClassMem'   method='POST' action='actions/processClassMem.php'> "; 
                echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
                echo '<div class="form-grid">';

                     if (($gotClassReg) || ($gotPartnerClassReg)) {  

                          if ($gotClassReg) {
                            echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Remove Registrations</h4>';
                            echo "<input type='checkbox' title='Select to Remove Registrations(s)' name='".$delChk."'>";   
                            echo '</div>';
                              $numActions++;
                           } else {
                              if ($gotPartnerClassReg) {
                                  echo '<div class="form-item">';
                                  echo '<h4 class="form-item-title">Remove Registrations</h4>';
                                  echo "<input type='checkbox' title='Select to Remove Registrations(s)' name='".$delChk."'>";   
                                  echo '</div>';
                                  $numActions++;

                              }  // got partner
                      
                            } // got classreg

                            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] > 0) && (!($gotPartnerClassReg ))) {
                        
                                  echo '<div class="form-item">';
                                  echo '<h4 class="form-item-title">Register?</h4>';
                          
                                    echo "<input type='checkbox' title='Select to register for this class' name='".$regChk."'>";   
                                  echo '</div>';
                                    $numActions++;
                           }
                            if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] > 0) && ($gotPartnerClassReg)) {
                              if (!($gotClassReg)) {
                    
                                echo '<div class="form-item">';
                                echo '<h4 class="form-item-title">Register?</h4>';
                                echo "<input type='checkbox' title='Select to register for this class' name='".$regChk."'>";   
                     
                                echo '</div>';
                                  $numActions++;                          
                            }
                           }
                            //  else below goes to registered
                          }  else {

                            echo '<div class="form-item">';
                            echo '<h4 class="form-item-title">Register?</h4>';
                            echo "<input type='checkbox' title='Select to register for this class' name='".$regChk."'>";   
                         
                            echo '</div>';
                              $numActions++;

                      
                      }  // registered
                        if ($numActions > 0) {
                              echo '<div class="form-item">';
                               echo "<button type='submit' name='subMemClass'>Process</button>";
                               echo '</div>';
                        }
                    

                      }
                       echo '</div>';                     
                       echo '</form>';
                
         
              echo "</div>"; // end of form container
                
            } // end of foreach
 
        ?>

    <br>
    </section>
      </div>
</div>
<?php
  include 'footer.php';
?>
</body>
</html>