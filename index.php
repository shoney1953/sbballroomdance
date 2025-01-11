<?php 
session_start();
require_once 'config/Database.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';
require_once 'includes/siteemails.php';
// require_once 'stripe/stripe-php-16.4.0/init.php';
$_SESSION['homeurl'] = $_SERVER['REQUEST_URI']; 

$_SESSION['upcoming_eventnumber'] = 0;
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

$_SESSION['numupcomingclasses'] = 0;
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
            'orgemail' => $orgemail,
            'eventdj' => $eventdj,
            'eventdesc' => $eventdesc,
            'eventroom' => $eventroom,
            'eventregend' => $eventregend,
            'eventregopen' => $eventregopen,
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

$numUpcomingClasses = 0;
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
        array_push($classes, $class_item);
        $class_month = substr($row['date'], 5, 2);
        $class_year = substr($row['date'], 0, 4);

        if ($current_year < $class_year) {
            $numUpcomingClasses++;
            array_push($upcomingClasses, $class_item);
        } else {         
            if ($current_month <= $class_month) {
                $numUpcomingClasses++;
                  array_push($upcomingClasses, $class_item);
             } 
            
        }

   $_SESSION['numupcomingclasses'] = $numUpcomingClasses;
       
     
    }

} 

$_SESSION['classes'] = $classes;
$_SESSION['upcoming_classes'] = $upcomingClasses;
$_SESSION['allClasses'] = $upcomingClasses;

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
                'directorylist' => $directorylist,
                'fulltime' => $fulltime,
                'robodjnumlogins' => $robodjnumlogins,
                'robodjlastlogin' => $robodjlastlogin
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
<div class="container-section">
<nav class="nav">
    <div class="container">
     <ul>
       
         <li> <a href="#" >Home</a></li>
     
            <?php
             if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] != 'visitor') {
           
                  echo ' <li><a href="yourProfile.php">
                  <img title="Click to see or update your information or registrations" src="img/profile.png" alt="Your Profile" style="width:32px;height:32px;">
                  <br>Your Profile</a></li>';
     
                }
                if ($_SESSION['role'] === 'visitor') {
        
                echo ' <li style="color: red;font-weight: bold;font-size: medium">
                Welcome '.$_SESSION["visitorfirstname"].'</li>'; 
 
                }
            }
       
            ?>
      
            <li><a title="Combined Calendar of Events and Classes" href="#calendar">Activities Calendar</a></li>
            <li><a title="2025 New Years Cruise" href="specialevent.php">2025 Cruise Info</a></li>
       
            <li><a title="List of Upcoming Events" href="#events">Event List</a></li>

            <?php
             if (isset($_SESSION['role'])) {
             
                echo '<li><a title="Register for Upcoming Events" href="regForEvents.php">Register for Events</a></li>';
               
             }
            ?>
            <li><a title="List of Upcoming Classes" href="#classes">Class List</a></li>
            <?php
             if (isset($_SESSION['role'])) {
            
                echo '<li><a title="Register for Upcoming Classes" href="regForClasses.php">Register for Classes</a></li>';
  
                
             }
            ?>
        
   
     </ul>
   
