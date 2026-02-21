<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");

$events = $_SESSION['upcoming_events'];

$fromCC = $webmaster;
$replyEmail = $webmaster;
$fromEmailName = 'SBDC Ballroom Dance Club';
$mailAttachment = "";
$mailAttachment2 = "";
$replyTopic = "SBDC Event Registration";
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$eventInst = new Event($db);
$user = new User($db);
$mealchoices = new DinnerMealChoices($db);
$message = '';
$result = 0;
$danceCost = 0;
$regSelected = [];
$regAll = '';
$emailBody = "Thanks for registering for the following SBDC event(s):<br>";
$emailSubject = '';
$eventNum = 0;
$regUserid1 = 0;
$regUserid2 = 0;
$toCC2 = '';
$toCC3 = '';
$toCC4 = '';
$toCC5 = '';
$smealCHK1 = '';
$mChoices = [];
$meal1 = '';
$mealprice1 = '';
$mealid1 = 0;
$mealproductid1 = '';
$mealpriceid1 = '';
$dietaryRestriction1 = '';
$smealCHK2 = '';

$meal2 = '';
$mealprice2 = '';
$mealid2 = 0;
$mealproductid2 = '';
$mealpriceid2 = '';
$dietaryRestriction2 = '';
$drID1 = '';
$drID2 = '';
$id_int = 0;

$num_registered = 0;
$currentDate = new DateTime();

