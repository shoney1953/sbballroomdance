<?php
session_start();
$_SESSION['homeurl'] = $_SERVER['REQUEST_URI'];
include_once 'config/Database.php';
include_once 'models/Event.php';
include_once 'models/DanceClass.php';
$num_classes = 0;
$num_events = 0;
$classes = [];
$events = [];
$upcomingClasses = [];
$upcomingEvents = [];
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

if($rowCount > 0) {

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
            "eventdesc" => html_entity_decode($eventdesc),
            "eventroom" => $eventroom,
            'eventnumregistered' => $eventnumregistered
        );
        array_push( $events, $event_item);
    
        if ($compareDate <= $row['eventdate']) {
            array_push( $upcomingEvents, $event_item);
        }
    }
  

} else {
   echo 'NO EVENTS';

}


/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();
$num_classes = $rowCount;

if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $class_item = array(
            'id' => $id,
            'classname' => $classname,
            'classlevel' => $classlevel,
            'classlimit' => $classlimit,
            'date' => $date,
            'time' => $time,
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            'numregistered' => $numregistered
        );
        array_push( $classes, $class_item);

        if ($compareDate <= $row['date']) {
            array_push( $upcomingClasses, $class_item);
        }
    }

} else {
   echo 'NO CLASSES';

}

$_SESSION['classes'] = $classes;
$_SESSION['upcoming_classes'] = $upcomingClasses;

// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance Beta</title>
</head>
<body>
<nav class="nav">
    <div class="container">
     <h1 class="logo"><a href="index.html">SBDC Ballroom Dance Club</a></h1>
     <ul>
        <li><a href="#" class="current">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="#classes">Classes</a></li>
        <li><a  target="_blank"
href="https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20">
         Activities Calendar</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#pictures">Picture Gallery</a></li>
        <li><a  target="_blank"
href="https://drive.google.com/drive/folders/1LjnghlW8uftZHNxDG1YN4hbkq5AU2f7f?usp=sharing">
DJ Documents</a>
    </li>
        <li><a href="admin.php">Admin</a></li>
        </ul>
     </div>
