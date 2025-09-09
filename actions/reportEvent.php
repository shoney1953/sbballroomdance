<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$user = new User($db);
$event = new Event($db);
$mChoice = new DinnerMealChoices($db);
$mealChoices = [];
$mealCounts = [];
$regArr = [];
$memReg = 0;
$nonMemReg = 0;
$attDance = 0;
$attDinner = 0;
$playCornhole = 0;
$playSoftball = 0;
$dwop = 0;
$numDwop = 0;
$init_dinner = 1;
$paidNum = 0;
$regCount = 0;
$paidOnline = 0;
$mealsSelected = [];
class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/SBDC LOGO.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            30,
            10,
            'SBDC Event Registration Report - '.$today, 0, 1, 'C'
        );
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 10);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0,' C');
    }
}

           $result = $mChoice->read();
           
                $rowCount = $result->rowCount();
       
                $num_meals = $rowCount;
            
                if ($rowCount > 0) {

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $meal_item = array(
                            'id' => $id,
                            'mealname' => $mealname,
                            'eventid' => $eventid,
                            'memberprice' => $memberprice,
                            'guestprice' => $guestprice,
                            'productid' => $productid,
                            'priceid' => $priceid,
                            'guestpriceid' => $guestpriceid,
                            'numberchosen' => 0

                        );
                        array_push($mealChoices, $meal_item);
                  
                    }
                }

    if (isset($_POST['eventId'])) {
        if ($_POST['eventId'] !== '') {

            $eventId = htmlentities($_POST['eventId']);
     
            $event->id = $eventId;
            $event->read_single();
            if ($event->eventtype === 'Dance Party') {
                $result = $eventReg->read_ByEventIdDinner($eventId);
            } else {
                $result = $eventReg->read_ByEventId($eventId);  
            }
           
        } else {
        $result = $eventReg->read();
    }

    $rowCount = $result->rowCount();
    $num_reg = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'eventid' => $eventid,
                'eventname' => $eventname,
                'eventdate' => $eventdate,
                'eventtype' => $eventtype,
                'orgemail' => $orgemail,
                'userid' => $userid,
                'email' => $email,
                'paid' => $paid,
                'ddattenddinner' => $ddattenddinner,
                'ddattenddance' => $ddattenddance,
                'paidonline' => $paidonline,
                'cornhole' => $cornhole,
                'softball' => $softball,
                'message' => $message,
                'mealchoice' => $mealchoice,
                'mealname' => $mealname,
                'dietaryrestriction' => $dietaryrestriction,
                'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
            );
            array_push($regArr, $reg_item);
        }
    }

    $pdf = new PDF();

    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);

    $pdf->addPage('L');
    $pdf->SetFont('Arial', '', 10);

