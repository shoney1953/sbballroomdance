<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';
$filter_name = '';
$filter_level = '';
$filter_criteria = '';

if (count($_GET) === 0) {
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$upcomingClasses = $_SESSION['upcoming_classes'];
unset($_SESSION['filtered_classes']);
} else {
  $filter_criteria = '<h4>Results filtered by ';
  if (isset($_GET['name'])) {
    $filter_name = str_replace('%','',$_GET['name']);
    if ($filter_name !== '') {
      $filter_criteria .= 'name: '.$filter_name.' ';   
    }
   
    unset($_GET['name']);
  }
  if (isset($_GET['level'])) {
    $filter_level = str_replace('%','',$_GET['level']);
     
        if ($filter_level !== '') {
           $filter_criteria .= 'level: '.$filter_level.' ';
        }
        
    unset($_GET['level']);
  } 

  if (isset($_SESSION['filtered_classes'])) {
    $upcomingClasses = $_SESSION['filtered_classes'];
  }
}


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
$classLiteral = '';
$classyear = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v4">
    <title>SBDC Ballroom Dance - Upcoming Classes </title>
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
              echo '<h4>If you do not see the action you need to perform on the class, please contact the instructor.</h4><br>';
          }
       echo '<fieldset>';
        echo '<legend>Filter Classes</legend>';

        echo '<form  method="POST" action="actions/searchClasses.php" >';
        echo '<div class="form-grid4">';
        echo '<div>';
        echo '<h4 class="form-item-title">Search Class Name</h4>';
        echo '<input type="search"  placeholder="search class name" name="searchname" ><br>';
        echo '</div>';
         echo '<div>';
        echo '<h4 class="form-item-title">Search Class level</h4>';
        echo '<input type="search"  placeholder="search class level" name="searchlevel" ><br>';
        echo '</div>';
         echo '<button type="submit" name="searchClasses">Filter classes</button>'; 
        echo '</div>';
       
        echo '</form>';
        echo '</fieldset>';
         if ($filter_criteria !== '') {
          if (($filter_name != '') || ($filter_level !== '')) {
              echo $filter_criteria;
          } else {
            $filter_criteria = '';
          }
         
         }
  
        if (!(isset($_SESSION['username']))) {
          echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Click Here Login as a Member or Visitor to Register or Manage Event Registrations</a></h4>';
        }
          echo '<div class="form-grid4">'  ; 
          $classMonth = 0;
          $init = 0;
          $prevClassMonth = 0;    
          $classLiteral = '';
          foreach ($upcomingClasses as $class) {

                $rpChk = "rp".$class['id'];
                $delChk = "del".$class['id'];
                $regChk = "reg".$class['id'];
                 $cd = 'class.php?id=';
                 $cd .= $class["id"];
                 $numActions = 0;
             
                $classMonth = substr($class['date'],5,2);
                $classYear = substr($class['date'],0,4);
             
                if ($init === 0) {
               
                  $prevClassMonth = $classMonth ;
                  echo '</div>';
         
                    echo '<div>';
                   echo '<h4> ------------------   '.$classYear.'  MONTH: '.$classMonth.' ------------------</h4><br>';
                    echo '</div>';
                             echo '<div class="form-grid4">'  ;

                  $init = 1;
                } elseif ($prevClassMonth !== $classMonth) {
                  $prevClassMonth = $classMonth;
           
                    
                  echo '</div>';
                  echo '<h4> ------------------    '.$classYear.' MONTH: '.$classMonth.'  -----------------<h4><br>';
                  echo '<div>';
          
                  echo '</div>';
                     
                    echo '<div class="form-grid4">'  ;
                
                }
                //  echo '<div class="form-container">';
                echo '<fieldset>';
                $classLiteral .= $class['date'].'&nbsp;&nbsp; '.$class['classname'].' &nbsp; &nbsp;'.$class['classlevel'].' &nbsp; &nbsp;'.$class['instructors']   ;

                //  echo "<legend title='Click for complete class description'><a href='".$cd."'>  ".$class['classlevel'].":     ".$class['classname']."      on ".$class['date']."</a></legend>";
                 echo "<legend title='Click for complete class description'><a href='".$cd."'> $classLiteral</a></legend>";
                 $classLiteral = '';
 
                  if  (isset($_SESSION['username']) ) {
                    echo "<h5 class='form-title-left' title='click for pdf report'><form  target='_blank' name='reportClassForm'   method='POST' action='actions/reportClass.php'> ";
                    echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
         
                    echo "<button  type='submit'>Report</button></p>";
                    echo '</form>';
                    }

          

               $gotClassReg = 0;
                if ((isset($_SESSION['username'])) && ($_SESSION['role'] !== 'visitor')) {
                    if ($classReg->read_ByClassIdUser($class['id'],$_SESSION['userid'])) {
                       $gotClassReg = 1;
                
                    } ;
                }
                  if ((isset($_SESSION['username'])) && ($_SESSION['role'] === 'visitor')) {
                    if ($classReg->read_ByClassIdEmail($class['id'],$_SESSION['username'])) {
                       $gotClassReg = 1;
                
                    } ;
                }
                  $gotPartnerClassReg = 0;
                   if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                      if ($partnerClassReg->read_ByClassIdUser($class['id'],$_SESSION['partnerid'])) {  
                
                        $gotPartnerClassReg = 1;
                       
                      }
                    }
                
                   if ($gotClassReg)  {
        
                    echo '<div class="form-item">';
                    echo "<h4 class='form-item-title'>You registered for this class on: ".substr($classReg->dateregistered,0,10)."</h4>";
                    echo '</div>'; // end of form item

                  }  // end got class reg
                   
                    if ($gotPartnerClassReg) {
   
                      echo '<div class="form-item">';
                      echo "<h4 class='form-item-title'>Your partner registered for this class on: ".substr($partnerClassReg->dateregistered,0,10)."</h4>";
                      echo '</div>'; // end of form item 

                    } // got partner

        
                echo "<form name='processClassMem'   method='POST' action='actions/processClassMem.php'> "; 
                echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
     
           
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
                                 echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
                          
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
                    
                       echo '</form>';
                      }
                               
                   
                
         
              // echo "</div>"; // end of form container
              echo '</fieldset>';
           
            // } // end of foreach
         echo '</div>'
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