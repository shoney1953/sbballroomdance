<?php
session_start();
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username']))
{
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
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
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
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$upcomingEvents = [];
$upcomingEvents = $_SESSION['upcoming_events'] ;
$user = new User($db);
$users = $_SESSION['regUsers'];
// $_SESSION['regUsers'] = [];
// $num_users = 0;
// if (isset($_POST['search'])) {
//     $search = $_POST['search'];
//     $search .= '%';

//     $result = $user->readLike($search);
    
//     $rowCount = $result->rowCount();
//     $num_users = $rowCount;

//     if($rowCount > 0) {
    
//         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//             extract($row);
//             $user_item = array(
//                 'id' => $id,
//                 'firstname' => $firstname,
//                 'lastname' => $lastname,
//                 'username' => $username,
//                 'role' => $role,
//                 'email' => $email,
//                 'phone1' => $phone1,
//                 'password' => $password,
//                 'partnerId' => $partnerid,
//                 'hoa' => $hoa,
//                 'passwordChanged' => $passwordChanged,
//                 'streetAddress' => $streetaddress,
//                 'lastLogin' => $lastLogin
//             );
//             array_push( $users, $user_item);
      
//         }
//     }


// } else {
//         $result = $user->read();
        
//         $rowCount = $result->rowCount();
//         $num_users = $rowCount;
//         if($rowCount > 0) {
        
//             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//                 extract($row);
//                 $user_item = array(
//                     'id' => $id,
//                     'firstname' => $firstname,
//                     'lastname' => $lastname,
//                     'email' => $email,
//                     'partnerId' => $partnerid
                    
//                 );
//                 array_push( $users, $user_item);
        
//             }
            
//         }
//     }  
// $_SESSION['regUsers'] = $users;

$updateReg = false;
$deleteReg = false;
$addReg = false;



if (isset($_POST['regId'])) {
    $regId = htmlentities($_POST['regId']);
    if(isset($_POST['updateReg'])) {$updateReg = $_POST['updateReg'];}
    if(isset($_POST['deleteReg'])) {$deleteReg = $_POST['deleteReg'];}
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}

    if ($updateReg || $deleteReg) {
        $eventReg->id = $regId;
        if  ($eventReg->read_single()) {

        } else {
            echo "<br><h3 style='color: red;font-weight: bold;font-size: large'>
            No event registration with id ". $eventReg->id." found</h3><br>";
           echo "<h3 style='color: red;font-weight: bold;font-size: large'>
            Please return and enter a valid event registration id.</h3><br>"; 
        }
         
    } 

}
if (!isset($_POST['regId'])) {
    if(isset($_POST['addReg'])) {$addReg = $_POST['addReg'];}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Event Registration Administration</title>
</head>
<body>

    <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
      <?php 
        if ($updateReg || $deleteReg) {
        echo '<h1 class="section-header">Selected Registration</h1><br>';
        echo '<table>';
        echo '<tr>';
        
                echo '<th>Event Id    </th>';
                echo '<th>Event Name</th>';
                echo '<th>First Name </th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Paid</th>';
                echo '<th>Message</th>';
                echo '<th>Userid</th>';
                echo '<th>Registration ID   </th>';    
            echo '</tr>';
          
                echo "<tr>";
                    echo "<td>".$eventReg->eventid."</td>";
                    echo "<td>".$eventReg->eventname."</td>";
                    echo "<td>".$eventReg->firstname."</td>";
                    echo "<td>".$eventReg->lastname."</td>";
                    echo "<td>".$eventReg->email."</td>";
                    echo "<td>".$eventReg->paid."</td>";
                    echo "<td>".$eventReg->message."</td>";
                    echo "<td>".$eventReg->userid."</td>";
                    echo "<td>".$eventReg->id."</td>";
                echo "</tr>";

          
        echo '</table><br>';

       if($updateReg) {
        echo '<form method="POST" action="updateEventReg.php">';
  
        echo '<label for="eventid">Event Id</label>';
        echo '<input type="text" name="eventid" value="'.$eventReg->eventid.'"><br>';
        echo '<label for="firstname">First Name</label>';
        echo '<input type="text" name="firstname" value="'.$eventReg->firstname.'"><br>';
        echo '<label for="lastnames">Last Name</label>';
        echo '<input type="text" name="lastname" value="'.$eventReg->lastname.'"><br>';
        echo '<label for="email">Email</label>';
        echo '<input type="email" name="email" value="'.$eventReg->email.'"><br>';
        echo '<label for="paid">Paid (1 = YES; 0 = NO)</label>';
        echo '<input type="number" name="paid" min="0" max="1" value="'.$eventReg->paid.'"><br>';
        echo '<label for="ddattenddinner">Attend dinner if Dine & Dance format (1 = YES; 0 = NO)</label>';
        $ad = 0;
        if ($eventReg->ddattenddinner) {
            $ad = $eventReg->ddattenddinner;
        }
        else {
            $ad = 0;
        }
        echo '<input type="number" name="ddattenddinner" min="0" max="1" value="'.$ad.'"><br>';
        echo '<label for="ddattenddance">Attend dance if Dine & Dance format (1 = YES; 0 = NO)</label>';
        echo '<input type="number" name="ddattenddance" min="0" max="1" value="'.$eventReg->ddattenddance.'"><br>';
        echo '<label for="message">Message </label><br>';
        echo '<textarea  name="message" rows="4" cols="50">'.$eventReg->message.'</textarea><br>';
        echo '<label for="userid">Userid</label>';
        echo '<input type="text" name="userid" value="'.$eventReg->userid.'"><br>';
        echo '<input type="hidden" name="id" value="'.$eventReg->id.'">';
        echo '<button type="submit" name="submitUpdateReg">Update the Registration</button><br>';
        echo '</form>';
    
        }
    }

        if ($addReg) {
            echo '<div class="form-grid-div">';
            echo '<form method="POST" action="addEventReg.php">';
            echo '<ul class="list-box">';
            echo '<h4 style="text-decoration: underline;color: black"><em>
              To Register -- Please select One or More of the Events Listed</em></h4><br>';
        foreach ($upcomingEvents as $ev) {
                echo '<li class="list-none">';
                $chkboxID = "ev".$ev['id'];
                $evString = " ".$ev['eventname']." ".$ev['eventtype']." ".$ev['eventdate']." ";
                echo "<input type='checkbox' name='$chkboxID'>";
                echo "<label for='$chkboxID'> Select Event:
                    <strong>$evString </strong></label><br>";
                echo '</li>';
        }
        echo '</ul> <br><br>';
        echo '<ul class="list-box">';
        echo '<h4 style="text-decoration: underline;color: black"><em>
         Select one or more of the members Listed</em></h4><br>';
         foreach ($users as $usr) {
            echo '<li lass="list-none">';
            $usrID = "us".$usr['id'];
            $userString = " ".$usr['firstname']." ".$usr['lastname']." ".$usr['email']." ";
            echo "<input type='checkbox' name='$usrID'>";
            echo "<label for='$usrID'>$userString </label><br>";
         echo '</li>';
         }
         echo '</ul>';
           

            echo '<button type="submit" name="submitAddReg">
               Add the Event Registration</button><br>';
            echo '</form>';
        }     
        if($deleteReg) {
            echo '<p> You have selected to delete class registration id: '.$eventReg->id.'<br>';
            echo 'First name:  '.$eventReg->firstname.' Last Name '.$eventReg->lastname. '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteEventReg.php">';
            echo '<input type="hidden" name="id" value="'.$eventReg->id.'">';
            echo '<input type="hidden" name="eventid" value="'.$eventReg->eventid.'">';
            echo '<button type="submit" name="submitDeleteReg">Delete the Registration</button><br>';
            echo '</form>';
        }
        ?> 
    </section>
    </div>
</body>
</html>