</nav>
    <div class="hero">
        <div class="container">
            <h1 >Welcome to the SaddleBrooke Ballroom Dance Club Website</h1><br>
         
            <p>We are a primarily social club that provides dance lessons,
                 and opportunities to dance and socialize.</p><br>
            <p>We are comprised of members from SaddleBrooke HOA 1 and HOA 2.</p><br>
           <p>We're not <em>"strictly ballroom"</em>. Latin, Western, and Line Dance 
               are also part of our repetoire. </p><br>
        </div>
    </div>
    
    <div class="section-back">
    <section id="events" class="container content">

      <br>
        <h1 class="section-header">Upcoming Events</h1><br>
        <table>
            <tr>
                <th>Event Date</th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th>  
                <th>Event DJ</th>            
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Registered </th>
         
               
            </tr>
            <?php 
            $eventNumber = 0;
            foreach($upcomingEvents as $event) {
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
                  echo "</tr>";
              }
         
            ?> 
        </table>
        <br>
    </section>
    </div>
   <div class="section-back">
    <section id="classes" class="container content">
   
      <br>
        <h1 class="section-header">Upcoming Classes Available</h1><br>
        <table>
            <tr>
                <
                <th>Start Date</th>
                <th>Time    </th>
                <th>Class    </th>
                <th>Level    </th>
                <th>Registration Email    </th>
                <th>Instructors    </th>
                <th>Class Limit    </th>
                <th># Registered </th>
                <th>Room    </th>
                
               
            </tr>
            <?php 
            $classNumber = 0;
            foreach($upcomingClasses as $class)
             {
                 $classNumber++;
                  echo "<tr>";
                    
                    echo "<td>". $class['date']."</td>";
                    echo "<td>".$class['time']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['classlevel']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
                    echo "<td>".$class['numregistered']."</td>";
                    echo "<td>".$class['room']."</td>";

                  echo "</tr>";
              }
              
            ?> 
        </table>
        <br>
        
     
        <h3> Enter Information Below to Register for all or Selected Classes </h3>
        
        <form method="POST"  action="actions/register.php">
        <div class="form-grid2">
      
       
            <div>
                <br>
                <label for="regFirstName1">First Registrant First Name (Required)</label><br>
                <input type="text" name="regFirstName1" ><br>
                <label for="regLastName1">First Registrant Last Name (Required)</label><br>
                <input type="text" name="regLastName1" ><br>
                <label for="regEmail1">First Registrant Email (Required)</label><br>
                <input type="email" name="regEmail1" ><br>
              <br>
            </div>
            <div>
                <br>
                <label for="regFirstName2">Second Registrant First Name(optional)</label><br>
                <input type="text" name="regFirstName2" ><br>
                <label for="regLastName2">Second Registrant Last Name(optional)</label><br>
                <input type="text" name="regLastName2" ><br>
                <label for="regEmail2">Second Registrant Email (optional)</label><br>
                <input type="email" name="regEmail2" ><br>
                <br>
            </div>
            <div>
              <label for="message2ins">Message to Instructor(Optional)</label><br>
               <textarea id="message2ins" name="w3review" rows="3" cols="100"></textarea>
            </div>
      
            <div>
                <ul class=list-box>
                <h4 style="text-decoration: underline;color: black"><em>To Enroll -- Please select either All Classes   or   One or More of the Classes Listed</em></h4><br>
                <li class="list-none">
                <input type="checkbox" id="registerAll" name="registerAll" value="Register for All Classes">
                <label for="registerAll"><b> I/We would like to register for all available Classes </b></label><br>
                </li>
              
                <p>OR</p>
              
                <?php
                foreach($upcomingClasses as $class) {
                    echo '<li class="list-none">';
                    $chkboxID = "cb".$class['id'];
                    $classString = " ".$class['classname']." ".$class['classlevel']." ".$class['date']." ";
                    echo "<input type='checkbox' name='$chkboxID'>";
                    echo "<label for='$chkboxID'> I/We would like to register for:
                        <strong>$classString </strong></label><br>";
                    }
                    echo '</li>';
                 ?>
                </ul>
                <br><br>
                 <button name="submit" type="submit">Submit</button><br>
            </div> 
           
            </form>
    </section>
    </div>
    <div class="section-back">
    <section id="about" class="container content">
 
             
        <h2 class="section-header">What We are About</h2><br>
        <p>If you love all kinds of dancing, we're the club for you. </p>
        <p> We don't just do Ballroom dance - at our dances/practices, we play 
            music for Ballroom Dance, Western and Western Partner Dance, Line Dance, and Latin Dances.</p>
        <p>Our members can go to any class we provide free. Prospective members may attend one class free to see if they like it. </p>
        <p>We also have several times during the week
            available for practice - some with D.J.s, sometimes you can bring your favorite music. These
            sessions desginated as "Open Dance" are not restricted to members.</p>
        <p>Our members also receive reduced rates for our dinner dances, but you may attend as a guest
            if you'd like to put your toe in the water before committing. </p><br>
        <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20">
         Click Here to See the Activities Calendar for times and dates.
        </a>
        <br>
        <h3>Current Board Members</h3>
        <ul>
            <li class="li-none li-large">Brian Hand, President</li>
            <li class="li-none li-large">Richard Adinolfi, Vice-President</li>
            <li class="li-none li-large">Dottie Adams, Treasurer</li>
            <li class="li-none li-large">Wanda Ross, Secretary</li>
        </ul>
        <br>
        <ul>
        <li class="li-none"><a href="img/Membership Form 2022 Dance Club.pdf">Click Here for Membership Form</a></li><br>
        </ul>
    </section>
    </div>
    <div class="section-back">
    <section id="contact" class="container content">
  
        <h2 class="section-header">Enter your information below to contact us: </h2>
        
            <form method="POST" action="actions/contact.php">
            <div class="form-grid2">
                <div>
                <label for="firstname">First Name</label><br>
                <input type="text" name="firstname" ><br>
                <label for="lastname">Last Name</label><br>
                <input type="text" name="lastname" ><br>
                <label for="email">Email</label><br>
                <input type="email" name="email" ><br>
                </div>
           
                <div>  
                <label for="danceexperience">How familiar are you with Dance?</label><br>
                <select name = "danceexperience">
                    <option value = "Beginner" selected>Beginner or It's been a long time</option>
                    <option value = "Intermediate">Had moderate experience dancing</option>
                    <option value = "Advanced">Been Dancing for a long time</option>
                </select>
                <br>
                <label for="dancefavorite">What is your favorite type of dance?</label><br>
                <select name = "dancefavorite">
                    <option value = "Ballroom" selected>Ballroom dances: Foxtrot, Quickstep, Waltz etc.</option>
                    <option value = "Latin">Cha Cha, Rumba, Bolero, American Tango or Argentine Tango, etc.</option>
                    <option value = "Country Western">Western Partner, Two Step, Nightclub, etc.</option>
                    <option value = "Line Dance">Boot Scootin, Cupid Shuffle, Electric Slide, etc.</option>
                    <option value = "Other">I like them all, or I prefer some other kind of dance.</option>
                 </select>
                 <br><br>
              
                </div>   
                <div>
                <p> Tell Us About Yourself </p><br>
                <textarea name="message" cols="100" rows="3"></textarea><br><br>
                <button name="submit" type="submit">Submit</button><br>
                </div>
            </div>
                
            </form>
    </section>
    </div>

   <div class="section-back">
   <section id="pictures" class="container content">
       <h1>Pictures from Past Events</h1>
 
       <ul>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Sbdc-halloween-party-10-30-2021">Halloween Party 10 30 2021</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/02-15-2020-Sweetheart-Dance">Sweetheart Dance 02 15 2020</a></li>
           <li class="li-none"><a href="https://sheilahoney.smugmug.com/Masquerade-ball-01-11-2020-SBDC">Masquerade Dance 01 11 2020</a></li>
       </ul>
       <br>
   </section>
   </div>
</body>
</html>
