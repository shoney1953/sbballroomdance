<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$user = new User($db);
$event = new Event($db);
$regArr = [];
$memReg = 0;
$nonMemReg = 0;
$attDance = 0;
$attDinner = 0;
$dwop = 0;
$numDwop = 0;
$init_dinner = 1;


class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        $this->Image('../img/sbdc_logo_small.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',16);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            10,
            10,
            'SBDC Event Registration Report - '.$today, 0, 0, 'C'
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
                'userid' => $userid,
                'email' => $email,
                'paid' => $paid,
                'ddattenddinner' => $ddattenddinner,
                'ddattenddance' => $ddattenddance,
                'message' => $message,
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
    $paidNum = 0;
    $prevEvent = '';
    $init = 1;

    foreach ($regArr as $reg) {

        $regCount++;
        if ($init == 1) {
            $prevEvent = $reg['eventid'];
            $init = 0;
            $event_string = ' '.$reg['eventtype'].' --- '.$reg['eventname'].'  '
                     .$reg['eventdate'].'   Cost: '.$event->eventcost.' ';
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(40,5,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(45,5,"LAST NAME",1,0,"L");  
            $pdf->Cell(70,5,"EMAIL",1,0,"L"); 
            $pdf->Cell(18,5,"MEM",1,0,"L"); 
            $pdf->Cell(18,5,"DWOP",1,0,"L"); 
            if ($reg['eventtype'] === 'Dance Party') {
                if ($event->eventcost > 0) {
                    $pdf->Cell(14,5,"PAID",1,0,"L");
                }
            }
           
      
            if ($reg['eventtype'] === 'Dine and Dance') {
                $pdf->Cell(22,5,"DINNER?",1,0,"L");

            }
            if ($reg['eventtype'] === 'Dinner Dance') {
                $pdf->Cell(14,5,"PAID",1,1,"L");
            }
           
            if ($reg['eventtype'] === 'Dance Party') {
                $pdf->Cell(22,5,"DINNER?",1,1,"L");

            }

            // $pdf->Cell(70,5,"MESSAGE",1,1,"L");    
          
    
        }
        if ($reg['eventtype'] === 'Dance Party') {

            if ($init_dinner === 1) {
             if ($reg['ddattenddinner'] != true) {
                    $init_dinner = 0; 
                    $pdf->addPage('L');  
                    $event_string = ' '.$reg['eventtype'].' --- '.$reg['eventname'].'  '
                    .$reg['eventdate'].'   Cost: '.$event->eventcost.' ';
           $pdf->SetFont('Arial', 'B', 14);
           $pdf->Cell(0, 10, $event_string, 0, 1);
           $pdf->SetFont('Arial', '', 14);
           $pdf->Cell(40,5,"FIRST NAME",1,0,"L"); 
           $pdf->Cell(45,5,"LAST NAME",1,0,"L");  
           $pdf->Cell(70,5,"EMAIL",1,0,"L"); 
           $pdf->Cell(18,5,"MEM",1,0,"L"); 
           $pdf->Cell(18,5,"DWOP",1,0,"L"); 
           if ($reg['eventtype'] === 'Dance Party') {
               if ($event->eventcost > 0) {
                   $pdf->Cell(14,5,"PAID",1,0,"L");
               }
           }
     
           if ($reg['eventtype'] === 'Dine and Dance') {
               $pdf->Cell(22,5,"DINNER?",1,0,"L");

           }
           if ($reg['eventtype'] === 'Dinner Dance') {
               $pdf->Cell(14,5,"PAID",1,1,"L");
           }
          
           if ($reg['eventtype'] === 'Dance Party') {
               $pdf->Cell(22,5,"DINNER?",1,1,"L");

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
            $prevEvent = $reg['eventid'];
            $event_string = ' '.$reg['eventname'].'  '
            .$reg['eventdate'].' ';
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, $event_string, 0, 1);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(40,5,"FIRST NAME",1,0,"L"); 
            $pdf->Cell(45,5,"LAST NAME",1,0,"L");  
            $pdf->Cell(70,5,"EMAIL",1,0,"L");
            $pdf->Cell(18,5,"MEM",1,0,"L");
            $pdf->Cell(18,5,"DWOP",1,0,"L");
            if ($reg['eventtype'] === 'Dance Party') {
                if ($event->eventcost > 0) {
                    $pdf->Cell(14,5,"PAID",1,0,"L");
                }

                $pdf->Cell(22,5,"DINNER?",1,0,"L");
               }
            if ($reg['eventtype'] === 'Dinner Dance') {
             $pdf->Cell(14,5,"PAID",1,1,"L");
            }
            if ($reg['eventtype'] === 'Dine and Dance') {
             $pdf->Cell(22,5,"DINNER?",1,1,"L");

            }
            // $pdf->Cell(70,5,"MESSAGE",1,1,"L");      
    
         }
         $paid = 'Not Paid';

        if ($reg['paid'] == true) {
          if ($reg['ddattenddinner'] == true) {
            $paidNum++;
            $paid = 'Paid';
            } else {
                 $paid = 'NA';
         }
          
        }
        if ($reg['ddattenddinner'] == true) {
            $attDinner++;
      
        }
        if ($reg['ddattenddance'] == true) {
            $attDance++;
 
        }
        if ($reg['eventtype'] === 'Dance Party') {

            if ($init_dinner === 1) {
                if ($reg['ddattenddinner'] != true) {
                    $init_dinner = 0;
                    $pdf->Cell(100,5,' ',0,1,"L");       
            }
        }
    }
        $user->id = $reg['userid'];
    
          $pdf->Cell(40,5,$reg['firstname'],1,0,"L"); 
          $pdf->Cell(45,5,$reg['lastname'],1,0,"L");  
          $pdf->Cell(70,5,$reg['email'],1,0,"L");  
          if ($user->getUserName($reg['email'])) {
            $pdf->Cell(18,5,"YES",1,0,"L"); 
            $memReg++;
            $user->id = $reg['userid'];
            $user->read_single();  {
               if ($user->partnerId > 0) {
                $pdf->Cell(18,5,"NO",1,0,"L"); 
               } else {
                $pdf->Cell(18,5,"YES",1,0,"L"); 
                $numDwop++;
               } 
            }
        } else {
            $pdf->Cell(18,5,"NO",1,0,"L");
            $pdf->Cell(18,5,"UNK",1,0,"L"); 
            $nonMemReg++; 
            $dwop = "NO";
        } 
        $user->id = $reg['userid'];
        $user->read_single();
        if ($reg['eventtype'] === 'Dinner Dance') {
            if ($event->eventcost > 0) {
                if ($reg['paid'] === '1') {
                    $pdf->Cell(14,5,"YES",1,1,"L");
                } else {
                    $pdf->Cell(14,5,"NO ",1,1,"L");
                } 
            }

      }
      if ($reg['eventtype'] === 'Dance Party') {
        if ($event->eventcost > 0) {
        if ($reg['paid'] === '1') {
            if ($reg['ddattenddinner'] === '1') {
              $pdf->Cell(14,5,"YES",1,0,"L");
            } else {
              $pdf->Cell(14,5,"N/A ",1,0,"L");
             } 
        } else {
            if ($reg['ddattenddinner'] === '1') {
                $pdf->Cell(14,5,"NO",1,0,"L");
              } else {
                $pdf->Cell(14,5,"N/A ",1,0,"L");
               } 
     
        } 
    }
  }
      if ($reg['eventtype'] === 'Dine and Dance') {
        if ($reg['ddattenddinner'] === '1') {
            $pdf->Cell(22,5,"YES",1,0,"L");
        } else {
            $pdf->Cell(22,5,"NO ",1,0,"L");
        } 
     
    }
    if ($reg['eventtype'] === 'Dance Party') {
        if ($reg['ddattenddinner'] === '1') {
            $pdf->Cell(22,5,"YES",1,1,"L");
        } else {
            $pdf->Cell(22,5,"NO ",1,1,"L");
        } 
     
    }
        
        // $pdf->Cell(70,5,$reg['message'],1,1,"L"); 


    }
    $pdf->addPage('L');
    $pdf->SetFont('Arial','B', 14);
    $pdf->Ln(2);
    $pdf->Cell(0, 5, "Total Registrations for this Event:  ".$regCount, 0, 1);
    if ($reg['eventtype'] === 'Dinner Dance') {
    $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1); 
    } 
    if ($reg['eventtype'] === 'Dance Party') {
        $pdf->Cell(0, 5, "Total Paid for this Event:           ".$paidNum, 0, 1); 
        } 
    $pdf->Cell(0, 5, "Total Member Registrations:          ".$memReg, 0, 1);
    $pdf->Cell(0, 5, "Total Non Member Registrations:      ".$nonMemReg, 0, 1);
    $pdf->Cell(0, 5, "Total DWOP Member Registrations:      ".$numDwop, 0, 1);
    if ($reg['eventtype'] === 'Dine and Dance') {
    $pdf->Cell(0, 5, "Total Attending Dinner (if Dine and Dance):  ".$attDinner, 0, 1);
    $pdf->Cell(0, 5, "Total Attending Dance (if Dine and Dance):  ".$attDance, 0, 1);
    }
    if ($reg['eventtype'] === 'Dance Party') {
        $pdf->Cell(0, 5, "Total Attending Dinner (if Dance Party):  ".$attDinner, 0, 1);
        $pdf->Cell(0, 5, "Total Attending Dance (if Dance Party):  ".$attDance, 0, 1);
        }
    $pdf->SetFont('Arial', '', 14);
} else {
    $pdf->SetFont('Arial','B', 16);
    $pdf->Cell(0, 10, "   NO REGISTRATIONS FOUND ", 0, 1); 
    $pdf->SetFont('Arial', '', 14);
}
$today = date("m-d-Y");
$pdf->Output("I", "EventRegistrationReport.".$today.".PDF");
}



?>
