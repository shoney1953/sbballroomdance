<?php 
session_start();
require_once 'config/Database.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/User.php';
$_SESSION['homeurl'] = $_SERVER['REQUEST_URI']; 

if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. 
    Please Validate Input</h4><br>';

    unset($_GET['error']);
} elseif (isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> '.$_GET['success'].'</h4><br>';

    unset($_GET['success']);
} else {
    $_SESSION['homeurl'] = $_SERVER['REQUEST_URI']; 
}


$_SESSION['user'] = null;
date_default_timezone_set("America/Phoenix");
$num_classes = 0;
$num_events = 0;
$classes = [];
$events = [];
$upcomingClasses = [];
$upcomingEvents = [];
$directory = [];
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');

// require 'includes/db.php';
$database = new Database();
$db = $database->connect();

// get events
$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventcost' => $eventcost,
            'eventform' => $eventform,
            'eventdj' => $eventdj,
            'eventdesc' => $eventdesc,
            'eventroom' => $eventroom,
            'eventnumregistered' => $eventnumregistered
        );
        array_push($events, $event_item);
    
        if ($compareDate <= $row['eventdate']) {
            array_push($upcomingEvents, $event_item);
        }
        
     

    }
  

} 
$_SESSION['events'] = $events;
$_SESSION['upcoming_events'] = $upcomingEvents;


/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();
$num_classes = $rowCount;
$current_month = date('m');
$current_year = date('Y');


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
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            "classnotes" => $classnotes,
            'numregistered' => $numregistered
        );
        array_push($classes, $class_item);
        $class_month = substr($row['date'], 5, 2);
        $class_year = substr($row['date'], 0, 4);

     
        if ($current_month <= $class_month) {
            array_push($upcomingClasses, $class_item);
        }
        if ($current_year < $class_year) {
            array_push($upcomingClasses, $class_item);
        }


       
     
    }

} 

$_SESSION['classes'] = $classes;
$_SESSION['upcoming_classes'] = $upcomingClasses;

if (isset($_SESSION['username'])) {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'visitor') {
    $user = new User($db);
    $result = $user->read();
    
    $rowCount = $result->rowCount();
    $num_users = $rowCount;
    $_SESSION['directory'] = [];
    if($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'role' => $role,
                'email' => $email,
                'phone1' => $phone1,
                'password' => $password,
                'partnerId' => $partnerid,
                'hoa' => $hoa,
                'passwordChanged' => $passwordChanged,
                'streetAddress' => $streetaddress,
                'lastLogin' => date('m d Y h:i:s A', strtotime($lastLogin)),
                'numlogins' => $numlogins,
                'directorylist' => $directorylist
            );
            if ($user_item['directorylist']) {
                array_push($directory, $user_item);
            }
         
      
        }
   
        $_SESSION['directory'] = $directory;
    } 
}
}
}


// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" 
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<nav class="nav">
    <div class="container">
 
     <ul>
        <li><a href="#" >Home</a></li>
        <li><a href="#">Activities &dtrif;</a>
        <ul class="dropdown">
            <li><a href="#events">Events</a></li>
            <li><a href="#classes">Classes</a></li>
            <li><a href="#calendar">Activities Calendar</a></li>
        </ul>
        </li>
        <li><a href="#about">About Us</a></li>

        <li><a href="#contact">Contact Us</a></li>
        <li><a href="#">Volunteer &dtrif;</a>
        <ul class="dropdown">
            <li><a href="#djinfo">DJ Info</a></li>
            <li><a href="#instructorinfo">Instructor Info</a></li>
            <li><a href="#othervolunteer">Other Opportunities</a></li>
        </ul>
        </li>
        <li><a href="#pictures">Picture Gallery</a></li>
        <li><a href="#help">Help</a></li>
        <li><a href="resources.php">Resources</a></li>
   
    </li>
     </ul>
