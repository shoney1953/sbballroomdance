<?php
// session_start();
require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$event = new Event($db);
$eventReg = new EventRegistration($db);
$eventRegBefore = new EventRegistration($db);
$mChoices = new DinnerMealChoices($db);
$mealchoices = [];
$regs = $_SESSION['registrations'];
$updID = '';
$fnamID = '';
$lnamID = '';
$emailID = '';
$useridID = '';
$messID = '';
$paidID = '';
$dddinID = '';
$drID = '';


if (isset($_POST['submitUpdateReg'])) {
$fromCC = 'sheila_honey_5@hotmail.com';
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$toCC2 = $webmaster;
$toCC3 = $treasurer;
$toCC4 = '';
$toCC5 = '';
$mailAttachment = ''; 
$emailBody = "An event Registration has been modified by an administrator:<br>";
$replyTopic = "SBDC Event Registration Update ";

    foreach ($regs as $reg) {
        $updID = "upd".$reg['id'];
        $fnamID = "fnam".$reg['id'];
        $lnamID = "lnam".$reg['id'];
        $emailID = "email".$reg['id'];
        $useridID = "userid".$reg['id'];
        $messID = "mess".$reg['id'];
        $paidID = "paid".$reg['id'];
        $dddinID = "dddin".$reg['id'];
        $chID = "ch".$reg['id'];
        $sbID = "sb".$reg['id'];
        $drID = "dr".$reg['id'];
        
        if (isset($_POST["$updID"])) {
   
            $eventRegBefore->id = $reg['id'];
            $eventRegBefore->read_single();
        
            $eventReg->id = $reg['id'];
            $eventReg->firstname = $_POST["$fnamID"];
            $eventReg->lastname = $_POST["$lnamID"];
            $eventReg->eventid = $_POST['eventid'];
            $eventReg->email = $_POST["$emailID"];
            $eventReg->userid = $_POST["$useridID"];
            $eventReg->modifiedby = $_SESSION['username'];
            if (isset($_POST["$paidID"])) {

                $eventReg->paid = $_POST["$paidID"];
            } else {
                $eventReg->paid = $reg['paid'];
            }
            $eventReg->message = $_POST["$messID"];
          
            if (isset($_POST["$dddinID"])) {
     
               $eventReg->ddattenddinner = $_POST["$dddinID"];
            } else {
                $eventReg->ddattenddinner = $reg['ddattenddinner'];
            }
            
            if (isset($_POST["$chID"])) {
                $eventReg->cornhole = $_POST["$chID"];
             } else {
                 $eventReg->cornhole = $reg['cornhole'];
             }
             if (isset($_POST["$sbID"])) {
                $eventReg->softball = $_POST["$sbID"];
             } else {
                 $eventReg->softball = $reg['softball'];
             }
             if (isset($_POST["$messID"])) {
                $eventReg->message = $_POST["$messID"];
             }
             else {
                $eventReg->message = $reg['message'];
             }
            $eventReg->ddattenddance = $reg['ddattenddance'];
            $eventReg->dateregistered = $reg['dateregistered'];

            $mealChoices = [];
         
              $result = $mChoices->read_ByEventId($_POST['eventid']);

                $rowCount = $result->rowCount();
                $num_meals = $rowCount;

                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'mealdescription' => $mealdescription,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid

                        );
                        array_push($mealChoices, $meal_item);
                    } // while
                    if ($rowCount > 0) {
                        foreach ($mealChoices as $choice) {
                          $mcID = "mc".$reg['id'].$choice['id'];
                          if (isset($_POST["$mcID"])) {
                            $eventReg->mealchoice = $choice['id'];
                          }
                        } //foreach
                    } //rowcount
                    if (isset($_POST["$drID"])) {
                        $eventReg->dietaryrestriction = $_POST["$drID"];
                    }
 
            $eventReg->update();
        }
    } 
 
 

}
 $event->id = $eventReg->eventid;
$event->read_single();
if (($event->eventtype === 'Dinner Dance') || ($event->eventtype === 'Dance Party')) {


$emailSubject = 'An Event Registration has been modified for '.$event->eventname.'!';
$emailBody .= '<br>************************************';
$emailBody .= "<br> <strong>Event: ".$event->eventname.
"<br>Type:    ".$event->eventtype.
"<br>DJ  :    ".$event->eventdj.
"<br>Room:    ".$event->eventroom.
"<br>Date:    ".date('M d Y',strtotime($event->eventdate))."</strong><br>"; 

$emailBody .= "Registration id: ".$eventRegBefore->id;
$emailBody .= " modified by: ".$_SESSION['username'];
$emailBody .= "<br>Registration Record before: <br>";
$emailBody .= "<br>First Name: ".$eventRegBefore->firstname;
$emailBody .= "<br>Last Name: ".$eventRegBefore->lastname;
$emailBody .= "<br>Email: ".$eventRegBefore->email;
$emailBody .= "<br>Paid: ".$eventRegBefore->paid;
$emailBody .= "<br>Attend Dinner: ".$eventRegBefore->ddattenddinner;
$emailBody .= "<br>Meal Choice: ".$eventRegBefore->mealchoice;
$emailBody .= '<br>************************************';
$emailBody .= "<br><br>Registration Record after: <br>";
$emailBody .= "<br>First Name: ".$eventReg->firstname;
$emailBody .= "<br>Last Name: ".$eventReg->lastname;
$emailBody .= "<br>Email: ".$eventReg->email;
$emailBody .= "<br>Paid: ".$eventReg->paid;
$emailBody .= "<br>Attend Dinner: ".$eventReg->ddattenddinner;
$emailBody .= "<br>Meal Choice: ".$eventReg->mealchoice;
          
                    sendEmail(
                        $treasurer, 
                        "treasurer", 
                        $fromCC,
                        $fromEmailName,
                        $emailBody,
                        $emailSubject,
                        $replyEmail,
                        $replyTopic,
                        $mailAttachment,
                        $toCC2,
                        $toCC3,
                        $toCC4,
                        $toCC5
                    );
                    $emailBody = "Thanks for registering for the following events:<br>";  
                } else {
                    echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
                }       
            }
    $redirect = "Location: ".$_SESSION['adminurl']."#events";
    header($redirect);
    exit;

?>