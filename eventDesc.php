<?php
session_start();
require_once 'config/Database.php';
require_once 'models/DinnerMealChoices.php';
require_once 'models/Event.php';
$allEvents = $_SESSION['upcoming_events'];
$eventRegistrations = [];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];

$database = new Database();
$db = $database->connect();
$event = new Event($db);
$mChoices = new DinnerMealChoices($db);
$mealChoices = [];
$numMeals = 0;
$eventID = $_GET['id'];
$result = $mChoices->read_ByEventId($eventID);
  $rowCount = $result->rowCount();
  $num_meals = $rowCount;
  if ($rowCount > 0) {
        $numMeals = $rowCount;
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $meal_item = array(
              'id' => $id,
              'mealname' => $mealname,
              'mealdescription' => $mealdescription,
              'eventid' => $eventid,
              'memberprice' => $memberprice,
              'guestprice' => $guestprice
          );
          array_push($mealChoices, $meal_item);
      } // while
      
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Event Description</title>
</head>
<body>
<nav class="nav">
    <div class="container">   
     <ul> 
    <li><a href="index.php">Back to Home</a></li>
    <?php
      if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
        echo '  <li><a href="SBDCEventst.php">Back to Upcoming Events</a></li>';
      } else {
         echo '  <li><a href="SBDCEvents.php">Back to Upcoming Events</a></li>';
      }
    ?>
  
     </ul>
    </div>
</nav>

<?php
if (isset($_GET['id'])) {
       unset($_GET['id]']);

    echo '<div class="container-section">';
    echo '<section class="content">';
    echo '<br><br>';
   echo '<br><br>';
    echo "<div class='form-container'>"  ;
      echo '<h4>Selected Event</h4>';
    echo "<div class='form-grid'>"  ; 
    foreach($allEvents as $event) {
     if ($event["id"] === $eventID) {  


          $eventCutOff = strtotime($event['eventdate'].'-7 days');
      
      
    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Date: ".$event['eventdate']."</h4>";
    echo "</div>";

    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Type: ".$event['eventtype']."</h4>";
    echo "</div>";

   echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Name: ".$event['eventname']."</h4>";
    echo "</div>";

    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Description: ".$event['eventdesc']."</h4>";
    echo "</div>";


    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Room: ".$event['eventroom']."</h4>";
    echo "</div>";

    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> DJ: ".$event['eventdj']."</h4>";
    echo "</div>";


    if ($event['eventform']) {
      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'> <a href='".$event['eventform']."'>PRINT EVENT FORM</a></h4>";
      echo "</div>";
    }
    if ($event['eventtype'] === 'Dance Party') {
    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Last Day to Register for Dinner: <br>".date("Y-m-d",$eventCutOff)."</h4>"; 
    echo "</div>";
    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Last Day to Register for Dance Only: <br>".$event['eventregend']."</h4>";
    echo "</div>";

    } else if ($event['eventtype'] === 'Dinner Dance') {
      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'> Last Day to Register for Dinner Dance: ".$event['eventregend']."</h4>";
      echo "</div>";
    } else {
      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'> Last Day to Register for Event: ".$event['eventregend']."</h4>";
      echo "</div>";
    }
    
    echo "<div class='form-item'>";
    echo "<h4 class='form-item-title'> Number Registered: ".$event['eventnumregistered']."</h4>";
    echo "</div>";
    if ($event['eventcost'] !== '0') {
      if ($event['eventtype'] === 'Dance Party')  {
      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'> Member Dance Only Cost: ".number_format($event['eventcost'],2)."</h4>";
      echo "</div>";
      echo "<div class='form-item'>";
      echo "<h4 class='form-item-title'> Guest Dance Only Cost: ".number_format($event['eventguestcost'],2)."</h4>";
      echo "</div>";
      } // dance party

    } // eventcost
         echo "</div>"; // form grid
         echo "</div>"; // form container 
     if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
     if (($event['eventtype'] === 'Dinner Dance') || ($event['eventtype'] === 'Dance Party')) {

        if ($num_meals > 0) {
            echo "<div class='form-container'>"  ;
            echo "<h4> Meal Choices</h4>";
             foreach($mealChoices as $choice) {
                  
                echo "<div class='form-grid6'>"  ; 
                
                 echo "<div class='form-item'>";
                 echo "<h4 class='form-item-title'>".$choice['mealname']."</h4>";
                 echo "</div>";
                 echo "<div class='form-item'>";
                 echo "<h4 class='form-item-title'> ".$choice['mealdescription']."</h4>";
                 echo "</div>";
                 echo "<div class='form-item'>";
                 echo "<h4 class='form-item-title'> Member Price: ".number_format($choice['memberprice']/100,2)."</h4>";
                 echo "</div>";
                 echo "<div class='form-item'>";
                 echo "<h4 class='form-item-title'> Guest Price: ".number_format($choice['guestprice']/100,2)."</h4>";
                 echo "</div>";
                  echo "</div>"; // form grid
                    
             }  // for each mealchoice
               echo "</div>"; // form container
              
        } // num meals
     
      }  // dd or dp
    } // testmode

        }    

      }
        echo '<div class="form-grid2">';
  
        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
        if (isset($_SESSION['role'])) {
        if ((isset($_SESSION['testmode'])) && ($_SESSION['testmode'] === 'YES')) {
            echo '<div class="form-grid-div">';
              echo "<button><a href='regForEventsOnline.php'><h4>Click to Register and Pay for Events Online</h4></a></button> ";
              echo '</div>';
           }
        echo '<div class="form-grid-div">';
        echo "<button><a href='regForEvents.php'><h4>Click to Register for Events</h4></a></button> ";
        echo '</div>';
      
         } // role
        } else {
            echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login as a Member or Visitor to Register</a></h4>';
        }
        
        echo '</div>'; 
    } 
 ?>

     <footer >
    <?php
    require 'footer.php';
   ?>
        </footer >
</body>
</html>