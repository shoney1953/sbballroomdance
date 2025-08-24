<?php
// session_start();
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
$potentialReg1 = [];
$potentialReg2 = [];
$num_registered = 0;
$currentDate = new DateTime();

if (!isset($_POST['submitAddRegs'])) {

     $redirect = "Location: ".$_SESSION['homeurl'];
     header($redirect); 
     exit;
}

    $eventInst->id = $_POST['eventid'];
    $eventInst->read_single();
    if (isset($_POST['mem1Chk'])) {
    
    $potentialReg1['eventid'] = $eventInst->id;
    $potentialReg1['eventtype'] = $eventInst->eventtype;
    $potentialReg1['eventname'] = $eventInst->eventname;
    $potentialReg1['eventdate'] = $eventInst->eventdate;
    $potentialReg1['orgemail'] = $eventInst->orgemail;
    $potentialReg1['visitor'] = $_POST['visitor'];
    $potentialReg1['firstname'] = htmlentities($_POST['firstname1']);
    $potentialReg1['lastName'] = htmlentities($_POST['lastname1']);
    $potentialReg1['email'] = htmlentities($_POST['email1']);  
    $potentialReg1['email'] = filter_var($potentialReg1['email'], FILTER_SANITIZE_EMAIL); 
    $potentialReg1['registeredby'] = $_SESSION['username'];
    $potentialReg1['productid'] = $eventInst->eventproductid;
    $potentialReg1['priceid'] = $eventInst->eventmempriceid;
    $potentialReg1['guestpriceid'] = $eventInst->eventguestpriceid;
    if (isset($_POST['message'])) {
       $potentialReg1['message'] = htmlentities($_POST['message']); 
       }
    $regFirstName1 = htmlentities($_POST['firstname1']);
    $regLastName1 = htmlentities($_POST['lastname1']);
    $regEmail1 = htmlentities($_POST['email1']);  
    $regEmail1 = filter_var($regEmail1, FILTER_SANITIZE_EMAIL); 
    if ($user->getUserName($regEmail1)) {    
        $regUserid1 = $user->id;
       }

}
   if (isset($_POST['mem2Chk'])) {

    $potentialReg2['visitor'] = $_POST['visitor'];
    $potentialReg2['firstname'] = htmlentities($_POST['firstname2']);
    $potentialReg2['lastName'] = htmlentities($_POST['lastname2']);
    $potentialReg2['email'] = htmlentities($_POST['email2']);  
    $potentialReg2['email'] = filter_var($potentialReg2['email'], FILTER_SANITIZE_EMAIL); 
    $potentialReg2['eventtype'] = $eventInst->eventtype;
    $potentialReg2['eventname'] = $eventInst->eventname;
    $potentialReg2['eventdate'] = $eventInst->eventdate;
    $potentialReg2['eventid'] = $eventInst->id;
    $potentialReg2['orgemail'] = $eventInst->orgemail;
    $potentialReg2['productid'] = $eventInst->eventproductid;
    $potentialReg2['priceid'] = $eventInst->eventmempriceid;
    $potentialReg2['guestpriceid'] = $eventInst->eventguestpriceid;
    if (isset($_POST['message'])) {
    
       $potentialReg2['message'] = htmlentities($_POST['message']); 
       }
    

   
    $regFirstName2 = htmlentities($_POST['firstname2']);
 
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

        $eventid = $_POST['eventid'];

        if (isset($_POST['message'])) {
          $message = $_POST['message'];
        } else {
           $message = ''; 
        }
       
            
              if (isset($_POST['dietaryr1'])) {
                $dietaryRestriction1 = $_POST['dietaryr1'];
                $potentialReg1['dietaryrestriction'] = $dietaryRestriction1;
              }
          
              if (isset($_POST['dietaryr2'])) {
                $dietaryRestriction2 = $_POST['dietaryr2'];
                $potentialReg2['dietaryrestriction'] = $dietaryRestriction2;
              }
              if ($eventInst->eventtype === 'Dinner Dance') {
                $potentialReg1['ddattenddinner'] = '1';
                 $potentialReg2['ddattenddinner'] = '1';
              } else {
                if (isset($_POST['ddattdin1'])) {
             
                  $potentialReg1['ddattenddinner'] = '1'; 
                  $eventReg->ddattenddinner = '1';
                } else {
                   $potentialReg1['ddattenddinner'] = '0'; 
                  $eventReg->ddattenddinner = '0';
                }
                 if (isset($_POST['ddattdin2'])) {
                  $potentialReg2['ddattenddinner'] = '1'; 
                  $partnerEventReg->ddattenddinner = '1';
                } else {
                   $potentialReg2['ddattenddinner'] = '0'; 
                  $partnerEventReg->ddattenddinner = '0';
                }
              }
              if (isset($_POST['mem1Chk'])) {
               if ($potentialReg1['visitor'] === '1') {
                    $potentialReg1['eventcost'] = $eventInst->eventguestcost;
                  } else {
                    $potentialReg1['eventcost'] = $eventInst->eventcost;
                  }  
              }
               if (isset($_POST['mem2Chk'])) {
               if ($potentialReg2['visitor'] === '1') {
                    $potentialReg2['eventcost'] = $eventInst->eventguestcost;
                  } else {
                    $potentialReg2['eventcost'] = $eventInst->eventcost;
                  }  
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
                    
                           $mealChk1 = 'meal'.$choice['id'];
                         if (isset($_POST["$mealChk1"])) {
                      
                                  $mealid1 = $choice['id'];
                                  $meal1 = $choice['mealname'];
                                  $mealprice1 = $choice['memberprice'];
                                  $mealpriceid1 = $choice['priceid'];
                                  $potentialReg1['mealchoice'] =  $choice['id'];
                                  $potentialReg1['mealdesc'] = $choice['mealname'];
                                  $potentialReg1['productid'] = $choice['productid'];
                                  $potentialReg1['memberprice'] =  $choice['memberprice'];
                                  $potentialReg1['guestprice'] =  $choice['guestprice'];
                                  $potentialReg1['guestpriceid'] =  $choice['guestpriceid'];
                                  $potentialReg1['priceid'] =  $choice['priceid'];

                               } //smeal1
                   
                             $mealChk2 = 'meal2'.$choice['id'];
                         if (isset($_POST["$mealChk2"])) {   
                             
                                  $mealid2 = $choice['id'];
                                  $meal2 = $choice['mealname'];
                                  $mealprice2 = $choice['memberprice'];
                                  $mealpriceid2 = $choice['priceid'];
                                  $potentialReg2['productid'] = $choice['productid'];
                                  $mealpriceid2 = $choice['priceid'];
                                  $potentialReg2['mealchoice'] =  $choice['id'];
                                  $potentialReg2['mealdesc'] = $choice['mealname'];                       
                                  $potentialReg2['memberprice'] =  $choice['memberprice'];
                                  $potentialReg2['guestprice'] =  $choice['guestprice'];
                                  $potentialReg2['guestpriceid'] =  $choice['guestpriceid'];
                                  $potentialReg2['priceid'] =  $choice['priceid'];
                       
                               } //smeal2
                      } // foreach choice
                   
                }  // rowCount
  
