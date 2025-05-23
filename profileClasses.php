<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '
    .$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif (isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '
    .$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} else {
    $_SESSION['profileurl'] = $_SERVER['REQUEST_URI']; 
    $_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
   
}

$classRegs = [];
$pclassRegs = [];

$numClasses = 0;
$pnumClasses = 0;
$database = new Database();
$db = $database->connect();
$userid = $_SESSION['userid'];
$user = new User($db);
$partner = new User($db);
$user->id = $_SESSION['userid'];
$user->read_single();
if ($user->partnerId !== 0) {
    $partner->id = $user->partnerId;
    $partner->read_single();
}

/* get class registrations */
$classReg = new ClassRegistration($db);

$result = $classReg->read_ByUserid($_SESSION['userid']);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if ($rowCount > 0) {
  
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'classdate' => $classdate,
            'email' => $email,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($classRegs, $reg_item);
    
    }
} 
$_SESSION['classregistrations'] = $classRegs;

if ($user->partnerId > 0) {
$pclassReg = new ClassRegistration($db);
$presult = $classReg->read_ByUserid($partner->id);
$prowCount = $result->rowCount();
$pnumClasses = $rowCount;
if ($prowCount > 0) {
  
    while ($row = $presult->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'classdate' => $classdate,
            'email' => $email,
            'registeredby' => $registeredby,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($pclassRegs, $reg_item);
        array_push($_SESSION['classregistrations'], $reg_item);

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
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC - Profile Classes</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="yourProfile.php">Back to Your Profile</a></li>

    </ul>
    </div>
</nav> 


<div class="container-section" >
<div class="content">
    <br><br>
       
    <section id="classregistrations" class="content">
    <h2>Class Registrations</h2>
       
    <div class="form-grid2">
    <div class="form-grid-div">

        <table>
        <thead>  
            <tr>
                <th colspan='6' style='text-align: center'>Your Class Registrations</th>
            </tr>
            <tr>
             
                <th>Delete?</th>
     
                <th>Class Name</th>
                <th>Start Date</th>
                <th>Class Time</th>
                <th>Date Registered</th>     
                <th>Registered By</th>       
            </tr>
        </thead>
        <tbody>
        <form method='POST' action="actions/deleteClassReg.php">
            <?php 
 
            foreach ($classRegs as $classRegistration) {
                $delID = "del".$classRegistration['id'];     
                  echo "<tr>";
                    echo "<td><input type='checkbox' title='Check to Delete Class Registration' 
                    name='".$delID."'></td>";
       
                    echo "<td>".$classRegistration['classname']."</td>";
                    echo "<td>".$classRegistration['classdate']."</td>";  
                    echo "<td>".$classRegistration['classtime']."</td>";         
                    echo "<td>".$classRegistration['dateregistered']."</td>";
                    echo "<td>".$classRegistration['registeredby']."</td>";
             
                  echo "</tr>";
            }
         
            ?> 
        </tbody>
        </table>
    
        <button type='submit' name="submitDeleteReg">Delete Your Class Registrations</button>    
        </form>
    </div>
    <?php
    if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';

        echo "<table>";
        echo "<thead>";  
            echo "<tr>";
                echo "<th colspan='6' style='text-align: center'>Your Partners Class Registrations</th>";
            echo "</tr>";
            echo "<tr>";
             
                echo "<th>Delete?</th>";
     
                echo "<th>Class Name</th>";
                echo "<th>Start Date</th>";
                echo "<th>Class Time</th>";
                echo "<th>Date Registered</th>";     
                echo "<th>Registered By</th>";       
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo '<form method="POST" action="actions/deleteClassReg.php">';
      
 
            foreach ($pclassRegs as $classRegistration) {
                $delID = "del".$classRegistration['id'];     
                  echo "<tr>";
                    echo "<td><input type='checkbox' title='Check to Delete Class Registration' 
                    name='".$delID."'></td>";
       
                    echo "<td>".$classRegistration['classname']."</td>";
                    echo "<td>".$classRegistration['classdate']."</td>";  
                    echo "<td>".$classRegistration['classtime']."</td>";         
                    echo "<td>".$classRegistration['dateregistered']."</td>";
                    echo "<td>".$classRegistration['registeredby']."</td>";
             
                  echo "</tr>";
            }
         
           
        echo "</tbody>";
        echo "</table>";
    
        echo "<button type='submit' name='submitDeleteReg'>Delete Your Partners Class Registrations</button>";    
        echo "</form>";
    echo "</div>";
        }
     ?> 
    </div>
    </section>


    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>