</div>
     <div class="container">
     <ul>
    <?php

    if (isset($_SESSION['username'])) {
        
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'visitor') {
              echo ' <li><a href="yourProfile.php">
              <img src="img/profile.png" alt="Your Profile" style="width:32px;height:32px;">
              <br>Your Profile</a></li>';
              echo ' <li><a  style="color: red;font-weight: bold;font-size: medium" href="logout.php">Logout</a></li>'; 
              echo ' <li><a href="#directory">
              Member Directory</a></li>';
            }
        }

        if (isset($_SESSION['role'])) {
            if (($_SESSION['role'] == 'ADMIN') ||
             ($_SESSION['role'] == 'SUPERADMIN') ||
             ($_SESSION['role'] == 'INSTRUCTOR')
             ) {
                echo '<li><a href="administration.php">Administration</a></li>';
            }
        }
    } else {

        echo '<li><a style="color: red;font-weight: bold;font-size: medium" href="login.php"> Login</a></li>';
    }
    ?>
        </ul> 
</div>
</nav>
    <div class="hero">
        <div class="container">
            <h1 >Welcome to the SaddleBrooke Ballroom Dance Club Website</h1>

            <img class="motto-img" src="img/self.png" alt="motto">
            <!-- <img class="motto-img2" src="img/coupleSilloutteWhite.png" alt="couple"> -->
            <img class="motto-img2" src="img/dancing couple grey transparent.png" alt="couple"> 
         
            <p>We are a primarily social club that provides dance lessons,
                 and opportunities to dance and socialize.</p><br>
            <p>We are comprised of members from both SaddleBrooke HOA 1 and HOA 2.</p><br>
            <p>We're not <em>"strictly ballroom"</em>. Latin, Western, and Line Dance 
               are also part of our repetoire. </p><br><br>
 
    </div>
    
    </div>
    <div class="container-section ">
    <section id="about" class="content">  
    <h1><a style="color: red" href="img/Membership Form 2023 Dance Club.pdf"><strong>
            <em>Click Here for Membership Form </em></strong></a></h1>
    
        <h1 class="section-header">What We are About</h1>

        <p>If you love all kinds of dancing, we're the club for you. </p>
        <p> We don't just do Ballroom dance - at our dances/practices, we play 
            music for Ballroom Dance, Western and Western Partner Dance, Line Dance, and Latin Dances.</p>
       <br><strong> <p> We will help you get started if you've never stepped foot on the dance floor. We now are featuring Novice classes twice 
            a month, with a Novice Practice party once a month to help you feel more comfortable about joining dinner dances and other events.
            The classes are normally on Tuesdays in the Vermillion room, and the Novice Parties are on Fridays in the Vermillion room in the 
            same time slots as our open practice. Check the Activities calendar or send a note to <a href="mailto:countmein@sbballroomdance.com?subject=Novice Dancing Info">
                       countmein@sbballroomdance.com</a> for
            more information.</p></strong>

        <br><p>Our members can go to any class we provide free. Prospective members may attend one class free to see if they like it. </p>
        <p>Our members also receive reduced rates for our dinner dances, but you may attend as a guest
            if you'd like to put your toe in the water before committing. </p><br>
        <p>We also have several times during the week
            available for practice - some with D.J.s, sometimes you can bring your favorite music. These
            sessions designated as "Open Practice" are not restricted to members. </p>
            <p><em>Always check the Activites Calendar because we do have 
            cancellations.. and also for DJ information.</em> </p>
        <br><p>For 2023, we now have 3 different time slots for Open Practice - Sometimes these are used for Novice Classes or Parties as well:</p>
        <ul>
            <li class="li-none">Tuesday 4 to 5:30pm - Vermillion Room
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a></li>
            <li class="li-none">Friday 3 to 5pm - Vermillion Room
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a></li>
            <li class="li-none">Sunday 3 to 5pm - MountainView Ballroom
            <a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">Click Here for Map</a>
            </li>
        </ul><br>
 

        <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix">
         Click Here to See the Activities Calendar for times and dates.
        </a>
        <br><br>
        <p>Our classes are normally at the Mariposa Room at the DesertView Clubhouse
            <a href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Click Here for Map.</a>
        </p>
        <p>Our Sunday Open Practices, and our Dinner Dances are normally in the MountainView
            clubhouse in the Ballroom.
            <a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">Click Here for Map</a>
        </p>
        <p>Our Tuesday and Friday Open Practices, and a few Dinner Dances are in the HOA 1 
            clubhouse in the Vermillion Room and or Vistas Dining Room.
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a>
        </p>
        <br>
        <h3>Current Board Members</h3>
        <h4>Click on the Email to correspond with one of our Board Members</h4><br>
        <ul>
            <li class="li-none li-large">Brian Hand, President 
                   -&rarr; <a href="mailto:president@sbballroomdance.com?subject=SBDC Info">
                       president@sbballroomdance.com</a></li>
            <li class="li-none li-large">Richard Adinolfi, Vice-President 
                -&rarr;  <a href="mailto:vicepresident@sbballroomdance.com?subject=SBDC Info">
                    vicepresident@sbballroomdance.com</a></li>
            <li class="li-none li-large">Dottie Adams, Treasurer 
                  -&rarr; <a href="mailto:treasurer@sbballroomdance.com?subject=SBDC Info">
                  treasurer@sbballroomdance.com</a></li>
            <li class="li-none li-large">Sheila Honey, Secretary 
                  -&rarr; <a href="mailto:secretary@sbballroomdance.com?subject=SBDC Info">
                  secretary@sbballroomdance.com</a></li>
            <li class="li-none li-large">Dale & Ann Pizzitola, Chairs Instruction Director    
                -&rarr; <a href="mailto:dancedirector@sbballroomdance.com?subject=SBDC Info">
                dancedirector@sbballroomdance.com</a></li>
           <li class="li-none li-large">Rick Baumgartner, Chair D J and Music  
                -&rarr; <a href="mailto:djdirector@sbballroomdance.com?subject=SBDC Info">
                djdirector@sbballroomdance.com</a></li>
            <li class="li-none li-large">Sheila Honey, Web Master    
                -&rarr; <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Info">
                webmaster@sbballroomdance.com</a></li>
  
        </ul>
        <br>
        <ul>

       
        <a href="https://drive.google.com/file/d/1AxOsZTmqIujc3ytk4KR-EJ__4UQXDdUd/view?usp=sharing">
         Click Here Read the Club By Laws.
        </a><br><br>
    </section>
    </div>

    <div class="container-section ">
    <section id="events" class="content">

      <br>
        <h1 class="section-header">Upcoming Events</h1>
  
        <a style="font-weight: bold"
        href="https://drive.google.com/file/d/1mgJ-r6vAaVw9BgxuD-ae9w9Om4oWXrDV/view?usp=sharing">
         Click Here to See Dates to Save for the Upcoming Dinner Dances for Fall 2022 and Spring 2023.
        </a>      <br><br>
        <table>
            <tr>
                <th>Event Date</th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th>  
                <th>Event DJ</th>            
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Reg </th>
                <th>Form</th>

            </tr>
            <?php 
            $eventNumber = 0;
            foreach ($upcomingEvents as $event) {
                 $eventNumber++;
                  echo "<tr>";
                    echo "<td>".$event['eventdate']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".$event['eventdj']."</td>";            
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventcost']."</td>";
                    echo "<td>".$event['eventnumregistered']."</td>";
                if ($event['eventform']) {
                    echo '<td><a href="'.$event['eventform'].'">VIEW</a></td>';
                } else {
                        echo "<td> </td>"; 
                }
                  echo "</tr>";
            }
         
            ?> 
        </table>
        <br>
        <?php
        if ($eventNumber > 0) {
        $partner = new User($db);
        if (isset($_SESSION['username'])) {
            echo '<h3> Enter Information Below to Register for Event(s) </h3>';
            echo '<h4> This process generates an email, so it takes a while. Please be patient.
            You will be sent back to the home page when it is complete.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.</h4>';
        
            echo '<form method="POST"  action="actions/regEvent.php" target="_blank">';
            echo '<div class="form-grid3">';
      
            echo '<div class="form-grid-div">  <br>';
        if (isset($_SESSION['userfirstname'])) {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'"><br>';
        } else {
                echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                echo '<input type="text" name="regFirstName1" ><br>';
        }
        if (isset($_SESSION['userlastname'])) {
                echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'"><br>';
        } else {
                echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                echo '<input type="text" name="regLastName1" ><br>';
        }
        if (isset($_SESSION['useremail'])) {
                echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
        } else {
                echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                echo '<input type="email" name="regEmail1" ><br><br>';
        }
        if (isset($_SESSION['partnerid'])) {
            $partner->id = $_SESSION['partnerid'];
            $partner->read_single();
           
        }
        echo '<label for="message">Message (For Dine and Dance events please indicate if you want to have dinner)</label>';
        echo '<textarea  name="message" rows="4" cols="50"></textarea><br>';
         echo' </div>';
        echo '<div class="form-grid-div"> <br>';
     
        if (isset($_SESSION['partnerid'])) {
    
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'"><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" value="'.$partner->email.'"><br> <br>';

           } else {
            echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
            echo '<input type="text" name="regFirstName2" ><br>';
            echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
            echo '<input type="text" name="regLastName2" ><br>';
            echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
            echo '<input type="email" name="regEmail2" ><br> <br>';
           }

        echo '</div>';     
        echo '<div class="form-grid-div">';
            echo '<ul class="list-box">';
            echo '<h4 style="text-decoration: underline;color: black"><em>
              To Register -- Please select One or More of the Events Listed</em></h4><br>';
        foreach ($upcomingEvents as $event) {
                echo '<li class="list-none">';
                $chkboxID = "ev".$event['id'];
                $eventString = " ".$event['eventname']." ".$event['eventdate']." ";
                echo "<input type='checkbox' name='$chkboxID'>";
                echo "<label for='$chkboxID'> I/We would like to register for:
                    <strong>$eventString </strong></label><br>";
        }
                echo '</li>';
            
            echo '</ul> <br><br>';

                 echo '<button name="submitEventReg" type="submit">Submit</button><br>';
            echo '</div>';     
            echo '</form>';
    
        } else {
           // echo '<h3 style="color: red"> <strong><em>Please Login to Register</em></strong> </h3><br><br>';
            echo '<h3><a style="color: red;font-weight: bold;font-size: large"
             href="login.php"> <strong><em>Please Login to Register</em></a></h3><br><br>';
        }
    } else {
        echo '<h3> No Upcoming Events right now -- Check back soon</h3><br><br>';
    }
        ?>
    </section>
    </div>
   <div class="container-section ">
    <section id="classes" class="content">
   
      <br>
        <h1 class="section-header">Ongoing and Upcoming Classes</h1>
        <a href="https://drive.google.com/file/d/10uMkFzA0QC9KhwgtObAWG_66OKcEogsg/view?usp=sharing">
            Click for flyer for upcoming Classes.
        </a><br><br>
        <table>
            <tr>
                
                <th>Start Date</th>
                <th>Time    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Registration Email    </th>
                <th>Instructors    </th>
                <th>Notes</th>
                <th>Class Limit    </th>
                <th># Reg </th>
                <th>Room    </th>
                
               
            </tr>
            <?php 
            $classNumber = 0;
            foreach ($upcomingClasses as $class) {
                 $classNumber++;
                  echo "<tr>";
                    
                    echo "<td>". $class['date']."</td>";
                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['classlevel']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classnotes']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
                    echo "<td>".$class['numregistered']."</td>";
                    echo "<td>".$class['room']."</td>";

                  echo "</tr>";
            }
              
            ?> 
        </table>
        <br>
        
       <?php
        if ($classNumber > 0) {
        if (isset($_SESSION['username'])) {
            $partner = new User($db);
            echo '<h3> Enter Information Below to Register for all or Selected Classes </h3>';
            echo '<h4> This process generates an email, so it takes a while. Please be patient.
            You will be sent back to the home page when it is complete.<br>
            You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.</h4>';
        
            echo '<form method="POST"  action="actions/regClass.php" target="_blank">';
            echo '<div class="form-grid3">';

            echo '<div class="form-grid-div"> <br>';
               
            if (isset($_SESSION['userfirstname'])) {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" value="'.$_SESSION['userfirstname'].'"><br>';
            } else {
                    echo '<label for="regFirstName1">First Registrant First Name (Required)</label><br>';
                    echo '<input type="text" name="regFirstName1" ><br>';
            }
            if (isset($_SESSION['userlastname'])) {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" value="'.$_SESSION['userlastname'].'"><br>';
            } else {
                    echo '<label for="regLastName1">First Registrant Last Name (Required)</label><br>';
                    echo '<input type="text" name="regLastName1" ><br>';
            }
            if (isset($_SESSION['useremail'])) {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" value="'.$_SESSION['useremail'].'"><br><br>';
            } else {
                    echo '<label for="regEmail1">First Registrant Email (Required)</label><br>';
                    echo '<input type="email" name="regEmail1" ><br><br>';
            }
            if (isset($_SESSION['partnerid'])) {
                $partner->id = $_SESSION['partnerid'];
                $partner->read_single();
               
            }
           
                echo '<label for="message2ins">Message to Instructor(Optional)</label><br>';
               echo '<textarea id="message2ins" name="message2ins" rows="4" cols="50"></textarea><br>';
             echo' </div>';
            echo '<div class="form-grid-div"> <br>';
            if (isset($_SESSION['partnerid'])) {
    
                echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
                echo '<input type="text" name="regFirstName2" value="'.$partner->firstname.'" ><br>';
                echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
                echo '<input type="text" name="regLastName2" value="'.$partner->lastname.'"><br>';
                echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
                echo '<input type="email" name="regEmail2" value="'.$partner->email.'"><br> <br>';
    
               } else {
                echo '<label for="regFirstName2">Second Registrant First Name(optional)</label><br>';
                echo '<input type="text" name="regFirstName2" ><br>';
                echo '<label for="regLastName2">Second Registrant Last Name(optional)</label><br>';
                echo '<input type="text" name="regLastName2" ><br>';
                echo '<label for="regEmail2">Second Registrant Email (optional)</label><br>';
                echo '<input type="email" name="regEmail2" ><br> <br>';
               }
            echo '</div>';     
            echo '<div class="form-grid-div">';
                echo '<ul class="list-box">';
                echo '<h4 style="text-decoration: underline;color: black"><em>
                  To Enroll -- Please select One or More of the Classes Listed</em></h4><br>';
            foreach ($upcomingClasses as $class) {
                    echo '<li class="list-none">';
                    $chkboxID = "cb".$class['id'];
                    $classString = " ".$class['classname']." ".$class['classlevel']." ".$class['date']." ";
                    echo "<input type='checkbox' name='$chkboxID'>";
                    echo "<label for='$chkboxID'> I/We would like to register for:
                        <strong>$classString </strong></label><br>";         
                    echo '</li>';   
                    }            
                echo '</ul> <br><br>';      
                
        
        echo '<button name="submitRegClass" type="submit">Submit</button><br>';
        
           
            echo '</form>';
            echo '</div>'; 
            echo '</div>';
    
        } else {
           // echo '<h3 style="color: red"> <strong><em>Please Login to Register</em></strong> </h3><br><br>';
            echo '<h3><a style="color: red;font-weight: bold;font-size: large"
            href="login.php"> <strong><em>Please Login to Register</em></a></h3><br><br>';
        }
    } else {
        echo '<h3> No Upcoming Classes right now -- Check back soon</h3><br><br>';
    }
        ?>
    </section>
    </div>
  
    <div class="container-section ">
    <section id="contact" class="content">
    <br>
    <?php
    if (isset($_SESSION['username'])) { 
        echo '<h1 class="section-header">Please contact one of us below (Just click on the email to email us): </h1>';
        echo '<em><h4> Once you press SUBMIT, 
        There will be a time delay while the email is generated and sent, so please be patient.</em><br>
        You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.<br><br>';
        echo '<ul>';
            echo '<li class="li-none li-large">President
                   -&rarr; <a href="mailto:president@sbballroomdance.com?subject=SBDC Info">
                       president@sbballroomdance.com</a></li>';
            echo '<li class="li-none li-large">Vice President  
                -&rarr;  <a href="mailto:vicepresident@sbballroomdance.com?subject=SBDC Info">
                    vicepresident@sbballroomdance.com</a></li>';
            echo '<li class="li-none li-large">Questions about costs of events or membership 
                  -&rarr; <a href="mailto:treasurer@sbballroomdance.com?subject=SBDC Info">
                  treasurer@sbballroomdance.com</a></li>';
            echo '<li class="li-none li-large">General Membership Questions    
                -&rarr; <a href="mailto:secretary@sbballroomdance.com?subject=SBDC Info">
                secretary@sbballroomdance.com</a></li>';
            echo '<li class="li-none li-large">Questions about Classes   
                -&rarr; <a href="mailto:dancedirector@sbballroomdance.com?subject=SBDC Info">
                dancedirector@sbballroomdance.com</a></li>';
           echo '<li class="li-none li-large">Questions about Music or DJing 
                -&rarr; <a href="mailto:djdirector@sbballroomdance.com?subject=SBDC Info">
                djdirector@sbballroomdance.com</a></li>';
            echo '<li class="li-none li-large">Questions about the Website   
                -&rarr; <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Info">
                webmaster@sbballroomdance.com</a></li>';
  
        echo '</ul>';
    }
    if (!isset($_SESSION['username'])) { 
        echo '<h1 class="section-header">Enter your information below to contact us about New membership: </h1>';
        echo '<em><h4> Once you press SUBMIT, 
        There will be a time delay while the email is generated and sent, so please be patient.</em>
        You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
            spam/junk folder.<br><br>';
        
            echo '<form method="POST" action="actions/contact.php" target="_blank">';
            echo '<div class="form-grid3">';
                echo '<div class="form-grid-div">';
                echo '<label for="firstname">First Name</label><br>';
                echo '<input type="text" name="firstname" ><br>';
                echo '<label for="lastname">Last Name</label><br>';
                echo '<input type="text" name="lastname" ><br>';
                echo '<label for="email">Email</label><br>';
                echo '<input type="email" name="email" ><br>';
                echo '</div>';
           
                echo '<div class="form-grid-div">'; 
                echo '<label for="danceexperience">How familiar are you with Dance?</label><br>';
                echo '<select name = "danceexperience">';
                    echo '<option value = "Novice" selected>Never Danced Before</option>';
                    echo '<option value = "Beginner" selected>Beginner or It has been a long time</option>';
                    echo '<option value = "Intermediate">Had moderate experience dancing</option>';
                    echo '<option value = "Advanced">Been Dancing for a long time</option>';
                echo '</select> <br>';
               
                echo '<label for="dancefavorite">What is your favorite type of dance?</label><br>';
                echo '<select name = "dancefavorite">';
                    echo '<option value = "Ballroom" selected>Ballroom dances: Foxtrot, Quickstep, Waltz etc.</option>';
                    echo '<option value = "Latin">Cha Cha, Rumba, Bolero, American Tango or Argentine Tango, etc.</option>';
                    echo '<option value = "Country Western">Western Partner, Two Step, Nightclub, etc.</option>';
                    echo '<option value = "Line Dance">Boot Scootin, Cupid Shuffle, Electric Slide, etc.</option>';
                    echo '<option value = "Other">I like them all, or I prefer some other kind of dance.</option>';
                 echo '</select><br><br>';

                echo '</div class="form-grid-div">';  
                echo '<div class="form-grid-div">';
                echo '<p> Tell Us About Yourself or Provide a Message to the Club</p><br>';
                echo '<textarea name="message" cols="50" rows="4"></textarea><br><br>';
                echo '<button name="submit" type="submit">Submit</button><br>';
                echo '</div>';
            echo '</div>';
                
            echo '</form>';
    }
    ?>
    </section>
    </div>

   <div class="container-section ">
   <section id="pictures" class="content">
   <br>  
       <h1 class="section-header">Pictures from Past Events</h1>
       <div class="form-grid3">
       <div class="form-grid-div">
       <h4>2022</h4>
       <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/03-22-2022-March-Dinner-Dance">March Dinner Dance 03 22 2022</a></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/04-29-2022-SBDC-dinner-dance">April Dinner Dance 04 29 2022</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/2022-09-02-Dine-and-Dance/">Dine and Dance 09 02 2022</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Nov-3-2022-SBDC-dinner-dance-with-Chuck-Moses">November Dinner Dance 11 03 2022</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/2023-01-20-Dine-Dance">January Dine and Dance 01 2023</a></li>
       
        </ul>
       
        </div>
        
        <div class="form-grid-div">
       <h4>2021</h4>
       <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Holiday-Party-12-14-2021">Holiday Party 12 14 2021</a></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-TGIF-10-19-2021">TGIF 11 19 2021</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Sbdc-halloween-party-10-30-2021">Halloween Party 10 30 2021</a></li>
        </ul>
        </div>
        <div class="form-grid-div">
           <h4>2020</h4>
           <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/03-08-2020-March-Brunch-Dance">March Brunch Dance 03 08 2020</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/02-15-2020-Sweetheart-Dance">Sweetheart Dance 02 15 2020</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Masquerade-ball-01-11-2020-SBDC">Masquerade Dance 01 11 2020</a></li>
           </ul>
       </div>
    


     
       <div class="form-grid-div">
        <br>
       <h4>SBDC CLASS VIDEOS</h4>
       <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SaddleBrooke-Ballroom-Dance-Club-Videos">Class Videos</a></li>    
       </ul>
       <br>
      </div>
    </div>
   </section>
   </div>
   <div class="container-section ">
    <section id="help" class="content">
    <br><br> 
        <h1 class="section-header">For Website Help</h1><br>
        <p>Contact one of the board members or webmaster
        <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Website Help">
                webmaster@sbballroomdance.com</a> listed in the about section or
            read the introduction to the website PDF below.<br>
            <a href="https://drive.google.com/file/d/1UsEnVCytYHFuoEIlworrTg049jIzV2H6/view?usp=sharing">
                Click for the Introduction PDF
            </a>
        </p><br><br>
    </section>
   </div>
        
   <div class="container-section ">
   <section id="djinfo" class="content">
   <br>  
       <h1 class="section-header">Become a DJ</h1>
       <p> All our DJs are volunteers. We have two speakers available, 
           and quite a large 
           body of club music available. 
           We even have some pre-made song lists to facilitate
           DJ duties. See below for all the documents we've put together. </p><br>
        <p>
            Check out the Automated DJ APP that can be used where WIFI is available
        </p><br>
       <a  target="_blank"
