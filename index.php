<?php
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    /*Get Heroku ClearDB connection information */
$url      = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

/* $active_group = 'default';
$query_builder = TRUE;
$active_group = 'default';
$query_builder = TRUE; */

/*$db['default'] = array(
    'dsn'    => '',
    'hostname' => $cleardb_server,
    'username' => $cleardb_username,
    'password' => $cleardb_password,
    'database' => $cleardb_db,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
); */
// Create connection



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

$num_classes = 0;
$classes = [];

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo " <h1 style="center">Rows from the Dance Classes Table </h1> <br><hr>";
    while ($row = $result->fetch_assoc()) {
        $num_classes++;
        $classes[$num_classes] = [
            'classname' => $row["classname"],
            'registrationemail' => $row["registrationemail"],
            'instructors' => $row["instructors"],
            'classlimit' => $row["classlimit"],
            'room' => $row["room"],
            'date' => $row["date"]
        ]
 
       /* echo 
        "ID: ".$row["id"]
        ."  Class:  ".$row["classname"]
        ."  Registration Email:  ".$row["registrationemail"]
        ."  Instructors:  ".$row["instructors"]
        ."  Class Limit:  ".$row["classlimit"]
        ."  Room:  ".$row["room"]
        ."  Date: ".$row["date"];
        echo "<br><hr>"; */
        
    }
    var_dump($classes);
} 

$conn->close();

function sendEmail($toEmail, $toName)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        /* $mail->SMTPDebug = SMTP::DEBUG_SERVER;   */                   //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'sbdcemailer@gmail.com';                     //SMTP username
        $mail->Password   = '2021SendEmail';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = "587";                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('sbdcemailer@gmail.com', 'SBDC Ballroom Dance Club');
        $mail->addAddress($toEmail, $toName);     //Add a recipient
        /*$mail->addAddress('ellen@example.com');               //Name is optional */
        $mail->addReplyTo('sbdcemailer@gmail.com', 'Information');
        $mail->addCC('sheila_honey_5@hotmail.com');
        $mail->addBCC('sheila_honey_5@hotmail.com');

        //Attachments
        $mail->addAttachment('img/Membership Form 2022 Dance Club.pdf');         //Add attachments
    

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Thanks for Contacting us at SBDC Ballroom Dance Club!';
        $mail->Body    = "We'd love to have <b>you</b> as a new member to our club.<br>
         Please see attached membership form if you are interested.
         <br>Thanks!
         <br>SBDC Ballroom Dance Club";
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    $mail->smtpClose();
}
// sendEmail('sheila_honey_5@hotmail.com', 'Sheila Honey');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Sticky Navigation</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
            <h1 class="logo"><a href="index.html">My Website</a></h1>
            <ul>
                <li><a href="#" class="current">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </nav>
    <div class="hero">
        <div class="container">
            <h1>Welcome to the Saddlebrooke Ballroom Dance Club Website</h1>
            <p>We are a primarily social club that provides, lessons, and opportunities to dance and socialize.</p>
        </div>
    </div>
    <section class="container content">
        <h2>Classes Available</h2>
        <table>
            <tr>
                <th>Class</th>
                <th>Registration Email</th>
                <th>Instructors</th>
                <th>Class Limit</th>
                <th>Room</th>
                <th>Date</th>
            </tr>
            <?php
              foreach($classes as $class) {
                  echo "<tr>";
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
        
   
</body>
</html>