</div>
     <div class="container">
     <ul>
    <?php
  
        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
        if (isset($_SESSION['role'])) {
            echo ' <li><a  title="Logout from the Website" style="color: red;font-weight: bold;font-size: medium" href="logout.php">Logout</a></li>'; 
        }
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'visitor') {
              
              echo ' <li><a title="List of SaddleBrooke Ballroom Dance Club Members" href="#directory">
              Member Directory</a></li>';
             
              echo '<li><a title="List of Volunteer Activites" href="#">Volunteer &dtrif;</a>';
              echo '<ul >';
                  echo '<li><a href="#djinfo">DJ Info</a></li>';
                  echo '<li><a href="#instructorinfo">Instructor Info</a></li>';
                  echo '<li><a href="#othervolunteer">Other Opportunities</a></li>';
              echo '</ul>';

            }
        }

     
       
    }
        if (isset($_SESSION['role'])) {
            if (($_SESSION['role'] == 'ADMIN') ||
             ($_SESSION['role'] == 'SUPERADMIN') ||
             ($_SESSION['role'] == 'INSTRUCTOR')
             ) {
                echo '<li><a title="Club Administrative Functions" href="administration.php">Administration</a></li>';
            }
        }
     else {

        echo '<li><a title="Login as a Member or Visitor" style="color: red;font-weight: bold;font-size: medium" href="login.php"> Login</a></li>';
        echo '<li><a title="Information on Joining the Club" style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
    }
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'visitor') {
            echo '<li><a title="Information on Joining the Club" style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
        }
    }
    echo '<li><a title="New Membership Benefits" href="benefits.php">Special Member Benefits</a></li>';

    echo '<li><a title="Frequently Asked Questions" href="faq.php">FAQs</a></li>';
    echo '<li><a title="Additional Options" href="#">More ... &dtrif;</a>';
    echo '<ul>';
    echo '<li><a title="More information about our club" href="#about">About Our Club</a></li>';
    if (isset($_SESSION['username'])) {
        echo '<li><a title="How to Contact Us" href="#contact">Contact Us</a></li>';
    }
    echo '<li><a title="Where to find Help" href="#help">Help</a></li>';
    echo '<li><a title="Pictures from various activities and class videos" href="#pictures">Picture Gallery</a></li>';
    echo '<li><a title="Various Sources of More information" href="resources.php">Resources</a></li>';
    echo '<li><a title="Sponsors for the SaddleBrooke Ballroom Dance Club" href="sponsor.php">Sponsors</a></li>';
    echo '</ul>';
    echo '</li>';
    ?>
        </ul> 