href="https://drive.google.com/drive/folders/1LjnghlW8uftZHNxDG1YN4hbkq5AU2f7f?usp=sharing">
DJ Documents</a><br>
        <a  target="_blank" href="https://sbdcrobodj.com/">Robo DJ App</a><br>
        <a  target="_blank" href="https://zedar.com?x=82351 "
        >DJ Equipment Checkout App</a><br><br>
        <p>Contact our DJ Director for more information<a href="mailto:djdirector@sbballroomdance.com?subject=SBDC DJ Info">
                djdirector@sbballroomdance.com</a><br><br></p>
       
     
   </section>
   </div>
   <div class="container-section ">
   <section id="instructorinfo" class="content">
   <br>  
       <h1 class="section-header">Become an Instructor</h1>
       <br>
       <p> All our Instructors are volunteers.</p>
        <p>Some folks have been dancing a long time;
           Some not so much.</p> 
        <p>Some have had formal training; Some not so much. </p>
        <p>It doesn't matter to us.</p> 
        <p> It is in the spirit of our club to share what we know. 
            All of our members are very 
               appreciative of anything you can share with them. </p>
        <p>If you'd like to become an instructor.. even for an hour class,
          please contact our Dance Instruction Director for more information 
           <a href="mailto:dancedirector@sbballroomdance.com?subject=SBDC Dance Instruction Info">
                dancedirector@sbballroomdance.com</a></p><br><br>

     
   </section>
   </div>
   <div class="container-section ">
   <section id="othervolunteer" class="content">
   <br>  
       <h1 class="section-header">Other Ways to Participate</h1>
       <br>
       <p><em>We can always use your help on the following projects: </em></p>
         <ul>
             <li class="li-none">Decorating for Dance Parties</li>
             <li class="li-none">Checking People in at Dance Parties</li>
             <li class="li-none">Helping prepare material for the Annual Activites Fair </li>
             <li class="li-none">Helping with Meet and Greet at the Annual Activites Fair </li>
             <li class="li-none">Suggesting new Activities </li>
             <li class="li-none">Suggesting new Music </li>
             <li class="li-none">Encouraging your friends and neighbors to join the club. </li>
         </ul><br>
        <p>If you think <strong>any </strong> of these sound interesting, please contact one of the 
           board members.
       </p><br><br>

     
   </section>
   </div>
   <div class="container-section ">
   <section id="calendar" class="content">
   <br>  
       <h1 class="section-header">Activites Calendar</h1>
       <br>
       
       <iframe 
       src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23A79B8E&ctz=America%2FPhoenix&src=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20&src=djhndW9hbWgwN2lodjM1MWlyMXM4anMwMGtAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%234285F4&color=%23EF6C00" 
        ></iframe>
       <br>
       <p><strong>If that isn't showing correctly, try the link below: </strong>
            <a  target="_blank" 
    href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix">
            Activities Calendar</a>
   
       <br><br>

     
   </section>
   </div>
   <div class="container-section ">
    <section id="directory" class="content">
    <br><br> 
        <h1 class="section-header">Membership Directory</h1><br>
    <?php

     if (isset($_SESSION['username'])) {
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] != 'visitor') {

        echo '<div class="form-grid3">';
          
        echo '<form target="_blank" method="POST" action="actions/reportDirectory.php">'; 
        echo '<div class="form-grid-div">';
  
        echo '<h4>Report Membership Directory</h4>';
        echo '<input type="checkbox" name="reportDirectory">';
        echo '<label for="reportDirectory">Report Directory</label><br>';    
      
        echo '<button type="submit" name="submitUserRep">Report</button>';   
        echo '</div> ';  
        echo '</form>';
            
        echo '</div> '; 
        echo '<h4>List of Members</h4>';
        echo '<form target="_blank" method="POST" action="actions/searchDirectory.php" >';
        echo '<input type="text"  name="search" >';
        echo '<button type="submit" name="searchUser">Search Directory</button>';  
        echo '</form>';
     
        echo '<table>';
        echo '<tr>';
              
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';          
                echo '<th>Address</th>';
   
                echo '</tr>';
                       
                foreach($directory as $user) {
                
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
 
                      echo "</tr>";
                  }
             
        
            echo '</table><br>';       
 
            echo '</section>';
                }
            }
        }
        else {
            echo '<h3><a style="color: red;font-weight: bold;font-size: large"
            href="login.php"> <strong><em>Please Login to View Directory</em></a></h3><br><br>'; 
        }
    

?>
    </section>
   </div>
<?php
  require 'footer.php';
?>
</body>
</html>
