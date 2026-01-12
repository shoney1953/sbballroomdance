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
$partnerEventReg = new EventRegistration($db);
$eventInst = new Event($db);
$user = new User($db);
$mealchoices = new DinnerMealChoices($db);
$mChoices = [];
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
$regFirstName1;
$regFirstName2;
$regLastName1;
$regLastName2;
$regEmail1;
$regEmail2;
$meal2 = '';
$mealprice2 = '';
$mealid2 = 0;
$mealproductid2 = '';
$mealpriceid2 = '';
$dietaryRestriction2 = '';
$num_meals = 0;
$drID1 = '';
$drID2 = '';
$id_int = 0;
$gotEventReq = 0;
$num_registered = 0;
$currentDate = new DateTime();

if (!isset($_POST['submitAddRegs'])) {

     $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect); 
     exit;
}
 
    $eventInst->id = $_POST['eventid'];
    $eventInst->read_single();
      $result = $mealchoices->read_ByEventId($eventInst->id);

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

                }   

    if (isset($_POST['mem1Chk'])) {
    $regFirstName1 = htmlentities($_POST['firstname1']);
    $regLastName1 = htmlentities($_POST['lastname1']);
    $regEmail1 = htmlentities($_POST['email1']);  
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail1)) {    
        $regUserid1 = $user->id;
       }
    }
   if (isset($_POST['message'])) {
       $message = htmlentities($_POST['message']); 
       } else {
        $message = '';
       }
    var_dump($_POST)  ;
   if (isset($_POST['mem2Chk'])) {
   
    $regFirstName2 = htmlentities($_POST['firstname2']);

    $regLastName2 = htmlentities($_POST['lastname2']);
    $regEmail2 = htmlentities($_POST['email2']);  
  
    $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
      $toCC2 =   $_SESSION['useremail'];
    if ($user->getUserName($regEmail2)) {    
        $regUserid2 = $user->id;
      } else {
          $regUserid2 = 0;
      }
      
   }

    $emailSubject = "You have registered for SBDC event(s)";

        $eventid = $_POST['eventid'];

        if (isset($_POST['message'])) {
          $message = $_POST['message'];
        } else {
           $message = ''; 
        }
                $num_registered++;
                $eventId = $eventInst->id;
                $emailBody .= "<br><strong> Event: ".$eventInst->eventname.
                "<br>Type:    ".$eventInst->eventtype.
                "<br>DJ  :    ".$eventInst->eventdj.
                "<br>Room:    ".$eventInst->eventroom.
                "<br>Date:    ".date('M d Y',strtotime($eventInst->eventdate))."</strong><br>"; 


                switch ($eventInst->eventtype) {
                  case "BBQ Picnic":
             if ($user->getUserName($regEmail1)) {    
                 $regUserid1 = $user->id;
                  $mem1Partnerid = $user->partnerId;
                  } 
             if (isset($regEmail2))   {
                    if ($user->getUserName($regEmail2)) {    
                    $regUserid2 = $user->id;
                    $mem2Partnerid = $user->partnerId;
                  } 
                  }
                   if ($mem1Partnerid !== '0') {
                      $eventReg->dwop = 0;
                    } else {
                      $eventReg->dwop = 1;
                    }
       
                    if (isset($_POST['ddattm1'])) {
                        $emailBody .= "You have chosen to attend the meal.<br>";
                       
              }
                        foreach ($mChoices as $choice) {
                    
                          $mealChk1 = 'meal'.$choice['id'];
                         if (isset($_POST["$mealChk1"])) {
                          
                                $eventReg->mealchoice = $choice['id'];
                                 $emailBody .= 'Your meal choice is: '.$choice['mealname'].'.<br>';
                               } //smeal1   
                       
                    }
                     if (isset($_POST['dietaryr1'])) {
                        
                           $eventReg->dietaryrestriction = $_POST['dietaryr1'];
                           $emailBody .= 'Your dietary restriction is: '.$eventReg->dietaryrestriction.'.<br>';
                         }
                    if (isset($_POST['ch1'])) {
                        $emailBody .= "You have chosen to play cornhole.<br>";
                    }
                    if (isset($_POST['sb1'])) {
                        $emailBody .= "You have chosen to play softball.<br>";
                    }
                   if (isset($_POST['ddattm2'])) {
                        $emailBody .= "<br>Your partner has chosen to attend the meal.<br>";
                        foreach ($mChoices as $choice) {
                    
                          $mealChk2 = 'meal2'.$choice['id'];
                         if (isset($_POST["$mealChk2"])) {
                          
                                $partnerEventReg->mealchoice = $choice['id'];
                                 $emailBody .= 'You partners meal choice is: '.$choice['mealname'].'.<br>';
                               } //smeal1   
                        }
                        if (isset($_POST['dietaryr2'])) {
                        
                           $partnerEventReg->dietaryrestriction = $_POST['dietaryr2'];
                           $emailBody .= 'Your partner specified a dietary restriction of: '.$partnerEventReg->dietaryrestriction.'.<br>';
                         }                      
                    }
                    if (isset($_POST['ch2'])) {
                        $emailBody .= "Your partner has chosen to play cornhole.<br>";
                    }
                    if (isset($_POST['sb2'])) {
                        $emailBody .= "Your partner chosen to play softball.<br>";
                    }
                   
                    break;
                       case "Meeting":
                        if (isset($_POST['mem1Chk'])) {
                         $emailBody .= "Your have chosen to attend the meeting.<br>";
                          }
                        if (isset($_POST['mem2Chk'])) {
                         $emailBody .= "Your partner has chosen to attend the meeting.<br>";
                          }
                    break;
                    default:
                     $emailBody .= "Event type unknown!<br>";
                    break;
                    
                } //switch end



                    if ($eventInst->eventform) {
                        $actLink= "<a href='".$eventInst->eventform."'>
                        Click to view event Form</a><br>";
                       $emailBody .= 'There is a signup form to submit registration details and payment if any is required.<br>';
                       $emailBody .= "Click on <em>PRINT</em> in the Form column of the event listing
                        on the website to open the form.<br> Or<br>$actLink";
    
                    }
                //  }
                // }
                if ($message) {
                    $emailBody .= '<br> MESSAGE from Registrant: <br>';
                    $emailBody .= $message;
                    $emailBody .= '<br> <br>';
                }
            

          
                // do the insert(s)
             if (isset($_POST['mem1Chk'])) {
                
                         
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->eventname = $eventInst->eventname;
                $eventReg->registeredby = $_SESSION['username'];
                $eventReg->userid = $regUserid1;
                // $eventReg->mealchoice = 0;
                $eventReg->message = $message;
               if (isset($_POST['ddattm1'])) {
                    $eventReg->ddattenddinner = 1;
                } else {
                    $eventReg->ddattenddinner = 0;
                }
                if (isset($_POST['ch1'])) {
                    $eventReg->cornhole = 1;
                } else {
                    $eventReg->cornhole = 0;
                }
                if (isset($_POST['sb1'])) {
                    $eventReg->softball = 1;
                } else {
                    $eventReg->softball = 0;
                }
                $eventReg->paid = 0;
                $result = $eventReg->checkDuplicate($eventReg->email, $eventReg->eventid);
                if ($result) {
                        $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email1 Please check from the upcoming events tab.';
                        header($redirect);
                        exit; 
                } //endresult
                if (!$result) {
                
                    $eventReg->create();
                    $eventInst->addCount($eventReg->eventid);
                }  //end no results
             }
              if (isset($_POST['mem2Chk'])) {
                       // do the insert(s)
                    if ($mem2Partnerid !== '0') {
                      $partnerEventReg->dwop = 0;
                    } else {
                      $partnerEventReg->dwop = 1;
                    }
                    $partnerEventReg->firstname = $regFirstName2;
                    $partnerEventReg->lastname = $regLastName2;
                    $partnerEventReg->eventid = $eventId;
                    $partnerEventReg->email = $regEmail2;
                    $partnerEventReg->userid = $regUserid2;
                    $partnerEventReg->message = $message;
             
                    $partnerEventReg->registeredby = $_SESSION['username'];
                    $partnerEventReg->paid = 0;
                 if (isset($_POST['ddattm2'])) {
                     $partnerEventReg->ddattenddinner = 1;
  
                } else {
                     $partnerEventReg->ddattenddinner = 0;
                }
                if (isset($_POST['ch2'])) {
                     $partnerEventReg->cornhole = 1;
                } else {
                     $partnerEventReg->cornhole = 0;
                }
                if (isset($_POST['sb2'])) {
                     $partnerEventReg->softball = 1;
                } else {
                     $partnerEventReg->softball = 0;
                }
                    $result = $partnerEventReg->checkDuplicate($partnerEventReg->email, $partnerEventReg->eventid);

                    if ($result) {
                        $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email2 Please check from the upcoming events tab.';
                        header($redirect);
                        exit; 
                     }
     
                    if (!$result) {
                 
                        $partnerEventReg->create();
                        $eventInst->addCount($partnerEventReg->eventid);
                    }
                       
                }  // register mem2

                     $emailBody .= '<br>You may login to the website and check Upcoming Events to see the status of your registration(s).'; 
   
                $emailBody .= '<br> <br>';           
 
   if (isset($_POST['mem1Chk'])) {
        $regName1 = $regFirstName1.' '.$regLastName1;
    if (filter_var($regEmail1, FILTER_VALIDATE_EMAIL)) {
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
}
 if (isset($_POST['mem2Chk'])) {
        $regName2 = $regFirstName2.' '.$regLastName2;
    if (filter_var($regEmail2, FILTER_VALIDATE_EMAIL)) {
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
    } else {
        echo 'Registrant Email 2 is empty or Invalid. Please enter valid email.';

    }
 }

   $redirect = "Location: ".$_SESSION['returnurl'];
   header($redirect); 
   exit;
 

?>
