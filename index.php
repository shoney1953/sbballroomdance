<?php
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
echo '<h1>Testing SBDC Ballroom Dance on Heroku</h1>';
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

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo " <h1>Rows from the Dance Classes Table </h1> <br><hr>";
    while ($row = $result->fetch_assoc()) {
        echo 
        "ID: ".$row["id"]
        ."  Class:  ".$row["classname"]
        ."  Registration Email:  ".$row["registrationemail"]
        ."  Instructors:  ".$row["instructors"]
        ."  Class Limit:  ".$row["classlimit"]
        ."  Room:  ".$row["room"]
        ."  Date: ".$row["date"];
        echo "<br><hr>";
        $num_classes++;
    }
} else {
    echo 'Dance Class TABLE EMPTY';

    
}
echo "<h2> Total Number of Classes: ".$num_classes."</h2><br>";
$conn->close();

function sendEmail($toEmail, $toName)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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
sendEmail('sheila_honey_5@hotmail.com', 'Sheila Honey');
?>
