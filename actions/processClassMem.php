<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DanceClass.php';
require_once '../models/ClassRegistration.php';
require_once '../models/User.php';


$upcomingClasses = $_SESSION['upcoming_classes'];
$database = new Database();
$db = $database->connect();
$class = new DanceClass($db);
$reg = new ClassRegistration($db);
$partnerReg = new ClassRegistration($db);
$user = new User($db);
$gotClassReg = 0;
$gotPartnerClassReg = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Process Classes</title>
</head>
<body>
<nav class="nav">
        <div class="container">      
        <ul>  
            <li><a href="../index.php">Back to Home</a></li>
            <li><a href="../SBDCClassest.php">Back to Upcoming Classes</a></li>
        </ul>
        </div>
</nav>
 <br><br><br>
<section id="processclassess" class="content">
<div class="section-back">

<?php

foreach ($upcomingClasses as $class) {

    $delChk = "del".$class['id'];
    $regChk = "reg".$class['id'];

    if ($class['id'] === $_POST['classId']) {
     
             $gotClassReg = 0;
              $gotPartnerClassReg = 0;
               
              if ($reg->read_ByClassIdUser($class['id'],$_SESSION['userid'])) {
                       $gotClassReg = 1;
                    
                 }
                
               if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                 if ($partnerReg->read_ByClassIdUser($class['id'],$_SESSION['partnerid'])) {
                      $gotPartnerClassReg = 1;
                 }
               }
           
              if (isset($_POST["$delChk"])) {

                echo '<div class="form-container"';
                echo "<h1 class='form-title'>Remove Registrations for ".$class['classname']." beginning on ".$class['date']."</h1>";
                 echo  '<form method="POST" action="deleteClassRegt.php">  ';
                echo '<input type="hidden" name="classid" value='.$class['id'].'>';
                if ($gotClassReg) {

                
                 echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
              
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['userfirstname']."</h4>";
                echo '<input type="hidden" name="regid1" value='.$reg->id.'>';
                echo "<input type='checkbox'  title='Check to remove registration' id='remID1' name='remID1' checked>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
                } // got eventreg

                 if ($gotPartnerClassReg) {
                
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo '<input type="hidden" name="regid2" value='.$partnerReg->id.'>';
                echo "<h4 class='form-item-title'>Remove registration for ".$_SESSION['partnerfirstname']."</h4>";
                echo "<input type='checkbox'  title='Check to remove registration' id='remID2' name='remID2' checked>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
                 }
    
                echo '</div>'; // end of form grid
                echo '<button type="submit" name="submitRemoveRegs">Remove Registration(s)</button>';
                echo  '</form>';
                echo '</div>'; // end of form container
             } // end of delete check

   
             if (isset($_POST["$regChk"])) {
  
                echo '<div class="form-container"';
                    echo "<h1 class='form-title'>Add Registrations for ".$class['classname']." beginning on ".$class['date']."</h1>";
            
                echo  '<form method="POST" action="regClasst.php">  ';
                echo '<input type="hidden" name="classid" value='.$class['id'].'>';
                 echo '<div class="form-item">';
                  echo '<h4 class="form-item-title">Message To Instructor</h4>';
                  echo "<textarea name='message2ins' cols='100' rows='4'></textarea><br><br>";
                  echo '</div>';
                if ($gotClassReg === 0) {
                   echo '<div class="form-grid-div">';
                    echo '<div class="form-grid">';
                    echo '<input type="hidden" name="firstname1" value='.$_SESSION['userfirstname'].'>';
                    echo '<input type="hidden" name="lastname1" value='.$_SESSION['userlastname'].'>';
                    echo '<input type="hidden" name="email1" value='.$_SESSION['useremail'].'>';

                echo '<div class="form-item">';
                  echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['userfirstname']." ".$_SESSION['userlastname']." ".$_SESSION['useremail']."</h4>";
                echo "<input type='checkbox'  title='Check add Reservation ' name='mem1Chk' checked>";
                echo '</div>';
                echo '</div>'; // form grid
                echo '</div>'; // form grid div
              }
                if (!$gotPartnerClassReg) {
                    echo '<input type="hidden" name="firstname2" value='.$_SESSION['partnerfirstname'].'>';
                    echo '<input type="hidden" name="lastname2" value='.$_SESSION['partnerlastname'].'>';
                    echo '<input type="hidden" name="email2" value='.$_SESSION['partneremail'].'>';
                echo '<div class="form-grid-div">';
                echo '<div class="form-grid">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Add registration for ".$_SESSION['partnerfirstname']." ".$_SESSION['partnerlastname']." ".$_SESSION['partneremail']."</h4>";
                echo "<input type='checkbox'  title='Check add Reservation '  name='mem2Chk' checked>";
                echo '</div>';
                 echo '</div>'; // form grid
                echo '</div>'; // form grid div
                 }
                echo '<button type="submit" name="submitAddRegs">Add Registration(s)</button>';
                echo '</div>'; // form container 
                echo '</form>';
               }

    } // classid matches
} // foreach upcoming class


?>
</div>
</section>
<footer >
    <?php
    require '../footer.php';
   ?>
</footer>
</body>
</html>
