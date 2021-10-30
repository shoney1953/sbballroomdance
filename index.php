<?php

session_start();
$num_classes = 0;
$num_events = 0;
$classes = [];
$events = [];


if ($_SERVER['SERVER_NAME'] === 'localhost') {
    /* if in local testing mode */
    $server = "localhost";
    $username = "root";
    $password = "2021Idiot";
    $db = "mywebsite"; 
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
      /*Get Heroku ClearDB connection information */
$url      = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
}
$conn = new mysqli($server, $username, $password, $db);


// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error."</p>");
} 
/* get events */
$sql = "SELECT id, 
    eventname,
    eventtype, 
    eventroom, 
    eventdesc,
    eventdate,
    eventcost,
    eventnumreg,
    eventform,
    eventnumregistered
         FROM events where eventdate >= current_date() ;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {
      
        $num_events++;
        $events[$num_events] = [
            'id' => $row["id"],
            'eventname' => $row["eventname"],
            'eventtype' => $row["eventtype"],
            'eventroom' => $row["eventroom"],
            'eventdesc' => $row["eventdesc"],
            'eventdate' => $row["eventdate"],
            'eventcost' => $row["eventcost"],
            'eventnumreg' => $row["eventnumreg"],
            'eventform' => $row["eventform"],
            'eventnumregistered' => $row["eventnumregistered"]
        ];
        
    }
}
$_SESSION['events'] = $events;
/* get classes */
$sql = "SELECT id, 
    classname, 
    registrationemail, 
    instructors, 
    classlimit, 
    room, 
    numregistered,
    date FROM danceclasses where date >= current_date();";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
   
    while ($row = $result->fetch_assoc()) {
        $num_classes++;
        $classes[$num_classes] = [
            'id' => $row["id"],
            'classname' => $row["classname"],
            'registrationemail' => $row["registrationemail"],
            'instructors' => $row["instructors"],
            'classlimit' => $row["classlimit"],
            'room' => $row["room"],
            'date' => $row["date"],
            'numregistered' => $row['numregistered']
        ];
        
    }

$_SESSION['classes'] = $classes;



$conn->close();
}


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
        <li><a 
href="https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20">
         Activities Calendar</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a 
href="https://drive.google.com/drive/folders/1LjnghlW8uftZHNxDG1YN4hbkq5AU2f7f?usp=sharing">
DJ Documents</a>
    </li>
        </ul>
     </div>
