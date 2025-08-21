<?php
session_start();
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
$replyTopic = "SBDC Event Registration";
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$partnerEventReg = new EventRegistration($db);
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
$drID1 = '';
$drID2 = '';
$id_int = 0;

$num_registered = 0;
$currentDate = new DateTime();

if (!isset($_POST['submitAddRegs'])) {

     $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect); 
     exit;
}

    $eventInst->id = $_POST['eventid'];
    $eventInst->read_single();


    $regFirstName1 = htmlentities($_POST['firstname1']);
    $regLastName1 = htmlentities($_POST['lastname1']);
    $regEmail1 = htmlentities($_POST['email1']);  
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail1)) {    
        $regUserid1 = $user->id;
       }
   
   if (isset($_POST['message'])) {
       $message = htmlentities($_POST['message']); 
       } else {
        $message = '';
       }
      
   if (isset($_POST['mem2Chk'])) {
   
    $regFirstName2 = htmlentities($_POST['firstname2']);
    var_dump($regFirstName2);
    $regLastName2 = htmlentities($_POST['lastname2']);
    $regEmail2 = htmlentities($_POST['email2']);  
  
    $regEmail2 = filter_var($regEmail2, FILTER_SANITIZE_EMAIL); 
      $toCC2 =   $regEmail2;
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
       
           if (isset($_SESSION['testmode'])) {   
              $drID1 = "dr1".$eventInst->id;
              if (isset($_POST["$drID1"])) {
                $dietaryRestriction1 = $_POST["$drID1"];
              }
               $drID2 = "dr2".$eventInst->id;
              if (isset($_POST["$drID2"])) {
             
                $dietaryRestriction2 = $_POST["$drID2"];
              }
              
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
            }  //testmode
                $num_registered++;
                $eventId = $eventInst->id;
                $emailBody .= "<br><strong> Event: ".$eventInst->eventname.
                "<br>Type:    ".$eventInst->eventtype.
                "<br>DJ  :    ".$eventInst->eventdj.
                "<br>Room:    ".$eventInst->eventroom.
                "<br>Date:    ".date('M d Y',strtotime($eventInst->eventdate))."</strong><br>"; 
                if ($eventInst->orgemail != null) {
                    if ($toCC2 != '') {
                     $toCC3 = $eventInst->orgemail;
                    } else {
                      $toCC2 = $eventInst->orgemail;
                    }
                    
                }
                else {        
                        $toCC3 = '';                    
                }
                switch ($eventInst->eventtype) {
                  case "BBQ Picnic":
                 
                    if (isset($_POST['ddattm1'])) {
                        $emailBody .= "You have chosen to attend dinner.<br>";
                    }
                    if (isset($_POST['ch1'])) {
                        $emailBody .= "You have chosen to play cornhole.<br>";
                    }
                    if (isset($_POST['sb1'])) {
                        $emailBody .= "You have chosen to play softball.<br>";
                    }
                   if (isset($_POST['ddattm2'])) {
                        $emailBody .= "Your partner has chosen to attend dinner.<br>";
                    }
                    if (isset($_POST['ch2'])) {
                        $emailBody .= "Your partner has chosen to play cornhole.<br>";
                    }
                    if (isset($_POST['sb2'])) {
                        $emailBody .= "Your partner chosen to play softball.<br>";
                    }
                   
                    break;
                  case "Dance Party":
                         if (isset($_POST["$chkboxID2"])) {
                                $emailBody .= "You have chosen to attend dinner.<br>";
                                if ($_SESSION['testmode'] === 'YES') {
                                    if ($meal1 !== '') {
                                        $emailBody .= "You selected ".$meal1." at the cost of $".number_format($mealprice1/100,2)."";   
                                        $danceCost = $danceCost + $mealprice1;    
                                        if ($dietaryRestriction1 != '') {
                                          $emailBody .= " with a dietary restrictions of ".$dietaryRestriction1.".<br>";
                                       } else {
                                          $emailBody .= ".<br>";
                                       }
                             
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
        
                            $emailBody .= "Member Cost of the Dance Only will be $".number_format($eventInst->eventcost).".<br>";  
                            $emailBody .= "Guest Cost of the Dance Only will be $".number_format($eventInst->eventguestcost).".<br>"; 
                        }    
                          $emailBody .= "Please submit your fee prior to the dance as indicated on the form.<br>";  

                    break;
                    case "Dinner Dance":

                        if (isset($_SESSION['testmode']) &&($_SESSION['testmode'] === 'YES')) {
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

                        }  //testmode

                    break;
                    default:
                     $emailBody .= "Event type unknown!<br>";
                    break;
                    
                } //switch end


                 if ((!isset($_SESSION['testmode']))  || ($_SESSION['testmode'] !== 'YES')) {
                    if ($eventInst->eventcost > 0) {
                        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                        $coststr =  "Member Minimum Cost for the Dance is: "
                            .$fmt->formatCurrency($eventInst->eventcost, 'USD')."<br>
                            Check the form for specific costs.<br>";
            
                        $emailBody .= $coststr;
                        // $toCC2 = $treasurer;
                    }
                 }

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
            
                $emailBody .= '<br>You may login to the website and check Upcoming Events to see the status of your registrations.'; 
   
                $emailBody .= '<br> <br>';
          
                // do the insert(s)
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->eventname = 
                $eventReg->registeredby = $_SESSION['username'];
                $eventReg->userid = $regUserid1;
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

                if ($_SESSION['testmode'] === 'YES') {
             
                    $eventReg->mealchoice = $mealid1;
                    if ($dietaryRestriction1 !== '') {
                        $eventReg->dietaryrestriction = $dietaryRestriction1;
                    }

                } //testmode
                $eventReg->message = $message;

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
                var_dump($regFirstName2);
             if (isset($regFirstName2)) {
                       // do the insert(s)
                    $partnerEventReg->firstname = $regFirstName2;
                    $partnerEventReg->lastname = $regLastName2;
                    $partnerEventReg->eventid = $eventId;
                    $partnerEventReg->email = $regEmail2;
                    $partnerEventReg->userid = $regUserid2;
                    $partnerEventReg->message = $message;
                    $partnerEventReg->registeredby = $_SESSION['username'];
                    $partnerEventReg->paid = 0;
                if (isset($_POST['ddattm1'])) {
                    $partnerEventReg->ddattenddinner = 1;
                } else {
                    $partnerEventReg->ddattenddinner = 0;
                }
                if (isset($_POST['ch1'])) {
                    $partnerEventReg->cornhole = 1;
                } else {
                    $partnerEventReg->cornhole = 0;
                }
                if (isset($_POST['sb1'])) {
                    $partnerEventReg->softball = 1;
                } else {
                    $partnerEventReg->softball = 0;
                }

                    if ($_SESSION['testmode'] === 'YES') {
                
                        $partnerEventReg->mealchoice = $mealid2;
                       if ($dietaryRestriction2 !== '') {
                        $partnerEventReg->dietaryrestriction = $dietaryRestriction2;
                       } else {
                        $partnerEventReg->dietaryrestriction = '';
                       }

                    } //testmode
                    $result = $partnerEventReg->checkDuplicate($partnerEventReg->email, $partnerEventReg->eventid);

                    if ($result) {
                        $redirect = "Location: ".$_SESSION['regeventurl'].'?error=Duplicate Registration Email2 Please check from the upcoming events tab.';
                        header($redirect);
                        exit; 
                     }
     
                    if (!$result) {
                        var_dump($partnerEventReg);
                        $partnerEventReg->create();
                        $eventInst->addCount($partnerEventReg->eventid);
                    }
                       
                }  //endfirstname2

                
 

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
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    } else {
        echo 'Registrant Email 1 is empty or Invalid. Please enter valid email.';
    }


   $redirect = "Location: ".$_SESSION['returnurl'];
   header($redirect); 
   exit;
 

?>
