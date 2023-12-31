<?php 
session_start();
require_once 'config/Database.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';
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
            'eventdj' => $eventdj,
            'eventdesc' => $eventdesc,
            'eventroom' => $eventroom,
            'eventregend' => $eventregend,
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
            'numregistered' => $numregistered
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
        <!-- <li> </li> -->
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
      
       
            <li><a href="#events">Event List</a></li>

            <?php
             if (isset($_SESSION['role'])) {
             
                echo '<li><a href="regForEvents.php">Register for Events</a></li>';
               
             }
            ?>
            <li><a href="#classes">Class List</a></li>
            <?php
             if (isset($_SESSION['role'])) {
            
                echo '<li><a href="regForClasses.php">Register for Classes</a></li>';
  
                
             }
            ?>
        
   
     </ul>
   
</div>
     <div class="container">
     <ul>
    <?php
  
        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
        if (isset($_SESSION['role'])) {
            echo ' <li><a  style="color: red;font-weight: bold;font-size: medium" href="logout.php">Logout</a></li>'; 
        }
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'visitor') {
              
              echo ' <li><a href="#directory">
              Member Directory</a></li>';
              echo '<li><a href="#">Volunteer &dtrif;</a>';
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
                echo '<li><a href="administration.php">Administration</a></li>';
            }
        }
     else {

        echo '<li><a title="Login as a Member or Visitor" style="color: red;font-weight: bold;font-size: medium" href="login.php"> Login</a></li>';
        echo '<li><a style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
    }
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'visitor') {
            echo '<li><a style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
        }
    }
    echo '<li><a title="Frequently Asked Questions" href="faq.php">FAQs</a></li>';
    echo '<li><a href="#">More ... &dtrif;</a>';
    echo '<ul>';
    echo '<li><a href="#about">About Our Club</a></li>';
    if (isset($_SESSION['username'])) {
        echo '<li><a href="#contact">Contact Us</a></li>';
    }
    echo '<li><a href="#help">Help</a></li>';
    echo '<li><a href="#pictures">Picture Gallery</a></li>';
    echo '<li><a href="resources.php">Resources</a></li>';
    echo '<li><a href="sponsor.php">Sponsors</a></li>';
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
            <h1><img class="motto-img2" 
            src="img/SBDC LOGO.png" alt="logo"></h1>
            <h1 > Welcome to the SaddleBrooke Ballroom Dance Club Website        </h1>


            <!-- <img class="motto-img" src="img/self.png" alt="motto"></img> -->
          
            <!-- <img class="motto-img2" 
            src="img/dancing couple grey transparent.png" alt="couple">  -->

         
            <p>We are a primarily social club that provides dance lessons,
                 and opportunities to dance and socialize.</p><br>
            <p>We are comprised of members from both SaddleBrooke HOA 1 and HOA 2.</p><br>
            <p>We're not <em>"strictly ballroom"</em>. Latin, Western, and Line Dance 
               are also part of our repetoire. </p><br><br>
            <p>Scroll down or click one of the tabs above for more information</p><br>

        </div> 
     
    </div>
 
    
    <div class="container-section ">
    <section id="about" class="content">  

        <h1 class="section-header">What We are About</h1>
        <h2><a  
            href='img/SBDC Membership Form 2024.pdf' target='_blank'>
            Click for Membership Form</a></h2>
        <p>If you love all kinds of dancing, we're the club for you. </p>
        <p> We don't just do Ballroom dance - at our dances/practices, we play 
            music for Ballroom Dance, Western and Western Partner Dance, Line Dance, and Latin Dances.</p>
       <br><strong> <p> We will help you get started if you've never stepped foot on the dance floor. We now are featuring Novice classes twice 
            a month, with a Novice Practice party once a month to help you feel more comfortable about joining dinner dances and other events.
            The classes are normally on Mondays in the Vermilion room, and the Novice Parties are on Fridays in the Vermilion room in the 
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
            <br>
            <br><h3>As of July 1 2023, we have a completely new schedule for our Activities.</h3>
            <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix" target="_blank">
         Click Here to See the Activities Calendar for times and dates.
        </a>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th colspan=6>For Novice Dancers</th>
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
                    <td> <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></li></td>
                    <td>4 - 5pm</td>
                    <td>Class</td>
                    <td>Typically First and Third Mondays</td>
                </tr>
                <tr>
                    <td>   </td>
                    <td>Friday</td>
                    <td> <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></li></td>
                    <td>4 - 5:30pm</td>
                    <td>Practice Party</td>
                    <td>Typically Fourth or Fifth Friday</td>
                </tr>
                <tr>
            <td colspan=6> </td>        
            </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan=6>For Beginner and Intermediate Dancers</th>
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
                    <td><a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">MountainView Ballroom</a> </td>
                    <td>3 - 5pm</td>
                    <td>Class(es)</td>
                    <td>Monthly classes are held twice a week for the entire month.</td>
                </tr>
                <tr>
                    <td>   </td>
                    <td>Tuesday</td>
                    <td><a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">MountainView Ballroom</a> </td>
                    <td>5:30 - 7:30pm</td>
                    <td>Class(es)</td>
                    <td>Monthly classes are held twice a week for the entire month.</td>
                </tr>
               <tr>
               <td colspan=6> </td> 
                 </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan=6>For Everyone - Open Practice</th>
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
                    <td> <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>5 - 6pm</td>
                    <td>Open Practice </td>
                    <td>When there is a Novice Class</td>
                
                </tr>
                <tr>
                    <td>   </td>
                    <td>Monday</td>
                    <td> <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td>When there is a not Novice Class</td>
               
                </tr>
                <tr>
                    <td>   </td>
                    <td>Wednesday</td>
                    <td> <a href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Mariposa Room at DesertView</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td> </td>
                
                </tr>
                <tr>
                    <td>   </td>
                    <td>Friday</td>
                    <td> <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Vermilion Room HOA 1</a></td>
                    <td>4 - 6pm</td>
                    <td>Open Practice </td>
                    <td>When there is a not a Novice Party</td>
                    
                </tr>
               
            </tbody>
        </table>
        <br>
        <h3>Our monthly Dance Parties are normally held in the MountainView
            clubhouse in the Ballroom.  <a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">Click Here for Map. </a>
            They are often on Fridays, but sometimes on other days of the week. Check emails or the Activities Calendar.
           
        </h3>
        <!-- <p>For Our Open Practices:</p>
        <ul>
            <li class="li-none">Fourth Monday of the Month 4pm to 6pm - Vermilion Room
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a></li>
            <li class="li-none">Wednesdays 4pm to 6pm in the Mariposa Room
            <a href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Click Here for Map</a></li>
            <li class="li-none">Fridays from 4pm to 6pm in the Vermilion Room (when not used for the Novice Party)
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a>
            </li>
        </ul><br> -->
 
        <!-- <p>Our monthly classes will be held in the MountainView Ballroom on Sundays and Tuesdays
            <a href="https://goo.gl/maps/GE1xE4H8uJL4RmxD8">Click Here for Map.</a>
        </p>

        <p>Our Monday Open Practices and Saturday Western Partner Review sessions will be held
            in the Vermilion Room at HOA 1
            <a href="https://goo.gl/maps/fVyiV4xrXSfR7wDK7">Click Here for Map</a>
        </p>
        <p>Our Wednesday Open Practices will be held in the Mariposa Room at Desertview
        <a href="https://goo.gl/maps/kGnv9ohdmdZfQpcQ6">Click Here for Map</a></li>
        </p> -->
        <br>
        <div id="board">
        <h3>Current Board Members</h3>
        <h4>Click on the Email to correspond with one of our Board Members</h4><br>
        <ul>
            <li class="li-none li-large">Rich Adinolfi, President 
                   &rarr; <a href="mailto:adinolfir@outlook.com?subject=SBDC Info">
                       Email Rich</a></li>
            <li class="li-none li-large">Nan Kartsonis, Vice-President 
                &rarr;  <a href="mailto:nkartsonis@me.com?subject=SBDC Info">
                Email Nan</a></li>
            <li class="li-none li-large">Roger Shamburg, Treasurer 
                  &rarr; <a href="mailto:shamburgrog23@gmail.com?subject=SBDC Info">
                  Email Roger</a></li>
            <li class="li-none li-large">Jane Sims, Secretary 
                  &rarr; <a href="mailto:calamitywjs@gmail.com?subject=SBDC Info">
                  Email Jane</a></li>
            <li class="li-none li-large">Dale & Ann Pizzitola, Directors of Dance Instruction    
                &rarr; <a href="mailto:dancedirector@sbballroomdance.com?subject=SBDC Info">
                Email Ann & Dale</a></li>
           <li class="li-none li-large">Vivian Herman, Volunteer Coordinator  
                &rarr; <a href="mailto:tucsonviv6228@gmail.com?subject=SBDC Info">
                Email Vivian</a></li>
            <li class="li-none li-large">Rick Baumgartner, DJ Coordinator   
                &rarr; <a href="mailto:rb@zedargroup.com?subject=SBDC Info">
                Email Rick</a></li>
            <li class="li-none li-large">Sheila Honey, Web Master    
                &rarr; <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Info">
                Email Sheila</a></li>
  
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
            <tr>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<th>Report?</th>';
                }
                ?>
                <th>Date of Event    </th>
                <th>Register By</th>
                <th>Name    </th>
                <th>Type    </th>
                <th>Description</th> 
                <th>Room</th> 
                <th>DJ</th>            

                <th>Cost</th>
             
                <th>Form/Flyer</th>
                <th># Reg </th>

            </tr>
            </thead>
            <tbody>
            <?php 
            $eventNumber = 0;
            foreach ($upcomingEvents as $event) {
                 $eventNumber++;
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
                    echo "<td>".$event['eventdate']."</td>";
                    echo "<td>".$event['eventregend']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>"; 
                    echo "<td>".$event['eventroom']."</td>";
                    echo "<td>".$event['eventdj']."</td>";            

                    echo "<td>".$event['eventcost']."</td>";
                    if ($event['eventform']) {
                        echo '<td><a href="'.$event['eventform'].'">VIEW</a></td>';
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
            <tr>
                
                <th>Start Date</th>
                <th>Time    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Room    </th>
                <th>Registration Email    </th>
                <th>Instructors    </th>
                <th>Notes</th>
                <th>Class Limit    </th>
                <th># Reg </th>
               
            </tr>
            </thead>
            <tbody>
            <?php 
            $classNumber = 0;
            foreach ($upcomingClasses as $class) {
                 $classNumber++;
                 echo "<tr>";
                 $hr = 'classMem.php?id=';
                 $hr .= $class["id"];
        
                    
                    echo "<td>". $class['date']."</td>";
                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['classlevel']."</td>";
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classnotes']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
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
                   &rarr; <a href="mailto:adinolfir@outlook.com?subject=SBDC Contact Info">
                       Rich Adinolfi</a></li>';
            echo '<li class="li-none li-large">Vice President  
                &rarr;  <a href="mailto:nkartsonis@me.com?subject=SBDC Info">
                Nan Kartsonis</a></li>';
            echo '<li class="li-none li-large">Questions about costs of events or membership 
                  &rarr; <a href="mailto:shamburgrog23@gmail.com?subject=SBDC Contact Info">
                  Roger Shamburg</a></li>';
            echo '<li class="li-none li-large">General Questions    
                &rarr; <a href="mailto:calamitywjs@gmail.com?subject=SBDC Contact Info">
                Jane Sims</a></li>';
            echo '<li class="li-none li-large">Questions about Classes   
                &rarr; <a href="mailto:dancedirector@sbballroomdance.com?subject=SBDC Contact Info">
                Ann and Dale Pizzitola</a></li>';
            echo '<li class="li-none li-large">Questions about Volunteering   
                &rarr; <a href="mailto:tucsonviv6228@gmail.com?subject=SBDC Contact Info">
                Vivian Herman</a></li>';
           echo '<li class="li-none li-large">Questions about Music or DJing 
                &rarr; <a href="mailto:rb@zedargroup.com?subject=SBDC Contact Info">
                Rick Baumgartner</a></li>';
            echo '<li class="li-none li-large">Questions about the Website   
                &rarr; <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Contact Info">
                Sheila Honey</a></li>';
            echo '<li class="li-none li-large">Registering for or questions about events or classes   
                &rarr; <a href="mailto:countmein@sbballroomdance.com?subject=SBDC Contact Info">
                countmein@sbballroomdance.com</a></li>';
  
        echo '</ul>';
        echo '<br><br>';
    }
    // if (!isset($_SESSION['username'])) { 
    //     echo '<h1 class="section-header">Enter your information below to contact us about New membership: </h1>';
    //     echo '<em><h4> Once you press SUBMIT, 
    //     There will be a time delay while the contact email is generated and sent, so please be patient.</em>
    //     You may want to authorize sbdcmailer@sbballroomdance.com so the emails do not end up in the 
    //         spam/junk folder.<br><br>';
        
    //         echo '<form method="POST" action="actions/contact.php" target="_blank">';
    //         echo '<div class="form-grid3">';
    //             echo '<div class="form-grid-div">';
    //             echo '<label for="firstname">First Name</label><br>';
    //             echo '<input type="text" name="firstname" ><br>';
    //             echo '<label for="lastname">Last Name</label><br>';
    //             echo '<input type="text" name="lastname" ><br>';
    //             echo '<label for="email">Email</label><br>';
    //             echo '<input type="email" name="email" ><br>';
    //             echo '</div>';
           
    //             echo '<div class="form-grid-div">'; 
    //             echo '<label for="danceexperience">How familiar are you with Dance?</label><br>';
    //             echo '<select name = "danceexperience">';
    //                 echo '<option value = "Novice" selected>Never Danced Before</option>';
    //                 echo '<option value = "Beginner" selected>Beginner or It has been a long time</option>';
    //                 echo '<option value = "Intermediate">Had moderate experience dancing</option>';
    //                 echo '<option value = "Advanced">Been Dancing for a long time</option>';
    //             echo '</select> <br>';
               
    //             echo '<label for="dancefavorite">What is your favorite type of dance?</label><br>';
    //             echo '<select name = "dancefavorite">';
    //                 echo '<option value = "Ballroom" selected>Ballroom dances: Foxtrot, Quickstep, Waltz etc.</option>';
    //                 echo '<option value = "Latin">Cha Cha, Rumba, Bolero, American Tango or Argentine Tango, etc.</option>';
    //                 echo '<option value = "Country Western">Western Partner, Two Step, Nightclub, etc.</option>';
    //                 echo '<option value = "Line Dance">Boot Scootin, Cupid Shuffle, Electric Slide, etc.</option>';
    //                 echo '<option value = "Other">I like them all, or I prefer some other kind of dance.</option>';
    //              echo '</select><br><br>';

    //             echo '</div class="form-grid-div">';  
    //             echo '<div class="form-grid-div">';
    //             echo '<p> Tell Us About Yourself or Provide a Message to the Club</p><br>';
    //             echo '<textarea name="message" cols="50" rows="4"></textarea><br><br>';
    //             echo '<button name="submit" type="submit">Send Contact Info</button><br>';
    //             echo '</div>';
    //         echo '</div>';
                
    //         echo '</form>';
    // }
    ?>
    </section>
    </div>

   <div class="container-section ">
   <section id="pictures" class="content">
   <br>  
       <h1 class="section-header">Pictures from Past Events</h1>
       <div class="form-grid3">
       <div class="form-grid-div">
       <h4>2023</h4>
       <ul>
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
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/03-22-2022-March-Dinner-Dance">March Dinner Dance 03 22 2022</a></li> 
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/04-29-2022-SBDC-dinner-dance">April Dinner Dance 04 29 2022</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/2022-09-02-Dine-and-Dance/">Dine and Dance 09 02 2022</a></li>
           <li class="li-none"><a  href="https://sheilahoney.smugmug.com/Nov-3-2022-SBDC-dinner-dance-with-Chuck-Moses">November Dinner Dance 11 03 2022</a></li>

       
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
        <p>Contact one of the board members or webmaster
        <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Website Help">
                webmaster@sbballroomdance.com</a> listed in the about section or
            read the introduction to the website PDF below.<br>
            <a href="https://drive.google.com/file/d/1wbiW8gOqQ-rM9dpyz7p9qIBHKX_2WvDQ/view?usp=sharing">
                Click for the Member's Guide to the Website
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
           <a target="_blank" href="https://drive.google.com/file/d/1lRa1Sr_RIpyj-yLF_9qeZgdC7QGZ5q-X/view?usp=sharing"><em>Click for Robo DJ Guide</em></a><br>
        <a  target="_blank" href="https://zedar.com?x=82351 "
        >DJ Equipment Checkout App</a><br>
        <a target="_blank" href="https://drive.google.com/file/d/1y6GRdngzNWCgx-xSKYwk2wtc-8qK8eGC/view?usp=sharing">DJ Policy</a><br>
        <p>Contact our DJ coordinator,<a href="mailto:rb@zedargroup.com?subject=SBDC DJ Info">
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
        <p>If you think <strong>any </strong> of these opportunities sound interesting, please contact our Director of Volunteers, 
        <a href="mailto:tucsonviv6228@gmail.com?subject=SBDC Info">Vivian Herman</a>
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
   </div>
<?php
  require 'footer.php';
?>
</body>
</html>