</nav>
    <div class="hero">
        <div class="container">
            <h1 >Welcome to the Saddlebrooke Ballroom Dance Club Website</h1><br>
            <p>We are a primarily social club that provides, lessons,
                 and opportunities to dance and socialize.</p>
           <p>We're not "strictly ballroom". Latin, Western, Line Dance 
               are also part of our repetoire. </p>
        </div>
    </div>
    
    <div class="section-back">
  
    <section id="events" class="container content">
    <hr>
      <br>
        <h1 class="section-header">Upcoming Events</h1><br>
        <table>
            <tr>
                <th>Events ID    </th>
                <th>Event Name    </th>
                <th>Event Type    </th>
                <th>Event Description</th>
                <th>Event Date</th>
                <th>Event Room</th>
                <th>Event Cost</th>
                <th># Registered></th>
         
               
            </tr>
            <?php 
            $eventNumber = 0;
            foreach($events as $event) {
                 $eventNumber++;
                  echo "<tr>";
                    echo "<td>".$event['id']."</td>";
                    echo "<td>".$event['eventname']."</td>";
                    echo "<td>".$event['eventtype']."</td>";
                    echo "<td>".$event['eventdesc']."</td>";
                    echo "<td>".$event['eventdate']."</td>";
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
    <hr>
      <br>
        <h1 class="section-header">Upcoming Classes Available</h1><br>
        <table>
            <tr>
                <th>Class ID    </th>
                <th>Class    </th>
                <th>Registration Email    </th>
                <th>Instructors    </th>
                <th>Class Limit    </th>
                <th># Registered </th>
                <th>Room    </th>
                <th>Date    </th>
               
            </tr>
            <?php 
            $classNumber = 0;
            foreach($classes as $class)
             {
                 $classNumber++;
                  echo "<tr>";
                    echo "<td>".$class['id']."</td>";
                    echo "<td>".$class['classname']."</td>";
                    echo "<td>".$class['registrationemail']."</td>";
                    echo "<td>".$class['instructors']."</td>";
                    echo "<td>".$class['classlimit']."</td>";
                    echo "<td>".$class['numregistered']."</td>";
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['date']."</td>";
               
                  echo "</tr>";
              }
              
            ?> 
        </table>
        <br>
        
        
        <h3> Enter Information Below to Register for all or Selected Classes </h3>
        
        <form method="POST" action="register.php">
                <label for="regName1">First Registrant First and Last Name (Required)</label>
                <input type="text" name="regName1" >
                <label for="regEmail1">Frist Registrant Email (Required)</label>
                <input type="email" name="regEmail1" ><br>
                <label for="regName2">Second Registrant First and Last Name(optional)</label>
                <input type="text" name="regName2" >
                <label for="regEmail2">Second Registrant Email (optional)</label>
                <input type="email" name="regEmail2" ><br>
                <label for="danceexperience">How familiar are you with Dance?</label>
                <select name = "danceexperience">
                    <option value = "Beginner" selected>Beginner or Its been a long time</option>
                    <option value = "Intermediate">Had moderate experience dancing</option>
                    <option value = "Advanced">Been Dancing for a long time</option>
                </select>
                <br>
                <label for="dancefavorite">What is your favorite type of dance?</label>
                <select name = "dancefavorite">
                    <option value = "Ballroom" selected>Ballroom dances: Foxtrot, Quickstep, Waltz etc.</option>
                    <option value = "Latin">Cha Cha, Rumba, Bolero, American Tango or Argentine Tango, etc.</option>
                    <option value = "Country Western">Western Partner, Two Step, Nightclub, etc.</option>
                    <option value = "Line Dance">Boot Scootin, Cupid Shuffle, Electric Slide, etc.</option>
                    <option value = "Other">I like them all, or I prefer some other kind of dance.</option>
                 </select>
                 <br><br>
                 <p> Message to Instructor(s) (Optional) </p><br>
                <textarea name="message2ins" cols="200" rows="7"></textarea><br>
                <hr>
                <h4 ><em>To Enroll, Please select either All Classes or One or More of the Classes Listed</em></h4><br>
                <input type="checkbox" id="registerAll" name="registerAll" value="Register for All Classes">
                <label for="registerAll"><b> I/We would like to register for all available Classes </b></label><br>
                <p>OR</p>
                <?php
                foreach($classes as $class) {
                    $chkboxID = "cb".$class['id'];
                    $className = $class['classname'];
                    echo "<input type='checkbox' name='$chkboxID'>";
                    echo "<label for='$chkboxID'> I/We would like to register for: $className </label><br>";
                }
                 ?>
                 <br>
                <button name="submit" type="submit">Submit</button>
                
            </form>
    </section>
    </div>
    <div class="section-back">
    <section id="about" class="container content">
        <hr>
             
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
    </section>
    </div>
    <div class="section-back">
    <section id="contact" class="container content">
        <hr>
        <h2 class="section-header">Enter your information below to contact us: </h2>
            <form method="POST" action="contact.php">
                <label for="name">First and Last Name</label><br>
                <input type="text" name="name" ><br>
                <label for="email">Email</label><br>
                <input type="email" name="email" ><br>
                <p> Tell Us About Yourself </p><br>
                <textarea name="message" cols="200" rows="10"></textarea><br>
                <button name="submit" type="submit">Submit</button>
                
            </form>
    </section>
   <hr>
   </div>
</body>
</html>
