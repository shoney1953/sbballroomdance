<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Contact.php';
require_once 'models/Visitor.php';
require_once 'models/ClassRegistration.php';
require_once 'models/DanceClass.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];

$allClasses = [];
$classRegistrations = [];
$num_registrations = 0;
$num_classes = 0;
$_POST = array();
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 
$current_month = date('m');
$current_year = date('Y');
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');

$rpChk = '';
$upChk = '';
$dlChk = '';
$emChk = '';
$dpChk = '';
$arChk = '';
$num_users = 0;
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
$database = new Database();
$db = $database->connect();
/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();

$num_classes = $rowCount;
$_SESSION['allClasses'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $class_item = array(
            'id' => $id,
            'classname' => $classname,
            'classlevel' => $classlevel,
            'classlimit' => $classlimit,
            'date' => $date,
            'time' => date('h:i:s A', strtotime($time)),
            'time2' => $time,
            'instructors' => $instructors,
            'classnotes' => $classnotes,
            "registrationemail" => $registrationemail,
            "room" => $room,
            'numregistered' => $numregistered,
            'date2' => $date2,
            'date3' => $date3,
            'date4' => $date4,
            'date5' => $date5,
            'date6' => $date6,
            'date7' => $date7,
            'date8' => $date8,
            'date9' => $date9
            
        );
        array_push($allClasses, $class_item);

    }
    $_SESSION['allClasses'] = $allClasses;
} 
/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['classregistrations'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classdate' => $classdate,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'userid' => $userid,
            'email' => $email,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
      
        array_push($classRegistrations, $reg_item);
  
    }
  
    $_SESSION['classregistrations'] = $classRegistrations;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Class Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="administration.php">Back to Administration</a></li>
        <li><a title="List Historical Data" href="SBDCAAClasses.php">Archived Classes</a></li>
      </ul>
    </div>

</nav>

 
    <div class="container-section ">
    
    <section id="classes" class="content">
      <br><br>
   <?php
   
           
            echo '<h3 class="form-title">Maintain Classes</h3>';
            echo '<h4> Please check only 1 action per class.</h4>';
            echo '<form method="POST" action="actions/processClasses.php">';
      
            foreach($allClasses as $class) {
                $rpChk = "rp".$class['id'];
                $cvChk = "cv".$class['id'];
                $upChk = "up".$class['id'];
                $dlChk = "dl".$class['id'];
                $emChk = "em".$class['id'];
                $aeChk = "ae".$class['id'];
                $dpChk = "dp".$class['id'];
                $arChk = "ar".$class['id'];
                $drChk = "dr".$class['id'];
                $urChk = "ur".$class['id'];
       
         
                $mbSrch = "srch".$class['id'];
                $class_month = substr($class['date'], 5, 2);
                $class_year = substr($class['date'], 0, 4);
                $showReg = 0;
                // echo "<div class='form-container'>";
                echo '<fieldset>';
                echo '<legend>'.$class['classname'].' &nbsp;&nbsp; '.$class['classlevel'].'&nbsp;&nbsp; '.$class['date'].'</legend>';
                echo "<div class='form-grid'>";
                // echo "<h4 class='form-title'>Name: ".$class['classname']."</h4>";
                // echo "<h4 class='form-title'>Level: ".$class['classlevel']."</h4>";
                // echo "<h4 class='form-title'>Start Date: ".$class['date']."</h4>";
                $hr = 'classMem.php?id=';
                $hr .= $class["id"];
              
                echo "<h4 class='form-title' title='click to see registrants'>
                       Number Registered: <a href='".$hr."'>".$class['numregistered']."</a></h4>";
                
                echo "</div>"; // end of form grid
                // echo '<hr>';
                // echo '<h4 class="form-title form-division">Class Level Actions</h4>';
                
                echo "<div class='form-grid'>";

                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Report?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Report' name='".$rpChk."'>";
                echo "</div>";

                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Create CSV file?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Report' name='".$cvChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Email?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Email' name='".$emChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Duplicate?</h4>";
                echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Update?</h4>";
                echo "<input type='checkbox' title='Select to Update Classes(s)' name='".$upChk."'>";  
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Delete?</h4>";
                echo "<input type='checkbox' title='Select to Delete Classes(s)' name='".$dlChk."'>";
                echo "</div>";
                if ($_SESSION['role'] === 'SUPERADMIN') {
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Archive?</h4>";
                    echo "<input type='checkbox' title='Select to Archive Classes' name='".$aeChk."'>";
                    echo "</div>";
                }
                echo "</div>"; // end of form grid
          
                $class_month = substr($class['date'], 5, 2);
                $class_year = substr($class['date'], 0, 4);
                // if ($compareDate <= $class['date']) {
                if (($current_month <= $class_month ) || ($current_year < $class_year)){
                    // echo '<h4 class="form-title form-division">Class Registration Actions</h4>';
                    echo "<div class='form-grid'>";
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Add Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Add Member Registrations' name='".$arChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Update Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Update Registrations' name='".$urChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Delete Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Delete Registrations' name='".$drChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Search Criteria to Qualify Registrations?</h4>";
                    echo "<input type='text'  
                          title='Enter Partial or Full Name to qualify Registrations' name='".$mbSrch."' >";
                    echo '</div>';

                    echo "<div class='form-item'>";
                    echo '<button type="submit" name="submitClassProcess">Process This Class</button>'; 
                    echo '</div>';
                    // echo "</div>"; // end of form grid
                    echo '</div>';
                    
                } else {
   
                    // echo "<h4 class='form-title'>Registration Options not Available for Past Class</h4>";
                    echo "<div class='form-grid'>";
                    echo "<div class='form-item'>";
                    echo '<button type="submit" name="submitClassProcess">Process This Class</button>'; 
                    echo '</div>';
                    echo '</div>';
               
       
                }


                // echo "</div>"; // end of form container
                echo '</fieldset>';
            }
            echo '<button type="submit" name="submitClassProcess">Process Classes</button><br><br>'; 
            echo '</form>';
            
            echo '</section>';
        echo '</div>';
       
    ?>


<footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>