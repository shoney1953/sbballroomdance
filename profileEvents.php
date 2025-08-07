<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
require_once 'models/DinnerMealChoices.php';
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


$eventRegs = [];
$peventRegs = [];
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$numEvents = 0;
$pnumEvents = 0;
$numClasses = 0;
$pnumClasses = 0;

$database = new Database();
$db = $database->connect();
$userid = $_SESSION['userid'];
$user = new User($db);
$partner = new User($db);
$mChoices = new DinnerMealChoices($db);
$mealchoices = [];
$user->id = $_SESSION['userid'];
$user->read_single();
if ($user->partnerId !== 0) {
    $partner->id = $user->partnerId;
    $partner->read_single();
}
$mealChoice = new DinnerMealChoices($db);
/* */
$eventReg = new EventRegistration($db);
$result = $eventReg->read_ByUserid($_SESSION['userid']);

$rowCount = $result->rowCount();
$numClasses = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventregend' => $eventregend,
            'orgemail' => $orgemail,
            'ddattenddinner' => $ddattenddinner,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'email' => $email,
            'paid' => $paid,
            'mealchoice' => $mealchoice,
            'dietaryrestriction' => $dietaryrestriction,
            'registeredby' => $registeredby,
            'paidonline' => $paidonline,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegs, $reg_item);

    }
} 
$_SESSION['eventregistrations'] = $eventRegs;