if ($rowCount > 0) {
    $regCount = 0;

    $prevEvent = '';
    $init = 1;

    foreach ($regArr as $reg) {

        $regCount++;
        if ($init == 1) {
            $prevEvent = $reg['eventid'];
            $init = 0;
            
            $event_string = ' '.$reg['eventtype'].' --- '.$reg['eventname'].'  '
                     .$reg['eventdate'].'   Cost: '.$event->eventcost;
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            if ($reg['orgemail'] != null) {
                $pdf->Cell(0, 10, 'Organizer Email: '.$reg['orgemail'], 0, 1);
            }
          
          
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(35,8,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(40,8,"LAST NAME",1,0,"L");  

            $pdf->Cell(18,8,"MEM",1,0,"L"); 

            if ($reg['eventtype'] === 'BBQ Picnic') {
                  $pdf->Cell(22,8,"Cornhole",1,0,"L");                 
                  $pdf->Cell(22,8,"Softball",1,0,"L"); 
                  $pdf->Cell(22,8,"Meal?",1,1,"L");
            }

            if (($reg['eventtype'] === 'Novice Practice Dance') ||
            ($reg['eventtype'] === 'Social') ||            
            ($reg['eventtype'] === 'TGIF') ||
            ($reg['eventtype'] === 'Meeting'))
            {        
                $pdf->Cell(18,8,"DWOP",1,1,"L"); 
          
            } 
            
            if ($reg['eventtype'] === 'Dinner Dance')
            {
                $pdf->Cell(18,8,"DWOP",1,0,"L");  
                $pdf->Cell(14,8,"PAID",1,0,"L");
                $pdf->Cell(60,8,"MEAL",1,0,"L");
                $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");
            }
            if ($reg['eventtype'] === 'Dance Party') {
                $pdf->Cell(18,8,"DWOP",1,0,"L"); 
                $pdf->Cell(14,8,"PAID",1,0,"L");
                $pdf->Cell(22,8,"DINNER?",1,0,"L");
                $pdf->Cell(60,8,"MEAL",1,0,"L");
                $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");

            }

        }
        if ($reg['eventtype'] === 'Dance Party') {

         if ($init_dinner === 1) {
         if ($reg['ddattenddinner']) {
            $init_dinner = 0; 
            $pdf->addPage('L');  
            $event_string = ' '.$reg['eventtype'].' --- '.$reg['eventname'].'  '.$reg['eventdate'].'   Cost: '.$event->eventcost.' ';
           $pdf->SetFont('Arial', 'B', 10);
           $pdf->Cell(0, 10, $event_string, 0, 1);
           if ($reg['orgemail'] != null) {
            $pdf->Cell(0, 10, 'Organizer Email: '.$reg['orgemail'], 0, 1);
        }
        
           $pdf->SetFont('Arial', '', 10);
           $pdf->Cell(35,8,"FIRST NAME",1,0,"L"); 
           $pdf->Cell(40,8,"LAST NAME",1,0,"L");  
           $pdf->Cell(18,8,"MEM",1,0,"L"); 

        if ($reg['eventtype'] === 'BBQ Picnic') {
            $pdf->Cell(22,8,"Cornhole",1,0,"L");                 
            $pdf->Cell(22,8,"Softball",1,0,"L"); 
            $pdf->Cell(22,8,"Meal?",1,1,"L");
        }
        if (($reg['eventtype'] === 'Novice Practice Dance') ||
           ($reg['eventtype'] === 'Social') ||
           ($reg['eventtype'] === 'TGIF') ||
           ($reg['eventtype'] === 'Meeting')
             )
           {        
               $pdf->Cell(18,8,"DWOP",1,1,"L"); 
         
           } 
         
           if ($reg['eventtype'] === 'Dance Party') {
              $pdf->Cell(18,8,"DWOP",1,0,"L"); 
              $pdf->Cell(14,8,"PAID",1,0,"L");
              $pdf->Cell(22,8,"DINNER?",1,0,"L");
              $pdf->Cell(60,8,"MEAL",1,0,"L");
              $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");
           }
     

           if ($reg['eventtype'] === 'Dinner Dance') {
            $pdf->Cell(18,8,"DWOP",1,0,"L"); 
            $pdf->Cell(14,8,"PAID",1,0,"L");
            $pdf->Cell(60,8,"MEAL",1,0,"L");
            $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");
           }

            }
        }
    }
        if ($reg['eventid'] !== $prevEvent) {
            $pdf->addPage('L');
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Ln(2);
            $pdf->Cell(0, 5, "Total Registrations for this Event:  ".$regCount, 0, 1); 
            if ($reg['eventtype'] === 'Dinner Dance') {
             $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1);  
            }
            if ($reg['eventtype'] === 'Dance Party') {
                if ($event->eventcost > 0) {

                    $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1);  
                }
               }
            $pdf->Cell(0, 5, "Total Member Registrations:          ".$memReg, 0, 1);
            $pdf->Cell(0, 5, "Total Non Member Registrations:      ".$nonMemReg, 0, 1);
            if ($reg['eventtype'] === 'Dine and Dance') {
             $pdf->Cell(0, 5, "Total Attending Dinner (if Dance Party):  ".$attDinner, 0, 1);
             $pdf->Cell(0, 5, "Total Attending Dance  (if Dance Party):  ".$attDance, 0, 1);
            }
            if ($reg['eventtype'] === 'Dance Party') {
                $pdf->Cell(0, 5, "Total Attending Dinner (if Dance Party):  ".$attDinner, 0, 1);
                $pdf->Cell(0, 5, "Total Attending Dance  (if Dance Party):  ".$attDance, 0, 1);
               }
            $regCount = 1;
            $paidNum = 0;
            $memReg = 0;
            $nonMemReg = 0;
            $attDance = 0;
            $attDinner = 0;
            $paidOnline = 0;
            $prevEvent = $reg['eventid'];
            $event_string = ' '.$reg['eventname'].'  '
            .$reg['eventdate'].' ';
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(35,8,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(40,8,"LAST NAME",1,0,"L");  

            $pdf->Cell(18,8,"MEM",1,0,"L");
            
            if (($reg['eventtype'] === 'Novice Practice Dance') ||
            ($reg['eventtype'] === 'Social') ||
        
            ($reg['eventtype'] === 'TGIF') ||
            ($reg['eventtype'] === 'Meeting')
           )
            {
                $pdf->Cell(18,8,"DWOP",1,1,"L");
            } 
          
       
            if ($reg['eventtype'] === 'Dance Party') {
                $pdf->Cell(18,8,"DWOP",1,0,"L");
                $pdf->Cell(14,8,"PAID",1,0,"L");
                $pdf->Cell(22,8,"DINNER?",1,0,"L");
                $pdf->Cell(60,8,"MEAL",1,0,"L");
                $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");
               }
            if ($reg['eventtype'] === 'Dinner Dance') {
                $pdf->Cell(18,8,"DWOP",1,0,"L");
                $pdf->Cell(14,8,"PAID",1,0,"L");
                $pdf->Cell(60,8,"MEAL",1,0,"L");
                $pdf->Cell(60,8,"DIETARY RESTR",1,1,"L");
            }

    
         }

      
        if ($reg['paid'] == true) {
            if (($reg['eventtype'] === 'Dinner Dance') ||
                ($reg['eventtype'] === 'Dance Party'))
            {
                $paidNum++;   
            } 
          
        }
        if ($reg['paidonline'] == true) {
         $paidOnline++;
        }
        if ($reg['ddattenddinner'] == true) {
            $attDinner++;
        }
        if ($reg['ddattenddance'] == true) {
            $attDance++;
        }
        if ($reg['cornhole'] == true) {
            $playCornhole++;
        }
        if ($reg['softball'] == true) {
            $playSoftball++;
        }
        if ($reg['eventtype'] === 'Dance Party') {
        
            if ($init_dinner === 1) {
                if ($reg['ddattenddinner']) {
                    $init_dinner = 0;
                    $pdf->Cell(100,8,' ',0,0,"L");       
            }
        } 
    }
        $user->id = $reg['userid'];
    
          $pdf->Cell(35,8,$reg['firstname'],1,0,"L"); 
          $pdf->Cell(40,8,$reg['lastname'],1,0,"L");  
    
          if ($user->getUserName($reg['email'])) {
            $pdf->Cell(18,8,"YES",1,0,"L"); 
            $memReg++;
            $user->id = $reg['userid'];
            $user->read_single();  
            if ($reg['eventtype'] === 'BBQ Picnic') {
                if ($reg['cornhole'] === '1') {
                    $pdf->Cell(22,8,"YES",1,0,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,0,"L");
                } 
                if ($reg['softball'] === '1') {
                    $pdf->Cell(22,8,"YES",1,0,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,0,"L");
                } 
                if ($reg['ddattenddinner'] === '1') {
                    $pdf->Cell(22,8,"YES",1,1,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,1,"L");
                } 

            }
            if (($reg['eventtype'] === 'Novice Practice Dance') ||
                ($reg['eventtype'] === 'Social') ||

                ($reg['eventtype'] === 'TGIF') ||
                ($reg['eventtype'] === 'Meeting')
               )
            {
               if ($user->partnerId > 0) {
                $pdf->Cell(18,8,"NO",1,1,"L"); 
               } else {
                $pdf->Cell(18,8,"YES",1,1,"L"); 
                $numDwop++;
               } 
            } else if (!($reg['eventtype'] === 'Novice Practice Dance') &&
                       !($reg['eventtype'] === 'BBQ Picnic') ) {
                if ($user->partnerId > 0) {
                    
                    $pdf->Cell(18,8,"NO",1,0,"L"); 
    
                   } else {
                    $pdf->Cell(18,8,"YES",1,0,"L"); 
                    $numDwop++;
                   } 
            
           } 
        } else {
           
            if (($reg['eventtype'] === 'Novice Practice Dance') ||
                ($reg['eventtype'] === 'Social') ||
                ($reg['eventtype'] === 'TGIF') ||
                ($reg['eventtype'] === 'Meeting')
               )
            {
                $pdf->SetTextColor(255 , 0, 0);   
                $pdf->Cell(18,8,"NO",1,0,"L");
                $pdf->SetTextColor(0 , 0, 0);
                $pdf->Cell(18,8,"UNK",1,1,"L"); 

             } else { 
                if (!($reg['eventtype'] === 'BBQ Picnic'))
                 {
                    $pdf->SetTextColor(255 , 0, 0); 
                    $pdf->Cell(18,8,"NO",1,0,"L");
                    $pdf->SetTextColor(0 , 0, 0);
                    $pdf->Cell(18,8,"UNK",1,0,"L"); 
                } 


             }
             if ($reg['eventtype'] === 'BBQ Picnic') {
                $pdf->SetTextColor(255 , 0, 0);   
                $pdf->Cell(18,8,"NO",1,0,"L");
                $pdf->SetTextColor(0 , 0, 0);
                if ($reg['cornhole'] === '1') {
                    $pdf->Cell(22,8,"YES",1,0,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,0,"L");
                } 
                if ($reg['softball'] === '1') {
                    $pdf->Cell(22,8,"YES",1,0,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,0,"L");
                } 
                if ($reg['ddattenddinner'] === '1') {
                    $pdf->Cell(22,8,"YES",1,1,"L");
                } else {
                    $pdf->Cell(22,8,"NO ",1,1,"L");
                } 

            }
            
            $nonMemReg++; 
            $dwop = "NO";
        } 
        $user->id = $reg['userid'];
        $user->read_single();
        if ($reg['eventtype'] === 'Dinner Dance') 
            {
           
                if ($reg['paid'] === '1') {
                    $pdf->Cell(14,8,"YES",1,0,"L");
                } else {
                    $pdf->SetTextColor(255 , 0, 0);
                    $pdf->Cell(14,8,"NO ",1,0,"L");
                    $pdf->SetTextColor(0 , 0, 0);
                } 
             }
      if ($reg['eventtype'] === 'Dance Party')
            {
           
                if ($reg['paid'] === '1') {
                    $pdf->Cell(14,8,"YES",1,0,"L");
                } else {
                    $pdf->SetTextColor(255 , 0, 0);
                    $pdf->Cell(14,8,"NO ",1,0,"L");
                    $pdf->SetTextColor(0 , 0, 0);
                } 

            }
      if ($reg['eventtype'] === 'Dance Party') {
        if ($reg['ddattenddinner'] === '1') {
            $pdf->Cell(22,8,"YES",1,0,"L");
        } else {
            $pdf->Cell(22,8,"NO ",1,0,"L");
        } 
    }
     if (($reg['eventtype'] === 'Dinner Dance') || ($reg['eventtype'] === 'Dance Party')) {
        if (($reg['mealchoice'] != NULL) && ($reg['mealchoice'] > 0)) {

                $pdf->Cell(60,8,$reg['mealname'],1,0,"L");
                $pdf->Cell(60,8,$reg['dietaryrestriction'],1,1,"L");
                foreach ($mealChoices as &$meal) {

                    if ($meal['id'] === $reg['mealchoice']) {
                        $meal['numberchosen']++;
                    
                    }
                }
               unset($meal);
        } else {
                $pdf->Cell(60,8," ",1,0,"L");
                $pdf->Cell(50,8," ",1,1,"L");
        }
     }
  

}

    }
    $pdf->addPage('L');
    $pdf->SetFont('Arial','B', 10);
    $pdf->Ln(2);
    $pdf->Cell(120,10, "Summary Totals  ", 1, 1,"C");
    $pdf->Cell(100,8, "Registrations for this Event:  ", 1, 0,"L");
  
    $pdf->Cell(20,8, $regCount, 1, 1,"L");
    if ($event->eventtype === 'Dinner Dance') {
    $pdf->Cell(100,8, "Paid for this Event:           ", 1, 0,"L");
    $pdf->Cell(20, 8, $paidNum, 1, 1,"L"); 
     $pdf->Cell(100,8, "Paid Online:           ", 1, 0,"L");
    $pdf->Cell(20, 8, $paidOnline, 1, 1,"L"); 
    } 
    if ($event->eventtype === 'Dance Party') {
        $pdf->Cell(100, 8, "Paid for this Event:           ", 1, 0);
        $pdf->Cell(20, 8, $paidNum, 1, 1); 
        $pdf->Cell(100,8, "Paid Online:           ", 1, 0,"L");
        $pdf->Cell(20, 8, $paidOnline, 1, 1,"L"); 
        } 
    $pdf->Cell(100, 8, "Member Registrations:          ", 1, 0);
    $pdf->Cell(20, 8, $memReg, 1, 1); 
    $pdf->Cell(100, 8, "Non Member Registrations:      ", 1, 0);
    $pdf->Cell(20, 8, $nonMemReg, 1, 1); 
    $pdf->Cell(100, 8, "DWOP Member Registrations:      ", 1, 0);
    $pdf->Cell(20, 8, $numDwop, 1, 1); 
    if ($event->eventtype === 'Dine and Dance') {
    $pdf->Cell(100, 8, "Attending Dinner (if Dine and Dance):  ", 1, 1);
    $pdf->Cell(20, 8, $attDinner, 1, 1);
    $pdf->Cell(100, 8, "Attending Dance (if Dine and Dance):  ", 1, 1);
    $pdf->Cell(20, 8, $attDance, 1, 1);
    }
    if ($event->eventtype === 'Dance Party') {
        $pdf->Cell(100, 8, "Attending Dinner (if Dance Party):  ".$attDinner, 1, 1);
        $pdf->Cell(20, 8, $attDinner, 1, 1);
        $pdf->Cell(100, 8, "Attending Dance (if Dance Party):  ".$attDance, 1, 1);
        $pdf->Cell(20, 8, $attDance, 1, 1);
        }
   if ($event->eventtype === 'BBQ Picnic') {
            $pdf->Cell(100, 8, "Playing Cornhole:  ".$playCornhole, 1, 1);
  
            $pdf->Cell(100, 8, "Playing Softball:  ".$playSoftball, 1, 1);

            $pdf->Cell(100, 8, "Attending Meal:  ".$attDinner, 1, 1);

     }
     if (($event->eventtype === 'Dance Party') || ($event->eventtype === 'Dinner Dance')) { 
         $pdf->Ln(2);
             $pdf->Cell(150, 8, "Number of Meals Selected:  ", 1, 1);
        foreach ($mealChoices as $meal) {
            if ($meal['numberchosen'] > 0) {
             $pdf->Cell(100, 8, $meal['mealname'], 1, 0);  
             $pdf->Cell(50, 8, $meal['numberchosen'], 1, 1); 
            }

        }
     }
    $pdf->SetFont('Arial', '', 10);
} else {
    $pdf->SetFont('Arial','B', 14);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 10);
}
$today = date("m-d-Y");
$pdf->Output("I", "EventRegistrationReport.".$today.".PDF");




?>
