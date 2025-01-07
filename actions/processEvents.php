<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
require_once '../models/EventRegistration.php';
require_once '../models/User.php';
require_once '../models/DinnerMealChoices.php';
date_default_timezone_set("America/Phoenix");
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$allEvents = $_SESSION['allEvents'] ;
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
$upcomingEvents = [];
$upcomingEvents = $_SESSION['upcoming_events'] ;
$user = new User($db);
$users = [];
$regs = [];
$_SESSION['regUsers'] = [];
$_SESSION['registrations'] = [];

$num_users = 0;
$updateReg = false;
$deleteReg = false;
$addReg = false;
$processReg = false;
$processEvent = false;
$reportEvent = false;
$updateEvent = false;
$deleteEvent = false;
$emailEvent = false;
$archiveEvent = false;
$duplicateEvent = false;
$urChk = false;
$drChk = false;
$arChk = false;
$rpChk = false;
$upChk = false;
$dlChk = false;
$emChk = false;
$dpChk = false;
$aeChk = false;
$event_count = 0;

if (isset($_POST['submitEventProcess'])) {
   
    foreach ($allEvents as $event) {

        $event_count++;
        $processEvent = false;
        $processReg = false;
        $updateReg = false;
        $deleteReg = false;
        $addReg = false;
        $processReg = false;
        $processEvent = false;
        $reportEvent = false;
        $updateEvent = false;
        $deleteEvent = false;
        $emailEvent = false;
        $duplicateEvent = false;
        $archiveEvent = false;
        $rpChk = "rp".$event['id'];
        $upChk = "up".$event['id'];
        $dlChk = "dl".$event['id'];
        $emChk = "em".$event['id'];
        $dpChk = "dp".$event['id'];
        $aeChk = "ae".$event['id'];
        $arChk = "ar".$event['id'];
        $drChk = "dr".$event['id'];
        $urChk = "ur".$event['id'];
        $mbSrch = "srch".$event['id'];
   //  event check boxes 

    if (isset($_POST["$rpChk"])) {
        $reportEvent = true;
        $processEvent = true;
    
        break;
       
    }
    if (isset($_POST["$aeChk"])) {
        $archiveEvent = true;
        $processEvent = true;
        break;
       
    }
    if (isset($_POST["$upChk"])) {
        $updateEvent = true;
        $processEvent = true;

        break;
    }
    if (isset($_POST["$dlChk"])) {
        $deleteEvent = true;
        $processEvent = true;
        break;
    }
    if (isset($_POST["$emChk"])) {
        $emailEvent = true;
        $processEvent = true;
        break;
    }
    
    if (isset($_POST["$dpChk"])) {
        $duplicateEvent = true;
        $processEvent = true;
        break;
    }
    //   registration check boxes

    if (isset($_POST["$arChk"])) {
        $addReg = true;
        $processReg = true;
    }
    if (isset($_POST["$drChk"])) {

        $deleteReg = true;
        $processReg = true;
        
    }
    if (isset($_POST["$urChk"])) {
        $updateReg = true;
        $processReg = true;
    }
   
  if ($processReg) {

  if ($deleteReg | $updateReg) {
    if (isset($_POST["$mbSrch"])) {
    $regs = [];

    $search = trim($_POST["$mbSrch"]);
 
    $search .= '%';
    $eventid = $event['id'];
    $result = $eventReg->readLike($eventid, $search);
    $rowCount = $result->rowCount();
    $num_regs = $rowCount;
    if($rowCount > 0) {
    
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          $reg_item = array(
              'id' => $id,
              'firstname' => $firstname,
              'lastname' => $lastname,
              'email' => $email,
              'ddattenddinner' => $ddattenddinner,
              'ddattenddance' => $ddattenddance,
              'registeredby' => $registeredby,
              'dateregistered' => $dateregistered,
              'cornhole' => $cornhole,
              'softball' => $softball,
              'message' => $message,
              'paid' => $paid,
              'userid' => $userid
           
          );
          array_push( $regs, $reg_item);
    
      }
   
    } else {
      $result = $eventReg->read_ByEventId($eventid);
      
      $rowCount = $result->rowCount();
      $num_regs = $rowCount;
      if($rowCount > 0) {
      
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              extract($row);
              $reg_item = array(
                  'id' => $id,
                  'firstname' => $firstname,
                  'lastname' => $lastname,
                  'email' => $email,
                  'ddattenddinner' => $ddattenddinner,
                  'ddattenddance' => $ddattenddance,
                  'registeredby' => $registeredby,
                  'cornhole' => $cornhole,
                  'softball' => $softball,
                  'dateregistered' => $dateregistered,
                  'message' => $message,
                  'paid' => $paid,
                  'userid' => $userid

                  
              );
              array_push( $regs, $reg_item);

      
          }
          
      }
      
  }  

}
$_SESSION['registrations'] = $regs;
break;
}



if ($addReg) {
 
  if (isset($_POST["$mbSrch"])) {
    $users = [];
    $search = trim($_POST["$mbSrch"]);
    $search .= '%';

    $result = $user->readLike($search);
    
    $rowCount = $result->rowCount();
    $num_users = $rowCount;

    if($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email

            );
            array_push( $users, $user_item);
      
        }
    

} else {
        $result = $user->read();
        
        $rowCount = $result->rowCount();
        $num_users = $rowCount;
        if($rowCount > 0) {
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    'id' => $id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'partnerId' => $partnerid
                    
                );
                array_push( $users, $user_item);
        
            }
            
        }
    }  
$_SESSION['regUsers'] = $users;
  }

  break;
} 

}
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Process Events</title>
</head>
<body>
<nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../administration.php">Back to Administration</a></li>
        </ul>
        </div>
</nav>
 
<section id="processevents" class="content">
<div class="section-back">
<h1>Process Events</h1>
<?php

  if ($processReg) {

    require '../processEventRegs.php';
  }
  if ($processEvent) {

    require '../processEventItems.php';
  }
?>
</div>
</section>
<footer >
    <?php
    require '../footer.php';
   ?>
</footer>
</body>
</html>