</div>
</nav>
</div>
    <div class="hero">
        <div class="container">
        <!-- <img class="motto-img2" 
            src="img/SBDC LOGO.png" alt="logo">  -->
            <br><br>
            <!-- <h1><img class="motto-img2" 
            src="img/SBDC LOGO.png" alt="logo"></h1> -->
            <h1 > Welcome to the SaddleBrooke Ballroom Dance Club Website        </h1>


            <!-- <img class="motto-img" src="img/self.png" alt="motto"></img> -->
          
            <!-- <img class="motto-img2" 
            src="img/dancing couple grey transparent.png" alt="couple">  -->

         
            <p>We are a primarily social club that provides dance lessons,
                 and opportunities to dance and socialize.</p><br>
            <p>We are comprised of around 250 members from both SaddleBrooke HOA 1 and HOA 2.</p><br>
            <p>We're not <em>"strictly ballroom"</em>. Latin, Western, and Line Dance 
               are also part of our repetoire. </p><br><br>
            <p>Scroll down or click one of the tabs above for more information</p><br>

        </div> 
     
    </div>
 
    
    <div class="container-section ">
    <section id="about" class="content">  

        <h1 class="section-header">What We are About</h1>
        <h2><a  
            href='img/SBDC Membership Form 2025.pdf' target='_blank'>
            Click for Membership Form</a></h2>
        <p>If you love all kinds of dancing, we're the club for you. </p>
        <p> We don't just do Ballroom dance - at our dances/practices, we play 
            music for Ballroom Dance, Western and Western Partner Dance, Line Dance, and Latin Dances.</p>
       <br><strong> <p> We will help you get started if you've never stepped foot on the dance floor. We now are featuring Novice classes twice 
            a month, with a Novice Practice party once a month to help you feel more comfortable about joining dinner dances and other events.
            The Novice classes and the Novice Parties are on Wednesdays in the Mariposa room in the same time slots as our open practice. 
            Check the <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix" target="_blank">
         Activities Calendar
        </a>or send a note to <a href="mailto:countmein@sbballroomdance.com?subject=Novice Dancing Info">
            countmein@sbballroomdance.com</a> for
            more information.</p></strong>

        <br><p>Our members can go to any class we provide free. Prospective members may attend one class free to see if they like it. </p>
        <p>Our members also receive reduced rates for our dinner dances, but you may attend as a guest
            if you'd like to get a feel for the club before committing. </p><br>
        <p>We also have several times during the week
            available for practice - some with D.J.s, sometimes you can bring your favorite music. These
            sessions designated as "Open Practice" are not restricted to members. </p>
            <p><em>Always check the Activites Calendar because we do have 
            cancellations.. and also for DJ information.</em> </p>
            <br>
            <!-- <br><h3 style="color: red">As of  July 2024, we have a completely new schedule for our Activities.</h3> -->
            <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix" target="_blank">
         Click Here to See the Activities Calendar for times and dates.
        </a>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th  colspan=6><em>For Novice Dancers</em></th>
                </tr>  
                <tr>
                    <th> </th>
                    <th>Day</th>
                    <th>Location (Click for Map)</th>
                    <th>Time</th>
                    <th>Activity</th>
                    <th>Notes</th>
                </tr> 
            </thead>
            <tbody>
                <tr>
                    <td>   </td>
                    <td>1st & 2nd Wednesday</td>
    
                    <td> <a title="click for map" href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Mariposa Room at DesertView</a></td>
                    <td>4 - 5pm</td>
                    <td>Class</td>
                    <td>Typically First and Second Wednesdays</td>
                </tr>
                <tr>
                    <td>   </td>
                    <td>4th Wednesday</td>
         
                    <td> <a title="click for map" href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Mariposa Room at DesertView</a></td>
                    <td>4 - 5pm</td>
                    <td>Practice Party</td>
                    <td>Typically Fourth  Wednesday</td>
                </tr>
                <tr>
            <td colspan=6> </td>        
            </tr>
            </tbody>
            <thead>
                <tr>
                    <th  colspan=6><em>For Beginner and Intermediate Dancers</em></th>
                </tr>   
                <tr>
                    <th> </th>
                    <th>Day</th>
                    <th>Location (Click for Map)</th>
                    <th>Time</th>
                    <th>Activity</th>
                    <th>Notes</th>
                </tr> 
            </thead>
            <tbody>
                <tr>
                    <td>   </td>
                    <td>Sunday</td>
                    <td><a title="click for map" href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">MountainView Ballroom</a> </td>
                    <td>3 - 5pm</td>
                    <td>Class(es)</td>
                    <td>Monthly classes are held twice a week for the entire month.</td>
                </tr>
                <tr>
                    <td>   </td>
                    <td>Tuesday</td>
                    <td><a title="click for map" href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">MountainView Ballroom</a> </td>
                    <td>5 - 7pm</td>
                    <td>Class(es)</td>
                    <td>Monthly classes are held twice a week for the entire month.</td>
                </tr>
               <tr>
               <td colspan=6> </td> 
                 </tr>
            </tbody>
            <thead>
                <tr>
                    <th  colspan=6><em>For Everyone - Open Practice, ETC</em></th>
                </tr>   
                <tr>
                    <th> </th>
                    <th>Day</th>
                    <th>Location (Click for Map)</th>
                    <th>Time</th>
                    <th>Activity</th>
                    <th>Notes</th>
                </tr> 
            </thead>
            <tbody>
                <tr>
                    <td>   </td>
                    <td>Monday</td>
                    <td> <a title="click for map" href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td></td>
       
                
                </tr>

                <tr>
                    <td>   </td>
                    <td>3rd and 5th Wednesdays</td>
                    <td> <a title="click for map" href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Mariposa Room at DesertView</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td>When there is not a novice class or party</td>
                    <td> </td>
                
                </tr>
                <tr>
                    <td>   </td>
                    <td>Friday</td>
                    <td> <a title="click for map" href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td>   </td>
                    <td>Saturday</td>
                    <td> <a title="click for map" href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>10 - 11am</td>
                    <td>Western Partner Pattern Review or Other Activities</td>
                    <td> </td>
                    
                </tr>
               
            </tbody>
        </table>
        <br>
        <h3>Our monthly Dance Parties are normally held in the MountainView
            clubhouse in the Ballroom.  <a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">Click Here for Map. </a>
            They are often on Fridays, but sometimes on other days of the week. Check emails or the Activities Calendar.
           
        </h3>
       
        <br>
        <div id="board">
        <h3>Current Board Members</h3>
        <h4>Click on the Email to correspond with one of our Board Members</h4><br>
        <ul>
            <?php
            echo '<li class="li-none li-large">Rich Adinolfi, President 
                   &rarr; <a title="click to email" href="mailto:'.$president.'?subject=SBDC Info">
                       Email Rich</a></li>';
            echo '<li class="li-none li-large">Nan Kartsonis, Vice-President 
                &rarr;  <a title="click to email" href="mailto:'.$vicePresident.'?subject=SBDC Info">
                Email Nan</a></li>';
            echo '<li class="li-none li-large">Roger Shamburg, Treasurer 
                  &rarr; <a title="click to email" href="mailto:'.$treasurer.'?subject=SBDC Info">
                  Email Roger</a></li>';
            echo '<li class="li-none li-large">Peggy Albrecht, Secretary 
                  &rarr; <a title="click to email" href="mailto:'.$secretary.'?subject=SBDC Info">
                  Email Peggy</a></li>';
        
            echo '<li class="li-none li-large">Dale & Ann Pizzitola, Directors of Dance Instruction    
                &rarr; <a title="click to email" href="mailto:'.$danceDirector.'?subject=SBDC Info">
                Email Ann & Dale</a></li>';
           echo '<li class="li-none li-large">Vivian Herman, Volunteer Coordinator  
                &rarr; <a title="click to email" href="mailto:'.$volunteerDirector.'?subject=SBDC Info">
                Email Vivian</a></li>';
            echo '<li class="li-none li-large">Rick Baumgartner, DJ Coordinator   
                &rarr; <a title="click to email" href="mailto:'.$djDirector.'?subject=SBDC Info">
                Email Rick</a></li>';
            echo '<li class="li-none li-large">Sheila Honey, Web Master    
                &rarr; <a title="click to email" href="mailto:'.$webmaster.'?subject=SBDC Web Info">
                Email Sheila</a></li>';
                ?>
  
        </ul>
        </div>
        <br>
        <ul>

       
        <a href="https://drive.google.com/file/d/1oshvPWoc5ERLy7uD-gBuiS_c_n6hTcJE/view?usp=share_link">
         Click Here To Read the Club By Laws.
        </a><br><br>
    </section>
    </div>

    <div class="container-section">
    <section id="events" class="content">

      <br>
        <h1 class="section-header">Upcoming Events</h1>
        <h4>Please note that from 2025 on, for Dance Parties, we will be charging a minimal fee of $5 per member and $10 per non-member to attend the dance only to help offset our costs.</h4>
        <div class="form-grid2">
        <?php

         if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
            if (isset($_SESSION['role'])) {
        echo '<div class="form-grid-div">';
        echo '<button>';
        echo '<a href="regForEvents.php">Register For Events</a>';
        echo '</button>';  
    
        echo '</div>';
            }
        } else {
            echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login to Register</a></h4>';
        }
        ?>
       

        <div class="form-grid-div">
        <form target="_blank" method="POST" action="actions/printEvents.php"> 
        <button type="submit" name="submitPrintEvents">Print Upcoming Events</button>  
        </form>
        </div>
        </div>
        
        <table>
            <thead>
            <?php
            $first_value = reset($upcomingEvents); // First element's value
         
            $first_event_year = substr($first_value['eventdate'], 0, 4);

            echo '<tr>';
            echo '<th colspan="12"><em>'.$first_event_year.'</em></th>';
            echo '</tr>';
            echo '<tr>';
               

                if (isset($_SESSION['username'])) {
                    echo '<th>Report?</th>';
                }
                ?>
                <th>Event</th>
                <th>Registration</th>
                <th>Registration</th>
                <th>Name    </th>
                <th>Type    </th>
                <th>Description</th> 
                <th>Room</th> 
                <th>DJ</th>            
                <th>Cost</th>
                <th>Form/Flyer</th>
                <th># Reg </th>

            </tr>
            <tr>
            <?php
             if (isset($_SESSION['username'])) {
               echo '<th></th>';
             }
            ?>
                <th>Date</th>
                <th>Opens</th>
                <th>Ends</th>
                <th></th>
                <th></th>
                <th></th> 
                <th></th> 
                <th></th>            
                <th></th>
                <th></th>
                <th></th>
             </tr>
            </thead>
         
            <?php 
            $eventNumber = 0;
            foreach ($upcomingEvents as $event) {
                 $eventNumber++;
                 $event_year = substr($event['eventdate'], 0, 4);
             
                 if ($event_year > $first_event_year) {
                    echo '</tbody>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th  colspan="12"  ><em>'.$event_year.'</em></th>';
                    echo '</tr>';
                    echo '<tr>'; 
         
                    $first_event_year = $event_year;
                    echo '<tr>';
                    if (isset($_SESSION['username'])) {
                        echo '<th>Report?</th>';
                    }
                    
                    echo '<th>Event</th>';
                    echo '<th>Registration</th>';
                    echo '<th>Registration</th>';
                    echo '<th>Name    </th>';
                    echo '<th>Type    </th>';
                    echo '<th>Description</th>'; 
                    echo '<th>Room</th> ';
                    echo '<th>DJ</th>';            
                    echo '<th>Cost</th>';
                    echo '<th>Form/Flyer</th>';
                    echo '<th># Reg </th>';
    
                echo '</tr>';
                echo '<tr>';
                if (isset($_SESSION['username'])) {
                    echo '<th></th>';
                }
                
                echo '<th>Date</th>';
                echo '<th>Opens</th>';
                echo '<th>Ends</th>';
                echo '<th></th>';
                echo '<th></th>';
                echo '<th></th>'; 
                echo '<th></th> ';
                echo '<th></th>';            
                echo '<th></th>';
                echo '<th></th>';
                echo '<th></th>';

            echo '</tr>';
            echo '</thead>' ;
                echo '<tbody>';
                 }
                 

                    
        
                 $hr = 'eventMem.php?id=';
                 $hr .= $event["id"];
                  echo "<tr>";
                    if (isset($_SESSION['username'])) {
                    echo "<td>";
                    echo "<form  target='_blank' name='reportEventForm'   method='POST' action='actions/reportEvent.php'> ";
                    echo "<input type='hidden' name='eventId' value='".$event['id']."'>"; 
                    echo "<button class='button-small' type='submit'>&#10004;</button>";
                    // echo '<script language="JavaScript">document.reportEventForm.submit();</script></form>';
                    echo '</form>';
                    echo "</td>";
                    }
                    echo "<td>".substr($event['eventdate'],5,5)."</td>";
                    echo "<td>".substr($event['eventregopen'],5,5)."</td>";
                    echo "<td>".substr($event['eventregend'],5,5)."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventdj']."</td>";            
  
                    echo "<td>".$event['eventcost']."</td>";
                    if ($event['eventform']) {
                        echo '<td><a href="'.$event['eventform'].'">VIEW/PRINT</a></td>';
                    } else {
                            echo "<td> </td>"; 
                    }
                    echo '<td><a href="'.$hr.'">'.$event["eventnumregistered"].'</a></td>';
                    // echo "<td>".$event['eventnumregistered']."</td>";
              
                  echo "</tr>";
            }
            $_SESSION['upcoming_eventnumber'] = $eventNumber;
            ?> 
            </tbody>
        </table>
        <br>

    </section>
    </div>
    </div>
   <div class="container-section ">
    <section id="classes" class="content">
   
      <br>
        <h1 class="section-header">Ongoing and Upcoming Classes</h1>
        <div class="form-grid2">
        <?php

        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
            if (isset($_SESSION['role'])) {
        echo '<div class="form-grid-div">';
        echo '<button>';
        echo '<a href="regForClasses.php">Register For Classes</a>';
        echo '</button>';  

    
        echo '</div>';
            }
        } else {
            echo '<h4><a style="color: red;font-weight: bold;font-size: medium" href="login.php">Please Login to Register</a></h4>';
        }
        ?>
        <div class="form-grid-div">
        <form target="_blank" method="POST" action="actions/printClasses.php"> 
        <button type="submit" name="submitPrintClasses">Print Upcoming Classes</button>  
        </form>
        </div>
        </div>

        <table>
        <thead>
            <?php
                $first_class_value = reset($upcomingClasses); // First element's value
                $first_class_year = substr($first_class_value['date'], 0, 4);
                echo '<tr>';
                echo '<th  colspan="12"  ><em>'.$first_class_year.'</em></th>';
                echo '</tr>';
            ?>

            <tr>
            <th>Click for </th>
                <th>Start</th>
                <th>Start</th>
                <th>Class    </th>
                <th>Class</th>
                <th></th>
                <th>Registration</th>

                <!-- <th>Class</th> -->
                <th></th>
               
            </tr>
            <tr>
            <th>Details</th>               
                <th>Date</th>

                <th>Time    </th>
                <th>Name    </th>
                <th>Level    </th>
                <th>Room    </th>
                <th>Email    </th>

                <!-- <th>Limit</th> -->
                <th># Reg </th>
               
            </tr>
            </thead>
            <tbody>
            <?php 
            $classNumber = 0;
            $first_class_value = reset($upcomingClasses); // First element's value
     
            $first_class_year = substr($first_class_value['date'], 0, 4);
         
            foreach ($upcomingClasses as $class) {
     
                $class_year = substr($class['date'],0,4);
                if ($class_year > $first_class_year) {
                    echo '</tbody>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th  colspan="12"  ><em>'.$class_year.'</em></th>';
                    echo '</tr>';
                    echo '<tr>'; 
         
                    $first_class_year = $class_year;
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Click for </th>";
                        echo "<th>Start</th>";
                        echo "<th>Start</th>";
                        echo "<th>Class    </th>";
                        echo "<th>Class</th>";
                        echo "<th></th>";
                        echo "<th>Registration</th>";
    
                        echo "<th></th>";
                       
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th>Details</th>";               
                        echo "<th>Date</th>";
        
                        echo "<th>Time    </th>";
                        echo "<th>Name    </th>";
                        echo "<th>Level    </th>";
                        echo "<th>Room    </th>";
                        echo "<th>Email    </th>";
        
                        echo "<th># Reg </th>";
                       
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                }
                 $classNumber++;
                 echo "<tr>";
                 $hr = 'classMem.php?id=';
                 $hr .= $class["id"];
                 $ad = 'class.php?id=';
                 $ad .= $class["id"];
                 echo '<td><a href="'.$ad.'">'.$class["id"].'</a></td>';
                    echo "<td>". substr($class['date'],5,5)."</td>";

                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['classlevel']."</td>";
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
               

                    // echo "<td>".$class['classlimit']."</td>";
                    echo '<td><a href="'.$hr.'">'.$class["numregistered"].'</a></td>';
                    // echo "<td>".$class['numregistered']."</td>";
         

                  echo "</tr>";
            }
              
            ?> 
            </tbody>
        </table>
        <br>

    </section>