if ($user->partnerId > 0) {
    $peventReg = new EventRegistration($db);
$presult = $peventReg->read_ByUserid($user->partnerId);

$prowCount = $presult->rowCount();
$pnumClasses = $prowCount;

if ($prowCount > 0) {

    while ($row = $presult->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventregend' => $eventregend,
            'orgemail' => $orgemail,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'ddattenddinner' => $ddattenddinner,
            'email' => $email,
            'paid' => $paid,
            'mealchoice' => $mealchoice,
            'dietaryrestriction' => $dietaryrestriction,
            'registeredby' => $registeredby,
            'paidonline' => $paidonline,
            "dateregistered" => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($peventRegs, $reg_item);
        array_push($_SESSION['eventregistrations'], $reg_item);

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
    <title>SBDC - Profile Event Registrations</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="SBDCEvents.php">Back to Events</a></li>

        <li><a href="yourProfile.php">Back to Your Profile</a></li>
     
    </ul>
    </div>
</nav> 


<div class="container-section" >
<div class="content">
    <br><br>
    
    
    </div>
       </section>

    <section id="eventregistrations" class="content">
        <h2>Event Registrations</h2>
    <div class="form-grid2">

    <div class="form-grid-div">
 

    <form method='POST' action="actions/deleteEventReg.php">  
        <table>
            <thead>
            <tr>
                <th colspan="7" style="text-align: center">Your Event Registrations</th>
              </tr> 
            <tr>
                <th colspan="7" style="text-align: center">If events have been paid for, please contact event administrator to delete.</th>
            </tr>
            <tr>
                <th>Delete?</th>
      
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Paid</th>
                <th>Online</th>
                <th>Date Registered</th>   
                <th>Registered By</th>         
            </tr>
            </thead>
            <tbody>
            <?php 
    
            foreach ($eventRegs as $eventRegistration) {
                $eventName = 'NONE';
                $delID = "del".$eventRegistration['id'];
              
                  echo "<tr>";
                  if ($eventRegistration['paid'] == true ) {
                     echo "<td> </td>";
                      
                  } else {
                   echo "<td><input type='checkbox' title='Check to Delete Event Registration' name='".$delID."'> </td>";
                  }
                  // echo "<td><input type='checkbox' 
                  //      title='Check to Delete Event Registration' 
                  //      name='".$delID."'> </td>";
 
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";  
                    if ($eventRegistration['paid'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    if ($eventRegistration['paidonline'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
                    echo "<td>".$eventRegistration['registeredby']."</td>";
                    echo '<input type="hidden" name="eventid" value="'.$eventRegistration['eventid'].'">';
                  
                    
          
                  echo "</tr>";
                   if (isset($_SESSION['testmode']) && $_SESSION['testmode'] === 'YES') {
                  if (($eventRegistration['mealchoice'] != NULL) && ($eventRegistration['mealchoice'] > 0) ) {
                    $mealChoice->id = $eventRegistration['mealchoice'];
                    $mealChoice->read_single();
                    echo '<tr style="color: blue">';
                    echo '<td> </td>';
                    echo '<td><em>Meal Choice</em></td>';
                    echo "<td><em>".$mealChoice->mealchoice."</em></td>";
                    echo '<td><em>Price</em></td>';
                    echo "<td><em>".number_format($mealChoice->memberprice/100,2)."</em></td>";
                    echo '<td><em>Dietary Restriction</em></td>';
                    echo "<td><em>".$eventRegistration['dietaryrestriction']."</em></td>";
                    echo '</tr>';
                  } // meal choice not null and > 0
                  } // testmode
            }  // foreach $eventreg
         
            ?> 
       
        </div>
        </tbody>
        </table>
        <button type='submit' name="submitDeleteReg">Delete Your Event Registrations</button>       
    </form>
    </div>

    <?php
     if ($user->partnerId > 0) {
        echo '<div class="form-grid-div">';

        echo '<form method="POST" action="actions/deleteEventReg.php">';  
        echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo '<th colspan="7" style="text-align: center">Your Partners Event Registrations</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<th colspan="7" style="text-align: center">If events have been paid for, please contact event administrator to delete.</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Delete?</th>';

                echo '<th>Event Name</th>';
                echo '<th>Event Date</th>';
                echo '<th>Paid</th>';
                echo '<th>Online</th>';
                echo '<th>Date Registered</th>';   
                echo '<th>Registered By</th> ';        
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        
    
            foreach ($peventRegs as $eventRegistration) {
                $eventName = 'NONE';
                $delID = "del".$eventRegistration['id'];
              
                  echo "<tr>";
                    if ($eventRegistration['paid'] == true ) {
                     echo "<td> </td>";
                      
                  } else {
                   echo "<td><input type='checkbox' title='Check to Delete Event Registration' name='".$delID."'> </td>";
                  }
                  // echo "<td><input type='checkbox' 
                  //      title='Check to Delete Event Registration' 
                  //      name='".$delID."'> </td>";
 
                    echo "<td>".$eventRegistration['eventname']."</td>";
                    echo "<td>".$eventRegistration['eventdate']."</td>";  
                    if ($eventRegistration['paid'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>"; 
                    }
                    if ($eventRegistration['paidonline'] == true ) {
                      echo "<td>&#10004;</td>"; 
                    } else {
                        echo "<td>&times;</td>";
                    }
                    echo "<td>".$eventRegistration['dateregistered']."</td>";
                    echo "<td>".$eventRegistration['registeredby']."</td>";
                    echo '<input type="hidden" name="eventid" value="'.$eventRegistration['eventid'].'">';
          
                  echo "</tr>";
                  if (isset($_SESSION['testmode']) && $_SESSION['testmode'] === 'YES') {
                   if (($eventRegistration['mealchoice'] != NULL) && ($eventRegistration['mealchoice'] > 0) ) {
                    $mealChoice->id = $eventRegistration['mealchoice'];
                    $mealChoice->read_single();
                    echo '<tr style="color: blue">';
                    echo '<td> </td>';
                    echo '<td><em>Meal Choice</em></td>';
                    echo "<td><em>".$mealChoice->mealchoice."</em></td>";
                    echo '<td><em>Price</em></td>';
                    echo "<td><em>".number_format($mealChoice->memberprice/100,2)."</em></td>";
                    echo '<td><em>Dietary Restriction</em></td>';
                    echo "<td><em>".$eventRegistration['dietaryrestriction']."</em></td>";
                    echo '</tr>';
                   }
                  }
            }
       
       
        echo '</div>';
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitDeleteReg">Delete Your Partners Event Registrations</button> ';  
        
    echo '</form>';
    echo '</div>';
     }
 if (isset($_SESSION['testmode']) && $_SESSION['testmode'] === 'YES') {
    echo '<div class="form-grid-div">';
     echo '<form method="POST" name="MemberUpdateEventMeals" action="actions/updateMealEventReg.php">';  
     echo '<table>';
         echo '<thead>';
         echo '<tr>';
         echo '<th colspan="5" style="text-align: center">Modify Your Dance Event Registrations</th>';
         echo '</tr>';
         echo '<tr>';
         echo '<th colspan="5" style="text-align: center"><em>NOTE: Events whose cut off date is past today cannot be modified. Please contact the event coordinator to change</em></th>';
         echo '</tr>';
         echo '<tr>';
             echo '<th>Update?</th>';
             echo '<th>Event Name</th>';
             echo '<th>Event Type</th>';
             echo '<th>Event Date</th>';
             echo '<th>Dance Only Reg Ends</th>';
         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';
 
         foreach ($eventRegs as $reg) {
             $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($reg['eventid']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
              } // while $row
            } // rowcount
            $eventdateTS = strtotime($reg['eventdate']);
            $eventCutOff = $eventdateTS - 7;
            $comparedateTS = strtotime($compareDate);
            if ($comparedateTS <= $eventCutOff) {
            if (($reg['eventtype'] == 'Dance Party') || ($reg['eventtype'] == 'Dinner Dance') ) {
                if (($reg['eventtype'] == 'Dinner Dance') ||
                  (($reg['eventtype'] == 'Dance Party') && !$reg['paid']) ||
                  (($reg['eventtype'] == 'Dance Party') && $reg['paid'] && $reg['ddattenddinner'])) {

                $updID = "upd".$reg['id'];
                $chID = "ch".$reg['id'];
                $sbID = "sb".$reg['id'];
                $updID = "upd".$reg['id'];
                $dddinID = "dddin".$reg['id'];
                $drID = "dr".$reg['id'];
           
               echo "<tr>";
               echo "<td>";
             
               echo "<input type='checkbox' title='Check to Update Event Registration' name='".$updID."'>";
              
               echo "</td>";

                 echo "<td>".$reg['eventname']."</td>";
                 echo "<td>".$reg['eventtype']."</td>";
                 echo "<td>".$reg['eventdate']."</td>";  
                 echo "<td>".substr($reg['eventregend'],0,10)."</td>";  

                 echo '<input type="hidden" name="id" value="'.$reg['id'].'">';

               echo "</tr>";
               echo "<tr>";
               if ($reg['eventtype'] === 'Dance Party')  {
        
                echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                if ($reg['ddattenddinner']) {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."'>";
                  }
            
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid1
                echo '</td>';

                 }  // dance party attend dinner  
           

            if ($reg['eventtype'] === 'Dinner Dance' || $reg['eventtype'] === 'Dance Party')  {

                foreach ($mealChoices as $meal_item) {
                
                $mcID = "mc".$meal_item['id'].$reg['id'];

                echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Select Meal Choice: ".$meal_item['mealchoice']."</h4>";
                  if ($reg['mealchoice'] === $meal_item['id']) {
                    echo "<input type='checkbox'  title='Check to select this meal' id='".$mcID."' name='".$mcID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Uncheck to de-select this meal' id='".$mcID."' name='".$mcID."'>";
                  }
                 echo '</div>'; // end of form item
                 echo '</div>'; // ebd form grid 1
                 echo '</td>';
                 } // foreach mealchoice
                echo '<td>';
                echo '<div class="form-grid1">';
                 echo '<div class="form-item">';
                       echo '<h4 class="form-item-title">Dietary Restriction</h4>';
                 echo "<input type='text'  title='dietary restriction' id='".$drID."' name='".$drID."' value='".$reg['dietaryrestriction']."'>";
                 echo '</div>'; // end of form item
                 echo '</div>'; // ebd form grid 1
                 echo '</td>';
                 } // testmode
                }
            
            } // for each peventreg
        }
           }
       
      
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitUpdateMealReg">Update Your  Event Registrations</button>';   
        echo '</form>';
        echo '</div>';

 if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';
     echo '<form method="POST" name="PartnerUpdateEventMeals" action="actions/updateMealEventReg.php">';  
     echo '<table>';
         echo '<thead>';
         echo '<tr>';
         echo '<th colspan="5" style="text-align: center">Modify Your Partners Dance Event Registrations</th>';
         echo '</tr>';
         echo '<tr>';
         echo '<th colspan="5" style="text-align: center"><em>NOTE: Events whose cut off date is past today cannot be modified. Please contact the event coordinator to change</em></th>';
         echo '</tr>';
         echo '<tr>';
             echo '<th>Update?</th>';
             echo '<th>Event Name</th>';
             echo '<th>Event Type</th>';
             echo '<th>Event Date</th>';
             echo '<th>Dance Only Reg Ends</th>';

         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';
 
         foreach ($peventRegs as $reg) {
             $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($reg['eventid']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;
                if ($rowCount > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealchoice' => $mealchoice,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid
                        ); 
                        array_push($mealChoices, $meal_item);
              } // while
            } // rowcount
            $eventdateTS = strtotime($reg['eventdate']);
            $eventCutOff = $eventdateTS - 7;
            $comparedateTS = strtotime($compareDate);

            if ($comparedateTS <= $eventCutOff) {
            if (($reg['eventtype'] == 'Dance Party') || ($reg['eventtype'] == 'Dinner Dance') ) {
                if (($reg['eventtype'] == 'Dinner Dance') ||
                  (($reg['eventtype'] == 'Dance Party') && !$reg['paid']) ||
                  (($reg['eventtype'] == 'Dance Party') && $reg['paid'] && $reg['ddattenddinner'])) {

                $updID = "upd".$reg['id'];
                $chID = "ch".$reg['id'];
                $sbID = "sb".$reg['id'];
                $updID = "upd".$reg['id'];
                $dddinID = "dddin".$reg['id'];
                $drID = "dr".$reg['id'];
               echo "<tr>";
                echo "<td>";
               echo "<input type='checkbox' title='Check to Update Event Registration' name='".$updID."'>";

                 echo "<td>".$reg['eventname']."</td>";
                 echo "<td>".$reg['eventtype']."</td>";
                 echo "<td>".$reg['eventdate']."</td>";  
                 echo "<td>".substr($reg['eventregend'],0,10)."</td>";  

                 echo '<input type="hidden" name="id" value="'.$reg['id'].'">';

               echo "</tr>";
               echo "<tr>";
               if ($reg['eventtype'] === 'Dance Party')  {
                echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                if ($reg['ddattenddinner']) {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."'>";
                  }
            
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid1
                echo '</td>';
               
                 }  // dance party attend dinner  
           
            if ($reg['eventtype'] === 'Dinner Dance' || $reg['eventtype'] === 'Dance Party')  {
           
                foreach ($mealChoices as $meal_item) {
                $mcID = "mc".$meal_item['id'].$reg['id'];
                echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo "<h4 class='form-item-title'>Select Meal Choice: ".$meal_item['mealchoice']."</h4>";
                  if ($reg['mealchoice'] === $meal_item['id']) {
                    echo "<input type='checkbox'  title='Check to select this meal' id='".$mcID."' name='".$mcID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Uncheck to de-select this meal' id='".$mcID."' name='".$mcID."'>";
                  }
                 echo '</div>'; // end of form item
                 echo '</div>'; // ebd form grid 1
                 echo '</td>';
                 } // foreach mealchoice
                 echo '<td>';
                 echo '<div class="form-grid1">';
                 echo '<div class="form-item">';
                  echo '<h4 class="form-item-title">Dietary Restriction</h4>';
                 echo "<input type='text'  title='dietary restriction' id='".$drID."' name='".$drID."' value='".$reg['dietaryrestriction']."'>";
                 echo '</div>'; // end of form item
                 echo '</div>'; // ebd form grid 1
                 echo '</td>';
            
                } // dance party dinner dance
            }  // dinner dance dance party
            } // for each peventreg
           }
        }   // $peventRegs
      
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitUpdateMealReg">Update Your Partners Event Registrations</button>';   
        echo '</form>';
        echo '</div>';
        } // yes partnerid
 } 
     ?>

    <div class="form-grid-div">
 

 <form method='POST' action="actions/updateBBQEventReg.php">  
     <table>
         <thead>
         <tr>
             <th colspan="6" style="text-align: center">Modify Your BBQ Event Registrations</th>
         </tr>
         <tr>
             <th>Update?</th>
             <th>Event Name</th>
             <th>Event Type</th>
             <th>Event Date</th>
             <th>Date Registered</th>   
             <th>Registered By</th>         
         </tr>
         </thead>
         <tbody>
         <?php 
 
         foreach ($eventRegs as $reg) {
            if ($reg['eventtype'] == 'BBQ Picnic') {
                $updID = "upd".$reg['id'];
                $chID = "ch".$reg['id'];
                $sbID = "sb".$reg['id'];
                $updID = "upd".$reg['id'];
                $dddinID = "dddin".$reg['id'];
           
               echo "<tr>";
               echo "<td><input type='checkbox' 
                    title='Check to Update Event Registration' 
                    name='".$updID."'> </td>";

                 echo "<td>".$reg['eventname']."</td>";
                 echo "<td>".$reg['eventtype']."</td>";
                 echo "<td>".$reg['eventdate']."</td>";  
                 echo "<td>".$reg['dateregistered']."</td>";
                 echo "<td>".$reg['registeredby']."</td>";
                 echo '<input type="hidden" name="id" value="'.$reg['id'].'">';

       
               echo "</tr>";
               echo "<tr>";
               if ($reg['eventtype'] === 'BBQ Picnic') {
 
                echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                if ($reg['ddattenddinner']) {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."'>";
                  }
   
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid
                echo '</td>';
 
                echo '<td>';
                     echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                if ($reg['cornhole']) {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."' checked>";
                  }
         
                echo '</div>'; // end of form item
                   echo '</div>'; // end of form grid
                echo '</td>';

               echo '<td>';
                echo '<div class="form-grid1">';
                echo '<div class="form-item">';
                echo '<h4 class="form-item-title">Play Softball?</h4>';
                if ($reg['softball']) {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."' checked>";
                }
                echo '</div>'; // end of form item
                echo '</div>'; // end of form grid
                echo "</tr>";
                echo '</td>';
            }
            
            }
          }
      
         ?> 
   
     </div>
         </tbody>
     </table>
    <button type='submit' name="submitUpdateBBQReg">Update Your BBQ Event Registration</button>   
     
 </form>
 </div>


     <?php
      if ($user->partnerId > 0) {
    echo '<div class="form-grid-div">';

    echo "<form method='POST' action='actions/updateBBQEventReg.php'>"; 
        echo '<table>';
           echo  '<thead>';
            echo '<tr>';
            echo '<th colspan="6" style="text-align: center">Modify Your Partners BBQ Event Registrations</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Update?</th>';
                echo '<th>Event Name</th>';
                echo '<th>Event Type</th>';
                echo '<th>Event Date</th>';
                echo '<th>Date Registered</th>';   
                echo '<th>Registered By</th>';         
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($peventRegs as $reg) {
               if ($reg['eventtype'] == 'BBQ Picnic') {
                   $updID = "upd".$reg['id'];
                   $chID = "ch".$reg['id'];
                   $sbID = "sb".$reg['id'];
                   $updID = "upd".$reg['id'];
                   $dddinID = "dddin".$reg['id'];
              
                  echo "<tr>";
                  echo "<td><input type='checkbox' 
                       title='Check to Update Event Registration' 
                       name='".$updID."'> </td>";
   
                    echo "<td>".$reg['eventname']."</td>";
                    echo "<td>".$reg['eventtype']."</td>";
                    echo "<td>".$reg['eventdate']."</td>";  
                    echo "<td>".$reg['dateregistered']."</td>";
                    echo "<td>".$reg['registeredby']."</td>";
                    echo '<input type="hidden" name="id" value="'.$reg['id'].'">';
   
          
                  echo "</tr>";
                  echo "<tr>";
            
     
                   echo '<td>';
                   echo '<div class="form-grid1">';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Attend Dinner?</h4>';
                    if ($reg['ddattenddinner']) {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."' checked>";
                  } else {
                    echo "<input type='checkbox'  title='Enter 1 for Attend dinner' id='".$dddinID."' name='".$dddinID."'>";
                  }
        
                   echo '</div>'; // end of form item
                    echo '</div>'; // end of form item
                   echo '</td>';
       
                   echo '<td>';
                    echo '<div class="form-grid1">';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Play Cornhole?</h4>';
                  if ($reg['cornhole']) {
                   echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."' checked>";
                  } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Cornhole' name='".$chID."'>";
                  }
                   echo '</div>'; // end of form item
                       echo '</div>'; // end of form item
                   echo '</td>';
   

       
                  echo '<td>';
                   echo '<div class="form-grid1">';
                   echo '<div class="form-item">';
                   echo '<h4 class="form-item-title">Play Softball?</h4>';
                if ($reg['softball']) {
                echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."' checked>";
                } else {
                      echo "<input type='checkbox'  title='Enter 1 for Play Softball' name='".$sbID."'>";
                }
        
                   echo '</div>'; // end of form item
                   echo '</div>'; // end of form grid
                   echo "</tr>";
                   echo '</td>';
               
              
               } // if bbq
             } // foreach
         
         
     
      
        echo '</div>';
        echo '</tbody>';
        echo '</table>';
        echo "<button type='submit' name='submitUpdateBBQReg'>Update Your Partners BBQ Registration</button>";   
        
    echo '</form>';
    echo '</div>';
        } // partnerid
    // echo '</div>';
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