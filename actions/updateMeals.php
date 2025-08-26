<?php
session_start();

require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/EventRegistration.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/PaymentProduct.php';

if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
$allEvents = $_SESSION['allEvents'] ;
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN') ) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
 
   $stripeSecretKey = $_SESSION['prodkey'] ;
}
if ($_SERVER['SERVER_NAME'] === 'localhost') {   

  $stripeSecretKey = $_SESSION['testkey'] ;
}

$_SESSION['eventmealchoices'] = [];
$row_count = 0;
$mChoices = [];
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$mealChoices = new DinnerMealChoices($db);
$product = new PaymentProduct($db);
$eventRegistration = new EventRegistration($db);
if (isset($_POST['eventid'])) {
    $_SESSION['mealupdateeventid'] = $_POST['eventid'];
    $result = $mealChoices->read_ByEventId($_POST['eventid']);
} else {
  $result = $mealChoices->read_ByEventId($_SESSION['mealupdateeventid']);
}


// $result = $mealChoices->read_ByEventId($_POST['eventid']);

$rowCount = $result->rowCount();

$num_meals = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $meal_item = array(
            'id' => $id,
            'mealname' => $mealname,
            'mealdescription' => $mealdescription,
            'eventid' => $eventid,
            'memberprice' => $memberprice,
            'guestprice' => $guestprice,
            'productid' => $productid,
            'priceid' => $priceid,
            'guestpriceid' => $guestpriceid

        );
        array_push($mChoices, $meal_item);
  
    }
  $_SESSION['eventmealchoices'] = $mChoices;
}
$event->id = $_SESSION['mealupdateeventid'];
$event->read_single();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Event Administration Update Meals</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="../index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="../administration.php">Back to Administration</a></li>
        <li><a title="Return to ADMIN EVENTS" href="../SBDCAEvents.php">Back to Events</a></li>
       
      </ul>
    </div>

</nav>
  
  
    
  <?php
   

              
       if (($_SESSION['role'] == 'ADMIN') || 
        ($_SESSION['role'] == 'SUPERADMIN') ) {
        echo "<div class='container-section' name='updatemeals'>  <br><br>";
        echo '<section id="updatemeals"  class="content">';
     
            echo '<br><br><h4>Update Meal Options</h4>';

          if (isset($_GET['error'])) {
            echo '<div class="container-error">';
            echo '<h4 class="error"> ERROR:  '.$_GET['error'].'.</h4>';
            echo '</div>';
            unset($_GET['error']);
        } else {
            $_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
        }
        echo '<form method="POST" action="updateMealOption.php">';
          if ($num_meals > 0) {

               foreach($mChoices as $choice) {
           
               $result = $eventRegistration->read_ByMealID($choice['id']);
               $rowCount = $result->rowCount();

                $upID = "up".$choice['id'];
                $mcID = "mc".$choice['id'];
                $mdID = "md".$choice['id'];
                $mpID = "mp".$choice['id'];
                $gpID = "pp".$choice['id'];
                $prodID = "prod".$choice['id'];
                $mpriceID = "mprice".$choice['id'];
                $gpriceID = "gprice".$choice['id'];
               
                     $mupChk = 'mupChk'.$choice['id'];
                      echo '<div class="form-container">';
                      echo '<div class="form-grid">';
                      echo "<input type='hidden' name='".$prodID."' value='".$choice['productid']."'>"; 
                      echo "<input type='hidden' name='".$mpriceID."' value='".$choice['priceid']."'>"; 
                      echo "<input type='hidden' name='".$gpriceID."' value='".$choice['guestpriceid']."'>"; 
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Update?</h4>";
                      if ($rowCount === 0) {
                          echo "<input type='checkbox' title='Select to Update Meal This Meal' name='".$upID."'>";
                      } else {
                        echo '<h4 class="form-item-title">IN USE</h4>';
                      }
                  
                      echo '</div>';
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Number Registrations</h4>";
                      echo "<h4 class='form-item-title'>".$rowCount."</h4>";
                      echo '</div>';
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Meal Name</h4>";
                       echo "<input type='text' name='".$mcID."' title='Update Meal Choice 1 Description' value='".$choice['mealname']."'>";
                       
                      echo '</div>';
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Meal Description</h4>";
                      //  echo "<input type='text' name='".$mdID."' title='Update Meal Choice 1 Description' value='".$choice['mealdescription']."'>";
                        echo "<textarea  title='Update Meal Choice 1 Description' name='".$mdID."' rows='2' cols='50'>".$choice['mealdescription']."</textarea>";
                      echo '</div>';
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Member Price in Pennies</h4>";
                      echo "<input type='text' name='".$mpID."' title='Update Meal Choice 1 Description' value='".$choice['memberprice']."'>";
                      echo '</div>';
                      echo "<div class='form-item'>";
                      echo "<h4 class='form-item-title'>Guest Price in Pennies</h4>";
                      echo "<input type='text' name='".$gpID."' title='Update Meal Choice 1 Description' value='".$choice['guestprice']."'>";
                      echo '</div>';


                      echo '</div> ';
                      

                      echo '</div> '; 
               }
     
          
             
           }
                      echo "<div class='form-item'>";
                      echo '<button type="submit" name="submitUpdateMeal">Update Meals</button>';
                      echo '</div>';
           echo '</form>';

            echo '</section> '; 
            echo '</div> '; 
    
         
        }
     ?>
    </div>
    <footer >

    <?php
  require '../footer.php';
?>
    
</div> 

</footer>
</body>
</html>
