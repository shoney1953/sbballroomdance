<?php
session_start();
require('../includes/fpdf.php');
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/ClassRegistrationArch.php';
require_once '../models/EventRegistration.php';
require_once '../models/EventRegistrationArch.php';
require_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);
$classRegistration = new ClassRegistration($db);
$classRegistrationArch = new ClassRegistrationArch($db);
$eventRegistration = new EventRegistration($db);
$eventRegistrationArch = new EventRegistrationArch($db);
$today = date("m-d-Y");
$sixMonthdate = strtotime("-6 months");

$userArr = [];
$userArrMod = [];
$eventArr = [];
$totalNoAct = 0;
$totalNoActEver = 0;
$regCount = 0;

class PDF extends FPDF
{
    function Header() {
        // Logo
        $today = date("m-d-Y");
        // $this->Image('../img/sbdc_logo_small.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(
            10,
            10,
            'SBDC Members W/O Activity Report - '.$today, 0, 0, 'C'
        );
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0,' C');
    }
}

$result = $user->read();

    $userCount = $result->rowCount();

    if ($userCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $usr_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'fulltime' => $fulltime,
                'email' => $email

            );
            $usr_item['totevents'] = 0;
            $usr_item['totclasses'] = 0;
            $usr_item['sixmonthevents'] = 0;
            $usr_item['sixmonthclasses'] = 0;
            array_push($userArr, $usr_item);
       
        }
    }

  

    $pdf = new PDF("L");
    $pdf->AliasNbPages();
    $pdf->SetTextColor(26, 22, 22);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

if ($userCount > 0) {
  foreach ($userArr as $user) {

    $result = $eventRegistration->read_ByEmail($user['email']);

    $regCount = $result->rowCount();

    if ($regCount > 0) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'eventname' => $eventname,
            'registeredby' => $registeredby,
            'dateregistered' => $dateregistered
        );
        $user['totevents']++;
        $user['sixmonthevents']++;    


      }
    }

        $result = $classRegistration->read_ByEmail($user['email']);

        $regCount = $result->rowCount();

        if ($regCount > 0) {
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $event_item = array(
                'id' => $id,
                'classname' => $eventname,
                'registeredby' => $registeredby,
                'dateregistered' => $dateregistered
            );
            $user['totclasses']++;
            $user['sixmonthclasses']++;  

          } 

        }

$result = $eventRegistrationArch->read_ByEmail($user['email']);

$regCount = $result->rowCount();

if ($regCount > 0) {
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $event_item = array(
        'id' => $id,
        'eventname' => $eventname,
        'registeredby' => $registeredby,
        'dateregistered' => $dateregistered
    );
    $user['totevents']++;
    $dateregts = strtotime($event_item['dateregistered']);

      if ($dateregts > $sixMonthdate) {
        $user['sixmonthevents']++;

      }


}
}  
$result = $classRegistrationArch->read_ByEmail($user['email']);

$regCount = $result->rowCount();

if ($regCount > 0) {
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $class_item = array(
        'id' => $id,
        'classname' => $classname,
        'registeredby' => $registeredby,
        'dateregistered' => $dateregistered
    );
    $user['totclasses']++;
    $dateregts = strtotime($class_item['dateregistered']);


      if ($dateregts > $sixMonthdate) {
        $user['sixmonthclasses']++;

      }


        }
      }
      array_push($userArrMod, $user);
    }


}
 



$pdf->SetFont('Arial', '', 14);
$pdf->Cell(70,5,"MEMBERS WITH NO CLASSES OR EVENTS IN THE LAST 6 MONTHS",0,1,"L"); 
$pdf->Cell(70,5," ",0,1,"L"); 
$pdf->Cell(40,5,"FIRST NAME",1,0,"L"); 
$pdf->Cell(40,5,"LAST NAME",1,0,"L");  
$pdf->Cell(75,5,"EMAIL",1,0,"L"); 
$pdf->Cell(30,5,"FULLTIME",1,1,"L"); 
$pdf->Ln(2);
// $pdf->Cell(15,5,"T EV",1,0,"L"); 
// $pdf->Cell(15,5,"6 EV",1,1,"L"); 
// 

foreach ($userArrMod as $user) {

  if (($user['sixmonthevents'] === 0) && 
     ($user['sixmonthclasses'] === 0)) {
    $pdf->Cell(40,5,$user['firstname'],0,0,"L"); 
    $pdf->Cell(40,5,$user['lastname'],0,0,"L");  
    $pdf->Cell(75,5,$user['email'],0,0,"L"); 
    if ($user['fulltime']) {
      $pdf->Cell(10,5,"YES",0,1,"L");   
    } else {
      $pdf->Cell(10,5,"NO",0,1,"L");  
    }
    $totalNoAct++;
    // $pdf->Cell(15,5,$user['totevents'],0,0,"L"); 
    // $pdf->Cell(15,5,$user['sixmonthevents'],0,1,"L");  
  
  }
  if (($user['totevents'] === 0) && 
     ($user['totclasses'] === 0)) {
  
    $totalNoActEver++;
    // $pdf->Cell(15,5,$user['totevents'],0,0,"L"); 
    // $pdf->Cell(15,5,$user['sixmonthevents'],0,1,"L");  
  
  }
}

$pdf->Ln(2);

$pdf->Cell(0, 5, "Total Members without Activity in the last 6 Months:  ".$totalNoAct, 0, 1);
$pdf->Cell(0, 5, "Total Members without any Activity:  ".$totalNoActEver, 0, 1);

$pdf->Output("I", "MemberNoActivity.".$today.".PDF");


// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