</div>
    </div>
  
    <div class="container-section ">
    <section id="contact" class="content">
    <br>
    <?php
    if (isset($_SESSION['username'])) { 
        echo '<h1 class="section-header">To correspond with one of the board members, Just click on any of the names below: </h1>';

        echo '<ul>';
            echo '<li class="li-none li-large">President
                   &rarr; <a href="mailto:'.$president.'?subject=SBDC Contact Info">
                       Rich Adinolfi</a></li>';
            echo '<li class="li-none li-large">Vice President  
                &rarr;  <a href="mailto:'.$vicePresident.'?subject=SBDC Info">
                Nan Kartsonis</a></li>';
            echo '<li class="li-none li-large">Questions about costs of events or membership 
                  &rarr; <a href="mailto:'.$treasurer.'?subject=SBDC Contact Info">
                  Roger Shamburg</a></li>';
            echo '<li class="li-none li-large">General Questions    
                &rarr; <a href="mailto:'.$secretary.'?subject=SBDC Contact Info">
                Peggy Albrecht</a></li>';
            echo '<li class="li-none li-large">Questions about Classes   
                &rarr; <a href="mailto:'.$danceDirector.'?subject=SBDC Contact Info">
                Ann and Dale Pizzitola</a></li>';
            echo '<li class="li-none li-large">Questions about Volunteering   
                &rarr; <a href="mailto:'.$volunteerDirector.'?subject=SBDC Contact Info">
                Vivian Herman</a></li>';
           echo '<li class="li-none li-large">Questions about Music or DJing 
                &rarr; <a href="mailto:'.$djDirector.'?subject=SBDC Contact Info">
                Rick Baumgartner</a></li>';
            echo '<li class="li-none li-large">Questions about the Website   
                &rarr; <a href="mailto:'.$webmaster.'?subject=SBDC Contact Info">
                Sheila Honey</a></li>';
            echo '<li class="li-none li-large">Registering for or questions about events or classes   
                &rarr; <a href="mailto:countmein@sbballroomdance.com?subject=SBDC Contact Info">
                countmein@sbballroomdance.com</a></li>';
  
        echo '</ul>';
        echo '<br><br>';
    }
    
    ?>
    </section>
    </div>

   <div class="container-section ">
   <section id="pictures" class="content">
   <br>  
       <h1 class="section-header">Pictures from Past Events</h1>
       <h4>All Events</h4>
       <ul>
       <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-Photos">Combination of All Event Photos</a></em></li>
       </ul>
       <div class="form-grid3">
       <div class="form-grid-div">
       <h4>2024</h4>
       <ul>
       <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-2024-Pictures">2024 Combined Photos</a></em></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/01-12-2024-SBDC-Winter-wonderland-dance">Winter Wonderland Dance 01 12 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/02-16-2024-Valentines-Dance">Valentines Dance 02 16 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-BBQ-Picnic-April-13-2024">BBQ Picnic Social 04 13 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Mama-Mia-Dance-Party-4-19-2024">Mama Mia Dance Party 04 19 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Groovy-Nights-5-17-2024">Groovy Nights 05 17 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/06-14-2024-SBDC-Summer-in-the-Desert-Party">Summer in the Desert 06 14 2024</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Red-White-and-Blue-dance-July-2024">Red White & Blue 07 19 2024
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-August-Fiesta-Party-2024">Fiesta Dance August 2024
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-September-Dance-2024">September Dance 09 13 2024
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-October-17-2024-Black-and-White-Dance-with-Chuck-Moses">October Dance 10 17 2024
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Brunch-Dance-11-16-2024">November Brunch Dance 11 16 2024
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Holiday-Gala-12-13-2024">December Holiday Gala 12 13 2024
       
       
       
       
    
        
       </a></li>
       
       
       
       
      
       </ul>
       </div>
    
      
    
       <div class="form-grid-div">
       <h4>2023</h4>
       <ul>
       <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-2023-Photos">2023 Combined Photos</a></em></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/2023-01-20-Dine-Dance">January Dine and Dance 01 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/2023-02-15-Sweetheart-Dance/">February Sweetheart Dance 02 15 2023</a></li> 
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/04-20-2023-Dinner-Dance/">Dinner Dance 04 20 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-June-Dance-Party-6-16-2023/">June Dance Party 06 16 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/07-14-2023-SBDC-Dance-Party/">July Dance Party 07 14 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-August-Dance-Party/">August Dance Party 08 11 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/09-15-2023-SBDC-Dance-Party/">September Dance Party 09 15 2023</a></li>
       <li class="li-none"><a href="https://sheilahoney.smugmug.com/December-2023-SBDC-Holiday-Gala/">Holiday Gala 12 14 2023</a></li>
       <!-- <li class="li-none">
        <a href="https://sheilahoney.smugmug.com/frame/slideshow?key=pbjK5H&speed=5&transition=fade&autoStart=1&captions=0&navigation=0&playButton=1&randomize=1&transitionSpeed=3&clickable=1"
        target="_blank">test slideshow</a></li> -->
       
       
     
       </ul>
       </div>
       <div class="form-grid-div">
       <h4>2022</h4>
       <ul>
           <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-2022-Photos">2022 Combined Photos</a></em></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/03-22-2022-March-Dinner-Dance">March Dinner Dance 03 22 2022</a></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/04-29-2022-SBDC-dinner-dance">April Dinner Dance 04 29 2022</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/2022-09-02-Dine-and-Dance/">Dine and Dance 09 02 2022</a></li>
           <li class="li-none"><a  href="https://sheilahoney.smugmug.com/Nov-3-2022-SBDC-dinner-dance-with-Chuck-Moses">November Dinner Dance 11 03 2022</a></li>

       
        </ul>
       
        </div>
        
        <div class="form-grid-div">
       <h4>2021</h4>
       <ul>
           <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-2021-Photos">2021 Combined Photos</a></em></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Holiday-Party-12-14-2021">Holiday Party 12 14 2021</a></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-TGIF-10-19-2021">TGIF 11 19 2021</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Sbdc-halloween-party-10-30-2021">Halloween Party 10 30 2021</a></li>
        </ul>
        </div>
        <div class="form-grid-div">
           <h4>2020</h4>
           <ul>
           
           <li class="li-none"><em><a href="https://sheilahoney.smugmug.com/SBDC-2020-Photos">2020 Combined photos</a></em></li>
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
      <div class="form-grid-div">
        <br>
       <h4>SBDC Open Practice Photos</h4>
       <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/SBDC-Open-Practice-Photos/">Random Photos from Open Practice</a></li>    
       </ul>
       <br>
       
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
        <p><a href="faq.php">Click to see Frequently Asked Questions</a></p>
        <br>
        <p>Contact one of the board members or
        <?php

          echo '<a href="mailto:'.$webmaster.'?subject=SBDC Website Help">
                Webmaster</a> listed in the about section or
            read the introduction to the website PDF below.<br>
            <a href="https://drive.google.com/file/d/1wbiW8gOqQ-rM9dpyz7p9qIBHKX_2WvDQ/view?usp=sharing">
                Click for the Member Guide to the Website'
                ?>
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
            Also, Check out the Automated DJ APP (ROBO DJ) that can be used where WIFI is available
        </p><br>
       <a  target="_blank"