if (!isset($_POST['submitEventReg'])) {

   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
}
  
    $regFirstName1 = htmlentities($_POST['regFirstName1']);
    $regLastName1 = htmlentities($_POST['regLastName1']);
    $regEmail1 = htmlentities($_POST['regEmail1']);  
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail1)) {    
        $regUserid1 = $user->id;
        if ($user->partnerId !== '0') {
            $eventReg->dwop = 0;
        } else {
            $eventReg->dwop = 1;
        }
       }
   if (isset($_POST['message'])) {
       $message = htmlentities($_POST['message']); 
       }
   if (isset($_POST['regEmail2'])) {
    $regFirstName2 = htmlentities($_POST['regFirstName2']);
    $regLastName2 = htmlentities($_POST['regLastName2']);
    $regEmail2 = htmlentities($_POST['regEmail2']);  
    $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail2)) {    
        $regUserid2 = $user->id;
      } else {
          $regUserid2 = 0;
      }
      
    }

    
    $emailSubject = "You have registered for SBDC event(s)";

    foreach ($events as $event) {

        $chkboxID = "ev".$event['id'];
        $chkboxID2 = "dd".$event['id'];
        $chkboxID3 = "ch".$event['id'];
        $chkboxID4 = "sb".$event['id'];
        $messID = "mess".$event['id'];
        $message = '';
        if (isset($_POST["$messID"])) {            
            $message = $_POST["$messID"];
        }
       if (isset($_POST["$chkboxID"])) {
      
        $eventNum = (int)substr($chkboxID,2);
// matches event

         if ($event['id'] == $eventNum) {

          $eventId = $event['id'];

       
              $drID1 = "dr1".$event['id'];
              if (isset($_POST["$drID1"])) {
                $dietaryRestriction1 = $_POST["$drID1"];
              }
               $drID2 = "dr2".$event['id'];
              if (isset($_POST["$drID2"])) {
             
                $dietaryRestriction2 = $_POST["$drID2"];
              }
              
              $result = $mealchoices->read_ByEventId($event['id']);

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
                        array_push($mChoices, $meal_item);              
                    } // while

                      foreach ($mChoices as $choice) {
  
                         $smealCHK1 = 'sm1'.$choice['id'];

                         if (isset($_POST["$smealCHK1"])) {
                          
                                  $mealid1 = $choice['id'];
    
                                  $meal1 = $choice['mealname'];
                                  $mealprice1 = $choice['memberprice'];
                                  $mealpriceid1 = $choice['priceid'];
                            
                               } //smeal1
                         $smealCHK2 = 'sm2'.$choice['id'];

                         if (isset($_POST["$smealCHK2"])) {   
                             
                                  $mealid2 = $choice['id'];
                                  $meal2 = $choice['mealname'];
                                  $mealprice2 = $choice['memberprice'];
                                  $mealpriceid2 = $choice['priceid'];
                       
                               } //smeal2
                      } // foreach choice
                   
                }  // rowCount
      
                $num_registered++;
                $eventId = $event['id'];
                $emailBody .= "<br><strong> Event: ".$event['eventname'].
                "<br>Type:    ".$event['eventtype'].
                "<br>DJ  :    ".$event['eventdj'].
                "<br>Room:    ".$event['eventroom'].
                "<br>Date:    ".date('M d Y',strtotime($event['eventdate']))."</strong><br>"; 
                // if ($event['orgemail'] != null) {
                //     $toCC2 = $event['orgemail'];
                // }
                // else {        
                //         $toCC2 = '';                    
                // }
                switch ($event['eventtype']) {
                  case "BBQ Picnic":
                    if (isset($_POST["$chkboxID2"])) {
                        $emailBody .= "You have chosen to attend dinner.<br>";
                    }
                    if (isset($_POST["$chkboxID3"])) {
                        $emailBody .= "You have chosen to play cornhole.<br>";
                    }
                    if (isset($_POST["$chkboxID4"])) {
                        $emailBody .= "You have chosen to play softball.<br>";
                    }
                    $emailBody .= "If your choices differ from your partner's, please contact the event coordinator or reply to this email.<br>";
                    $emailBody .= "You may also change your or your partner's choices via your profile.<br>";
                    break;
                  case "Dance Party":
                         if (isset($_POST["$chkboxID2"])) {
                                $emailBody .= "You have chosen to attend dinner.<br>";
                           
                                    if ($meal1 !== '') {
                                        $emailBody .= "You selected ".$meal1." at the cost of $".number_format($mealprice1/100,2)."";   
                                        $danceCost = $danceCost + $mealprice1;    
                                        if ($dietaryRestriction1 != '') {
                                          $emailBody .= " with a dietary restrictions of ".$dietaryRestriction1.".<br>";
                                       } else {
                                          $emailBody .= ".<br>";
                                       }
                             
                                    

                            
                                    if ($meal2 !== '') {
                                        $emailBody .= "You also selected ".$meal2." at the cost of $".number_format($mealprice2/100,2)."";    
                                        $danceCost = $danceCost + $mealprice2;    
                                      if ($dietaryRestriction2 != '') {
                                          $emailBody .= " with a dietary restrictions of ".$dietaryRestriction2.".<br>";
                                        } else {
                                          $emailBody .= ".<br>";
                                        }
                                    }
             
                                    $emailBody .= "Total Cost of the Dance will be ".number_format($danceCost/100,2).".<br><br>";   
                                } // testmode

                        } else {
                            $emailBody .= "You have chosen not to attend dinner before the dance.<br>";
        
                            $emailBody .= "Member Cost of the Dance Only will be $".number_format($event['eventcost']).".<br>";  
                            $emailBody .= "Guest Cost of the Dance Only will be $".number_format($event['eventguestcost']).".<br>"; 
                        }    
                          $emailBody .= "Please submit your fee prior to the dance as indicated on the form.<br>";  
                     
                           $emailBody .= "You may optionally choose to pay online. Simply go to your profile and click the pay button.<br>";
                    
                    break;
                    case "Dinner Dance":

                       
                            if ($meal1 !== '') {
                                $emailBody .= "You selected ".$meal1." at the cost of ".number_format($mealprice1/100,2).""; 
                                $danceCost = $danceCost + $mealprice1;
                                if ($dietaryRestriction1 !== '') {
                                    $emailBody .= " with a dietary restiction of ".$dietaryRestriction1.".<br>";
                                 } else {
                                     $emailBody .= ".<br>";
                                 }
                             } 


                            if ($meal2 !== '') {
                                $emailBody .= "You also selected ".$meal2." at the cost of ".number_format($mealprice2/100,2).".<br>";    
                                $danceCost = $danceCost + $mealprice2;    
                                if ($dietaryRestriction2 !== '') {
                                    $emailBody .= " with a dietary restiction of ".$dietaryRestriction2.".<br>";
                                 } else {
                                     $emailBody .= ".<br>";
                                 }  
                                } // meal2 

                           $emailBody .= "<br>Cost of the Dance will be ".number_format($danceCost/100,2).".<br>";  
                    
                           
                    

                    break;
                    default:
                     $emailBody .= "Event type unknown!<br>";
                    break;
                    
                } //switch end


             
                    if ($event['eventcost'] > 0) {
                        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                        $coststr =  "Member Minimum Cost for the Dance is: "
                            .$fmt->formatCurrency($event['eventcost'], 'USD')."<br>
                            Check the form for specific costs.<br>";
            
                        $emailBody .= $coststr;
                        // $toCC2 = $treasurer;
                    }
                 

                    if (!$event['eventform']) {
                        $emailBody .= '<br>The signup form specifying meal choices and associated costs
                        is not currently available, but
                        you will receive an email when it is. The email will have the signup form
                        attached.<br>';
                    }
                    if ($event['eventform']) {
                        if (substr($event['eventform'],0,4) === 'http') {
                                                    $actLink= "<a href='".$event['eventform']."'>
                        Click to view event Form</a><br>";
                       $emailBody .= 'There is a signup form to submit registration details and payment.<br>';
                       $emailBody .= "Click on <em>PRINT</em> in the Form column of the event listing
                        on the website to open the form.<br> Or<br>$actLink";
                        } else {
                        $mailAttachment = '..uploads/forms/'.$event['eventform'];
                       $emailBody .= 'There is an attached signup form to submit registration details and payment.<br>';

                        }
                  

    
                    }
                //  }
                // }
                if ($message) {
                    $emailBody .= '<br> MESSAGE from Registrant: <br>';
                    $emailBody .= $message;
                    $emailBody .= '<br> <br>';
                }
            
                $emailBody .= '<br>You may login to the website and look at 
                <strong>Your Profile</strong> to see which events and
                classes you have registered for, 
                and whether we have received payment for those that require it.';
                $emailBody .= '<br> <br>';
          
                // do the insert(s)
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->registeredby = $_SESSION['username'];
                $eventReg->userid = $regUserid1;
                if (isset($_POST["$chkboxID2"])) {
                    $eventReg->ddattenddinner = 1;
                } else {
                    $eventReg->ddattenddinner = 0;
                }
                if (isset($_POST["$chkboxID3"])) {
                    $eventReg->cornhole = 1;
                } else {
                    $eventReg->cornhole = 0;
                }
                if (isset($_POST["$chkboxID4"])) {
                    $eventReg->softball = 1;
                } else {
                    $eventReg->softball = 0;
                }

           
             
                    $eventReg->mealchoice = $mealid1;
                    if ($dietaryRestriction1 !== '') {
                        $eventReg->dietaryrestriction = $dietaryRestriction1;
                    }

         
                $eventReg->message = $message;

                $eventReg->paid = 0;
                $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
                if ($result) {
                        $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email1 Please check your profile.';
                        header($redirect);
                        exit; 
                } //endresult
                if (!$result) {
                  
                    $eventReg->create();
                    $eventInst->addCount($eventReg->eventid);
                }  //end no results
             if (isset($regFirstName2)) {
                       // do the insert(s)
                    $eventReg->firstname = $regFirstName2;
                    $eventReg->lastname = $regLastName2;
                    $eventReg->eventid = $eventId;
                    $eventReg->email = $regEmail2;
                    $eventReg->userid = $regUserid2;
                    $eventReg->message = $message;
                    $eventReg->registeredby = $_SESSION['username'];
                    $eventReg->paid = 0;
                    if ((isset($_SESSION['partnerid'])) && ($_SESSION['partnerid'] !== '0')) {
                       $eventReg->dwop = 0;
                    } else {
                        $eventReg->dwop = 1;
                    }
                
                
                        $eventReg->mealchoice = $mealid2;
                       if ($dietaryRestriction2 !== '') {
                        $eventReg->dietaryrestriction = $dietaryRestriction2;
                       } else {
                        $eventReg->dietaryrestriction = '';
                       }

                    $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);

                    if ($result) {
                        $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email2 Please check your profile.';
                        header($redirect);
                        exit; 
                     }
     
                    if (!$result) {
            
                        $eventReg->create();
                        $eventInst->addCount($eventReg->eventid);
                    }
                }  //endfirstname2

            } // end if eventid            
       } //end isset
      } // end foreach
    if ($num_registered === 0) {

            $redirect = "Location: ".$_SESSION['regeventurl'].'?error=No Events Selected Please check at least 1 event and resubmit.';
            header($redirect);
            exit; 
    }
    if (filter_var($regEmail1, FILTER_VALIDATE_EMAIL)) {
      
        $regName1 = $regFirstName1.' '.$regLastName1;
   
        sendEmail(
            $regEmail1, 
            $regName1, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $emailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $mailAttachment2,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
    }

    if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {

        $regName2 = $regFirstName2.' '.$regLastName2;
   
        sendEmail(
            $regEmail2, 
            $regName2, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $emailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $mailAttachment2,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
             
    }

   $redirect = "Location: ".$_SESSION['homeurl'];
   header($redirect); 
   exit;
 

?>
