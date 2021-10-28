<?php

session_start();
$num_classes = 0;
$classes = [];
  

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

$sql = "SELECT id, 
    classname, 
    registrationemail, 
    instructors, 
    classlimit, 
    room, 
    date FROM danceclasses;";

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
            'date' => $row["date"]
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
            <h1 >Welcome to the Saddlebrooke Ballroom Dance Club Website</h1>
            <p>We are a primarily social club that provides, lessons,
                 and opportunities to dance and socialize.</p>
        </div>
    </div>
    <section id="classes" class="container content section-back">
        <hr>
        <h1 class="section-header">Classes Available</h1><br>
        <table>
            <tr>
                <th>Class ID    </th>
                <th>Class    </th>
                <th>Registration Email    </th>
                <th>Instructors    </th>
                <th>Class Limit    </th>
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
                    echo "<td>".$class['room']."</td>";
                    echo "<td>".$class['date']."</td>";
               
                  echo "</tr>";
              }
              
            ?> 
        </table>
        <hr>
        <h1> Enter Information Below to Register for all or Selected Classes </h1>
        <hr>
        <form method="POST" action="register.php">
                <label for="regName1">First Registrant First and Last Name</label>
                <input type="text" name="regName1" >
                <label for="regEmail1">Email</label>
                <input type="email" name="regEmail1" >
                <label for="regName2">Second Registrant First and Last Name(optional)</label>
                <input type="text" name="regName2" >
                <label for="regEmail2">Email</label>
                <input type="email" name="regEmail2" >
                <input type="checkbox" id="registerAll" name="registerAll" value="Register for All Classes">
                <label for="registerAll"> I/We would like to register for all available Classes</label><br>
                <?php
                foreach($classes as $class) {
                    $chkboxID = "cb".$class['id'];
                    $className = $class['classname'];
                    echo "<input type='checkbox' name='$chkboxID'>";
                    echo "<label for='$chkboxID'> I/We would like to register for: $className </label><br>";
                }
                 ?>
                <button name="submit" type="submit">Submit</button>
                
            </form>
    </section>
    <section id="about" class="container content">
        <hr>

        <h1 class="section-header">What We are About</h1><br>
        <p>If you love all kinds of dancing, we're the club for you. </p>
        <p> We don't just do Ballroom dance - at our dances/practices, we play 
            music for Ballroom Dance, Western and Western Partner Dance, Line Dance, and Latin Dances.</p>
        <p>Our members can go to any class we provide free. We also have several times during the week
            available for practice - some with D.J.s, some time you can bring your favorite music.
            Potential members may attend one class free to see if they like it.</p>
        <p>Our members also receive reduced rates for our dinner dances, but you may attend as a guest
            if you'd like to put your toe in the water before committing. </p>
        <a style="font-weight: bold"
        href="https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20">
         Click Here to See the Activities Calendar for times and dates.
        </a>
    </section>
    <section id="contact" class="container content section-back">
        <hr>
        <h2 class="section-header">Enter your information below to contact us: </h2>
            <form method="POST" action="contact.php">
                <label for="name">First and Last Name</label>
                <input type="text" name="name" >
                <label for="email">Email</label>
                <input type="email" name="email" >
                <button name="submit" type="submit">Submit</button>
                
            </form>
    </section>
   
</body>
</html>