href="https://drive.google.com/drive/folders/1LjnghlW8uftZHNxDG1YN4hbkq5AU2f7f?usp=sharing">
DJ Documents</a><br>
        <a  target="_blank" href="https://sbdcrobodj.com/">Go to ROBO DJ App</a><br>
           <a target="_blank" href="https://drive.google.com/file/d/1lRa1Sr_RIpyj-yLF_9qeZgdC7QGZ5q-X/view?usp=sharing"><em>Click for ROBO DJ Guide</em></a><br>
        <a  target="_blank" href="https://zedar.com?x=82351 "
        >DJ Equipment Checkout App</a><br>
        <a target="_blank" href="https://drive.google.com/file/d/1y6GRdngzNWCgx-xSKYwk2wtc-8qK8eGC/view?usp=sharing">DJ Policy</a><br>
        <?php
        echo '<p>Contact our DJ coordinator,<a href="mailto:'.$djDirector.'?subject=SBDC DJ Info">';
        ?>
        Rick Baumgartner</a><br><br></p>
       
     
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
          <?php
          echo ' <a href="mailto:'.$danceDirector.'?subject=SBDC Dance Instruction Info">
           Dance Directors</a></p><br><br>';
        ?>
     
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
         <?php
        echo '<p>If you think <strong>any </strong> of these opportunities sound interesting, please contact our Director of Volunteers, 
        <a href="mailto:'.$volunteerDirector.'?subject=SBDC Volunteer Info">Vivian Herman</a>';
        ?>
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

        echo '<button type="submit" name="submitUserRep">Create Directory Report</button>';   
        echo '</div> ';  
        echo '</form>';
            
   
        echo '<div class="form-grid-div">';
        echo '<form target="_blank" method="POST" action="actions/searchDirectory.php" >';
        echo '<input type="text"  name="search" >';
        echo '<button type="submit" name="searchUser">Search Directory by Name or Email</button>';  
        echo '</form>';
     
     echo '</div>';
     echo '</div> ';    
     echo '<h3>List of Members</h3>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
              
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';          
                echo '<th>Address</th>';
   
                echo '</tr>';
        echo '</thead>';
        echo '<tbody>' ;           
                foreach($directory as $user) {
                    echo "<tr>";
                
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
 
                      echo "</tr>";
                  }
             
        echo '</tbody>';
            echo '</table><br>';       
 
            echo '</section>';
                }
            }
        }
        else {
            echo '<h3><a style="color: red;font-weight: bold;font-size: large"
            href="login.php"> <strong><em>Please Login as a Member to View Directory</em></strong></a></h3><br><br>'; 
        }
    
    

?>
    </section>
    <br><br> 
    <!-- <div>
    <section id="nominationform" class="content">
    <h1>Volunteer Nomination Form</h1><br>
 
   <ul>
    <li class="li-none"><a  href="https://drive.google.com/file/d/1Mr3NHDtOXQiHQbhaA_5kheREf5UuRF4G/view?usp=sharing">Click to download Volunteer Nomination form</a></li>
   </ul>

    </section>
   <br>
   </div> -->
<?php
  require 'footer.php';
?>
</body>
</html>