// the following happens when user has specified NOT to pay online
            if (!(isset($_POST['payonline'])) ) {
                $emailSubject = "You have registered for SBDC event(s)";
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
               
                  case "Dance Party":
                     
                         if (isset($_POST['ddattdin1'])) {
                                $emailBody .= "You have chosen to attend dinner.<br>";
                         
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
                             

                        } else {
                            $emailBody .= "You have chosen not to attend dinner before the dance.<br>";
        
                            $emailBody .= "Member Cost of the Dance Only will be $".number_format($eventInst->eventcost).".<br>";  
                            $emailBody .= "Guest Cost of the Dance Only will be $".number_format($eventInst->eventguestcost).".<br>"; 
                        }    
                          $emailBody .= "Please submit your fee prior to the dance as indicated on the form.<br>";  

                    break;
                    case "Dinner Dance":

                   
                            if (isset($_POST['mem2Chk'])) {
                            if ($meal1 !== '') {
                                $emailBody .= "You selected ".$meal1." at the cost of ".number_format($mealprice1/100,2).""; 
                                $danceCost = $danceCost + $mealprice1;
                                if ($dietaryRestriction1 !== '') {
                                    $emailBody .= " with a dietary restiction of ".$dietaryRestriction1.".<br>";
                                 } else {
                                     $emailBody .= ".<br>";
                                 }
                             } 

                            }
                             if (isset($_POST['mem2Chk'])) {
                            if ($meal2 !== '') {
                                $emailBody .= "Your Partner selected ".$meal2." at the cost of ".number_format($mealprice2/100,2).".<br>";    
                                $danceCost = $danceCost + $mealprice2;    
                                if ($dietaryRestriction2 !== '') {
                                    $emailBody .= " with a dietary restiction of ".$dietaryRestriction2.".<br>";
                                 } else {
                                     $emailBody .= ".<br>";
                                 }  
                                } // meal2 
                            }
                           $emailBody .= "<br>Cost of the Dance will be ".number_format($danceCost/100,2).".<br>";  


                    break;
                    default:
                     $emailBody .= "Event type unknown!<br>";
                    break;
                    
                } //switch end


            
                    if ($eventInst->eventcost > 0) {
                        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                        $coststr =  "Member Minimum Cost for the Dance is: "
                            .$fmt->formatCurrency($eventInst->eventcost, 'USD')."<br>
                            Check the form for specific costs.<br>";
            
                        $emailBody .= $coststr;
                        // $toCC2 = $treasurer;
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
             if (isset($_POST['mem1Chk'])) {
                // do the insert(s)
                $eventReg->firstname = $regFirstName1;
                $eventReg->lastname = $regLastName1;
                $eventReg->eventid = $eventId;
                $eventReg->email = $regEmail1;
                $eventReg->eventname = 
                $eventReg->registeredby = $_SESSION['username'];
                $eventReg->userid = $regUserid1;
               
             
                    $eventReg->mealchoice = $mealid1;
                    if ($dietaryRestriction1 !== '') {
                        $eventReg->dietaryrestriction = $dietaryRestriction1;
                    }

      
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
              }
             if (isset($_POST['mem2Chk'])) {
                       // do the insert(s)
                    $partnerEventReg->firstname = $regFirstName2;
                    $partnerEventReg->lastname = $regLastName2;
                    $partnerEventReg->eventid = $eventId;
                    $partnerEventReg->email = $regEmail2;
                    $partnerEventReg->userid = $regUserid2;
                    $partnerEventReg->message = $message;
                    $partnerEventReg->registeredby = $_SESSION['username'];
                    $partnerEventReg->paid = 0;
                if (isset($_POST['ddattdin2'])) {
                    $partnerEventReg->ddattenddinner = 1;
                } else {
                    $partnerEventReg->ddattenddinner = 0;
                }
                
                
                        $partnerEventReg->mealchoice = $mealid2;
                       if ($dietaryRestriction2 !== '') {
                        $partnerEventReg->dietaryrestriction = $dietaryRestriction2;
                       } else {
                        $partnerEventReg->dietaryrestriction = '';
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
                       
                }  // mem2chk
            

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
  } // end of not pay online
   $_SESSION['potentialReg1'] = $potentialReg1;
   $_SESSION['potentialReg2'] = $potentialReg2;
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Registration Confirmation</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../index.php">Back to Home</a></li>
            <li><a href="../SBDCEvents.php">Back To Events</a></li>
        </ul>
        </div>
    </nav>
  <div class="content">
    <br><br><br>
  
      <h4>Please confirm the information submitted.</h4><br>
      <h4>Click the CONFIRM button to proceed, or the RETURN button to modify information.</h4><br>
      <?php

       $danceCost = 0;
       $cost1 = 0;
       $cost2 = 0;
       $totalDanceCost = 0;

  
      if ($eventInst->eventtype === 'Dance Party') {
       if (isset($_POST['mem1Chk'])) {
       if ($potentialReg1['ddattenddinner'] === '1') {
   
        if ($_POST['visitor'] === '1') { 
         $danceCost = $danceCost + $potentialReg1['guestprice'];
         $cost1 = $potentialReg1['guestprice'];  
        } else {
          $danceCost = $danceCost + $potentialReg1['memberprice'];
           $cost1 = $potentialReg1['memberprice']; 
         }
        } // visitor

       if ($potentialReg1['ddattenddinner'] !== '1') {
        if ($_POST['visitor'] === '1') { 
           $danceCost = $potentialReg1['eventcost'] * 100;
           $cost1 = $danceCost;      
            
           } else {
             $danceCost = $potentialReg1['eventcost'] * 100;
            $cost1 = $danceCost;
          
          }

          } // not attend dinner
         } // mem1chk
      if (isset($_POST['mem2Chk'])) {
       if ($potentialReg2['ddattenddinner'] === '1') {
   
        if ($_POST['visitor'] === '1') { 
         $danceCost = $danceCost + $potentialReg2['guestprice'];
         $cost2 = $potentialReg2['guestprice'];  
        } else {
          $danceCost = $danceCost + $potentialReg2['memberprice'];
           $cost2 = $potentialReg2['memberprice']; 
         }
        } // visitor

       if ($potentialReg2['ddattenddinner'] !== '1') {
        if ($_POST['visitor'] === '1') { 
           $danceCost = $potentialReg2['eventcost'] * 100;
           $cost2 = $danceCost;      
          
           } else {
             $danceCost = $potentialReg2['eventcost'] * 100;
            $cost2 = $danceCost;
           
          }

          } // not attend dinner
         } // mem2chk

        } // dance party



        if ($eventInst->eventtype === 'Dinner Dance') {
         if (isset($_POST['mem1Chk'])) {

            if ($_POST['visitor'] === '1') {
                $danceCost = $danceCost + $potentialReg1['guestprice'];
                $cost1 = $potentialReg1['guestprice'];
            } else {
                $danceCost = $danceCost + $potentialReg1['memberprice'];
                $cost1 = $potentialReg1['memberprice'];
             } // visitor
         } // mem1chk
        if (isset($_POST['mem2Chk'])) {

         $danceCost = $danceCost + $potentialReg2['memberprice'];
          $cost2 = $potentialReg2['memberprice'];
       
         }  // mem2chk
    
       } // dinner dance
       

        echo "<h4> You are registering for ".$eventInst->eventname." on ".$eventInst->eventdate.".</h4>";
         $ftotalprice = number_format(($danceCost/100),2);
         $fprice1 = number_format(($cost1/100),2);
         $fprice2 = number_format(($cost2/100),2);

         echo '<div class="list-box">';

          if ($eventInst->eventtype === 'Dance Party') {

              if ((isset($_POST['mem1Chk'])) && ($potentialReg1['ddattenddinner'] !== '1')) {
                echo "<h4>You have selected the following options(s). </h4><br>";
                echo "<ol>";
                 if (isset($_POST['mem1Chk'])) {
                   echo "<li>Dance Only for ".$_POST['firstname1']." at a cost of ".$fprice1.".</li>";
                 }

              }
          
              if ((isset($_POST['mem2Chk'])) && ($potentialReg2['ddattenddinner'] !== '1')) {
                echo "<h4>You have selected the following options(s). </h4><br>";
              
                if (isset($_POST['mem2Chk'])) {
                  echo "<li>Dance Only for ".$_POST['firstname2']." at a cost of ".$fprice2.".</li>";
                }
            
          }
          echo '</ol>';
        }
          if (($eventInst->eventtype === 'Dinner Dance') || ($eventInst->eventtype === 'Dance Party') ) { 
        echo "<ol>";
        if ((isset($_POST['mem1Chk'])) && ($potentialReg1['ddattenddinner'] === '1')) {
         echo "<h4>You have selected the following meal(s). </h4><br>";
         $dr1 = '';
         if ($potentialReg1['dietaryrestriction'] != '') {
            $dr1 = ' with a dietary restriction of ';
            $dr1 .= $potentialReg1['dietaryrestriction'];
         }
           echo "<li>Meal Choice for ".$_POST['firstname1'].": ".$potentialReg1['mealdesc']." at a cost of ".$fprice1." ".$dr1.".</li>";
        } // mem1chk

        if ((isset($_POST['mem2Chk'])) && ($potentialReg2['ddattenddinner'] === '1')) {
          $dr2 = '';
            if (isset($potentialReg2['dietaryrestriction'])) {
            if ($potentialReg2['dietaryrestriction'] != '') {
                $dr2 = ' with a dietary restriction of ';
                $dr2 .= $potentialReg2['dietaryrestriction'];
            }
                echo "<li>Meal Choice for ".$_POST['firstname2'].": ".$potentialReg2['mealdesc']." at a cost of ".$fprice2." ".$dr2."</li>";
            }
          
        } // mem2chk
         echo "</ol>";
            echo "<br><h4>You will be charged a total of: $".$ftotalprice." </h4><br>";
         echo "</div>";
         if (isset($_POST['mem1Chk'])) {
             $_SESSION['potentialreg1'] = $potentialReg1;
         } else {
          unset($_SESSION['potentialreg1']);
         }
         if (isset($_POST['mem2Chk'])) {
         $_SESSION['potentialreg2'] = $potentialReg2;
         } else {
          unset($_SESSION['potentialreg2']);
         }

        }
         echo '<div class="form-grid4">';
        echo '<div class="form-grid-div">';
        echo "</div>";
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="regEventConfirmt.php">';
         if (isset($_POST['mem1Chk'])) {
        echo '<input type="hidden" name="mem1Chk" value="1">';
         }
          if (isset($_POST['mem2Chk'])) {
        echo '<input type="hidden" name="mem2Chk" value="1">';
         }
        echo '<div class="form-item">';
        echo '<br><button   type="submit" name="submitRegConfirm">CONFIRM AND PROCEED</button>'; 
        echo '</div>';
        echo "</div>";
        echo '</form>';
        echo '<div class="form-grid-div">';

         echo '<br><button><a  title="Return and Resubmit Info" href="../SBDCEventst.php">Return to Upcoming Events</a></button>';
          echo "</div>";
      

      ?>

      </div>
    <footer >

    <?php
  require '../footer.php';
?>
    
</div> 

</footer>
</body>
</html>
