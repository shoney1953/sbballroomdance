<?php
// include("includes/mailheader.php");
require '../includes/PHPMailer.php';
require '../includes/SMTP.php';
require '../includes/Exception.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
$classes = $_SESSION['upcoming_classes'];
include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);


$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following classes:<br>";
$emailSubject = '';
$numRegClasses = 0;
$message2Ins = '';
$id_int = 0;

if (isset($_POST['submit'])) {
    $regFirstName1 = htmlentities($_POST['regFirstName1']);
    $regLastName1 = htmlentities($_POST['regLastName1']);
    $regEmail1 = htmlentities($_POST['regEmail1']);

    $regFirstName2 = htmlentities($_POST['regFirstName2']);
    $regLastName2 = htmlentities($_POST['regLastName2']);
    $regEmail2 = htmlentities($_POST['regEmail2']);
    $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL);   

    if (isset($_POST['message2ins'])) {
        $message2Ins = $_POST['message2ins'];
      }
    if (isset($_POST['registerAll'])) {
        $regAll = $_POST['registerAll'];
      }
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if (!$regAll) {
   
        foreach($classes as $class) {
       
          $chkboxID = "cb".$class['id'];
          $insEmail = $class['registrationemail'];
         
          if (isset($_POST["$chkboxID"])) {
              $numRegClasses++;
              $regSelected += [$chkboxID => $insEmail]; 
         } //end isset
        } // end foreach

      }  // end not regall
  
      if ($regAll) {
            $emailSubject = "You have registered for all upcoming Classes!";
            foreach($classes as $class) {
                $classId = $class['id'];
                $emailBody .= "<br> ".$class['classlevel']."  ".$class['classname']."    Instructor(s):   ".
                $class['instructors']."    room:    ".$class['room'].
                "   beginning on date:    ".$class['date']."  time: ".$class['time']."<br>"; 
                
            // do the inserts
                $classReg->firstname = $regFirstName1;
                $classReg->lastname = $regLastName1;
                $classReg->classid = $classId;
                $classReg->email = $regEmail1;
                if(isset($_SESSION['userid'])) {
                    $classReg->userid = $_SESSION['userid'];
                } else {
                    $classReg->userid = 0;
                }
              
                $classReg->create();
                if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                
                    $classReg->firstname = $regFirstName2;
                    $classReg->lastname = $regLastName2;
                    $classReg->classid = $classId;
                    $classReg->email = $regEmail2;
                    if(isset($_SESSION['userid'])) {
                        $classReg->userid = $_SESSION['userid'];
                    } else {
                        $classReg->userid = 0;
                    }
                
                    $classReg->create();
                    } // end regemail 2
            } // end foreach

      } else {
        $emailSubject = "You have registered for selected Classes";
      
        foreach($regSelected as $key => $reg) {
      
        $id_int = (int)(substr($key,2));
      
        foreach ($classes as $class) {
            if ($class['id'] == $id_int) {
                $classId = $class['id'];
                $emailBody .= "<br> ".$class['classlevel']."  ".$class['classname']."    Instructor(s):   ".
                $class['instructors']."    room:    ".$class['room'].
                "   beginning on date:    ".$class['date']."  time: ".$class['time']."<br>"; 
                // do the insert(s)
                $classReg->firstname = $regFirstName1;
                $classReg->lastname = $regLastName1;
                $classReg->classid = $classId;
                $classReg->email = $regEmail1;
                if(isset($_SESSION['userid'])) {
                    $classReg->userid = $_SESSION['userid'];
                } else {
                    $classReg->userid = 0;
                }
                var_dump($classReg);
                $classReg->create();
                if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                
                    $classReg->firstname = $regFirstName2;
                    $classReg->lastname = $regLastName2;
                    $classReg->classid = $classId;
                    $classReg->email = $regEmail2;
                    $classReg->create();
                     } // end regemail2
            } // end if classid
        } // end foreach

       
    } // end foreach
   } // end else
    if (filter_var($regEmail1, FILTER_VALIDATE_EMAIL)) {
        sendEmail($regEmail1, $regFirstName1,  $regLastName1, $emailBody, $emailSubject);;
    } else {
        echo 'Registrant 1 Email is empty or Invalid. Please enter valid email.';
    }
 
  
    if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
        sendEmail($regEmail2,  $regFirstName2, $regLastName2, $emailBody, $emailSubject);
    } 


    $emailSubject = "People have Signed up for your upcoming Class";

    if ($regAll) {
        $classString = '';
        foreach ($classes as $class) {
            $emailBody = "The following individuals have signed up for the class you are going to teach: <br>";
        
            if ($message2Ins) {
                    $emailBody .= "<br>Their Message to the instructor(s) is: ".$message2Ins."<br><br>";
             }
            $emailBody .= "NAME: ".$regFirstName1." ".$regLastName1."<br>  EMAIL:  ".$regLastEmail1."<br>";
            if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                $emailBody .= "And <br>  NAME: ".$regFirstName2." ".$regLastName2."<br>  EMAIL:  ".$regEmail2."<br>";
        
            $insEmail = $class['registrationemail'];   
            $insEmail2 = "";
            $insEmail3 = "";
            $emailBody .= $classString;
            sendEmail($insEmail, $insEmail2, $insEmail3, $emailBody, $emailSubject);
            $classString = '';
            } // end regemail2
        } // end foreach
    } else {
        foreach ($regSelected as $key => $reg) {
    
            $id_int = (int)(substr($key,2));
          
            foreach ($classes as $class) {
                $emailBody = "The following individuals have signed up for the class you are going to teach: <br>";
  
                if ($message2Ins) {
                    $emailBody .= "<br>Their Message to the instructor(s) is: ".$message2Ins."<br><br>";
                }
                $emailBody .= "NAME: ".$regFirstName1." ".$regLastName1."<br>    EMAIL:  ".$regEmail1."<br>";
                if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
                    $emailBody .= "And <br>  NAME: ".$regFirstName2." ".$regLastName2."<br>  EMAIL:  ".$regEmail2."<br>";
                }
                $classString = '';
                $classString = "<br>Class: ".$class['classname']."<br>";

                if ($class['id'] == $id_int) {
                    $insEmail = $class['registrationemail'];   
                     $insEmail2 = "";
                     $insEmail3 = "";
                     $emailBody .= $classString;
                     sendEmail($insEmail, $insEmail2, $insEmail3, $emailBody, $emailSubject);
                     $classString = '';
                  
                }
            }
    }

    $redirect = "Location: ".$_SESSION['homeurl'];

  // $conn->close();
   header($redirect);
 exit;
} // end submit

}
function sendEmail($toEmail, $toFirstName, $toLastName, $emailBody, $emailSubject)
{
    $mail = new PHPMailer(true);
    $toName = $toFirstName."  ".$toLastName;
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
        $mail->addReplyTo('sbdcemailer@gmail.com', 'Class Registration');
        // $mail->addCC('sheila_honey_5@hotmail.com');
        // $mail->addBCC('sheila_honey_5@hotmail.com');

        //Attachments
        // $mail->addAttachment('img/Membership Form 2022 Dance Club.pdf');         //Add attachments
    

        //Content
          //Set email format to HTML
         

        $mail->isHTML(true);   
        
        $mail->Subject = $emailSubject; 
                      
        
        $mail->Body    = $emailBody;
        /*$mail->AltBody = 'This is the body in plain text for non-HTML mail  clients'; */

        $mail->send();
        echo '<br>Message has been sent<br>';
    } catch (Exception $e) {
        echo "<br>Message could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
    }
    $mail->smtpClose();
}

?>