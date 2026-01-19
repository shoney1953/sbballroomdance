<?php
session_start();

require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/PaymentProduct.php';
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
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
        if (($_SESSION['role'] != 'EVENTADMIN') && ($_SESSION['role'] != 'SUPERADMIN') ) {
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
       } else {
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
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
 
   $stripeSecretKey = $_SESSION['prodkey'] ;
}
if ($_SERVER['SERVER_NAME'] === 'localhost') { 
   
  $stripeSecretKey = $_SESSION['testkey'] ;
}


$row_count = 0;
$mChoices = [];
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$mealChoices = new DinnerMealChoices($db);
$product = new PaymentProduct($db);

$result = $mealChoices->read_ByEventId($_POST['eventid']);

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
  

}
$event->id = $_POST['eventid'];
$event->read_single();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Event Administration</title>
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
   if (($_SESSION['role'] == 'EVENTADMIN') || 
        ($_SESSION['role'] == 'SUPERADMIN') ) {
           echo "<div class='container-section' name='addmeals'>  <br><br>";
           echo '<section id="users"  class="content">';
           echo ' <h3 class="section-header">Add Meal Choices to '.$event->eventname.' '.$event->eventdate.'</h3> ';
           echo ' <h4 >Be sure to add both a member and guest price for each meal you are entering</h4>';
            echo '<div class="form-container">';
        
            echo "<form method='POST' action='addMealOption.php'>"; 
            echo "<input type='hidden' name='eventId' value='".$event->id."'>"; 
            echo '<div class="form-grid4">';
            echo "<div class='form-grid-div'>"
            ;
            echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal Name 1</h4>";
            echo "<input type='text' name='meal1' title='Enter Meal 1 Name' >";
             echo '</div>';
             echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal Description 1</h4>";
            // echo "<input type='text' name='mealdesc1' title='Enter Meal 1 Description' >";
             echo "<textarea title='Enter Meal 1 Description' name='mealdesc1' rows='2' cols='40'></textarea>";
             echo '</div>';
              echo "<div class='form-item'>";
              echo "<h4 class='form-item-title'>Member Price in Pennies</h4>";
              // if ($event->eventtype === 'BBQ Picnic') {
   
              //   echo "<input type='text' name='memberprice1' title='Enter member price for Meal Choice 1 in pennies' value='0000' >";
                  
              // } else {
        
                echo "<input type='text' name='memberprice1' title='Enter member price for Meal Choice 1 in pennies' >";
          
              // }
                echo '</div>';

              echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Guest Price in Pennies</h4>";
                  // if ($event->eventtype === 'BBQ Picnic') {
                   
                  //     echo "<input type='text' name='guestprice1' title='Enter guest price for Meal Choice 1 in pennies' value='0000' >";
                  // } else {
            
                      echo "<input type='text' name='guestprice1' title='Enter guest price for Meal Choice 1 in pennies' >";
                  // }


              echo '</div>';
                echo '</div> ';  
   
              echo "<div class='form-grid-div'>";
              echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal 2 Name</h4>";
            echo "<input type='text' name='meal2' title='Enter Meal 2 Name' >";
              echo '</div>';
            echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal Description 2</h4>";
            // echo "<input type='text' name='mealdesc2' title='Enter Meal 2 Description' >";
            echo "<textarea title='Enter Meal 2 Description' name='mealdesc2' rows='2' cols='40'></textarea>";
             echo '</div>';
              echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Member Price in Pennies</h4>";
            // if ($event->eventtype === 'BBQ Picnic') {
            //      echo "<input type='text' name='memberprice2' title='Enter member price for Meal Choice 2 in pennies' value ='0000' >";
            // } else {
                echo "<input type='text' name='memberprice2' title='Enter member price for Meal Choice 2 in pennies' >";
            // }
         
              echo '</div>';
              echo "<div class='form-item'>";
                   echo "<h4 class='form-item-title'>Guest Price in Pennies</h4>";
                // if ($event->eventtype === 'BBQ Picnic') {
                //         echo "<input type='text' name='guestprice2' title='Enter guest price for Meal Choice 2 in pennies' value='0000' >";
                // } else {
                    echo "<input type='text' name='guestprice2' title='Enter guest price for Meal Choice 2 in pennies' >";
                // }
      //  
      
              echo '</div>';
                      echo '</div>';
          
                 echo "<div class='form-grid-div'>";
            echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal 3 Name</h4>";
            echo "<input type='text' name='meal3' title='Enter Meal 3 Name' >";
              echo '</div>';
            echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Meal Description 3</h4>";
            // echo "<input type='text' name='mealdesc3' title='Enter Meal 3  Description' >";
            echo "<textarea title='Enter Meal 1 Description' name='mealdesc3' rows='2' cols='40'></textarea>";
             echo '</div>';
              echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Member Price in Pennies</h4>";
            // if ($event->eventtype === 'BBQ Picnic') {
            //         echo "<input type='text' name='memberprice3' title='Enter member price for Meal Choice 3 in pennies' value='0000' >";
            //       } else {
                     echo "<input type='text' name='memberprice3' title='Enter member price for Meal Choice 3 in pennies' >";
                  // }

              echo '</div>';
              echo "<div class='form-item'>";
            echo "<h4 class='form-item-title'>Guest Price in Pennies</h4>";
              // if ($event->eventtype === 'BBQ Picnic') {
              //   echo "<input type='text' name='guestprice3' title='Enter guest price for Meal Choice 3 in pennies' value='0000' >";
              // } else {
                 echo "<input type='text' name='guestprice3' title='Enter guest price for Meal Choice 3 in pennies' >";
              // }
  
              echo '</div>';

            echo "<button type='submit' name='submitAddMeals'>Add Meal Options</button>";   
            echo '</form>'    ;   
            echo '</div> ';   
           echo '</div> ';   
           if ($num_meals > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Meal Name</th>';
            echo '<th>Meal Description</th>';
            echo '<th>Member Price</th>';
            echo '<th>Guest Price</th>';
            echo '<th>Product ID</th>';
            echo '<th>Member Price ID</th>';
            echo '<th>Guest Price ID</th>';
            echo '</tr>';
            echo '</thead>';

            echo '<tbody>';
    
               foreach($mChoices as $choice) {
                   echo '<tr>';
                     echo "<td>".$choice['mealname']."</td>";
                      echo "<td>".$choice['mealdescription']."</td>";
                     echo "<td>".number_format($choice['memberprice']/100,2)."</td>";
                     echo "<td>".number_format($choice['guestprice']/100,2)."</td>";
                     echo "<td>".$choice['productid']."</td>";
                    echo "<td>".$choice['priceid']."</td>";
                    echo "<td>".$choice['guestpriceid']."</td>";
  
                  echo "</tr>";
               }
            echo "</tbody>";
            echo "</table>";
           }


            echo '</div> '; 
         
        }
     ?>
    <footer >

    <?php
  require '../footer.php';
?>
    
</div> 

</footer>
</body>
</html>
