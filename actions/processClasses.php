<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DanceClass.php';
require_once '../models/ClassRegistration.php';
require_once '../models/User.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
date_default_timezone_set("America/Phoenix");
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$allClasses = $_SESSION['allClasses'] ;

if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') 
        && ($_SESSION['role'] != 'INSTRUCTOR')
        && ($_SESSION['role'] != 'SUPERADMIN')) {
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
$class = new DanceClass($db);
$classReg = new ClassRegistration($db);
$upcomingClasses = [];
$upcomingClasses = $_SESSION['upcoming_classes'] ;
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
$processClass = false;
$reportClass = false;
$csvClass = false;
$updateClass = false;
$deleteClass = false;
$emailClass = false;
$archiveClass = false;
$duplicateClass = false;
$urChk = false;
$drChk = false;
$arChk = false;
$rpChk = false;
$upChk = false;
$dlChk = false;
$emChk = false;
$dpChk = false;
$aeChk = false;
$class_count = 0;

if (isset($_POST['submitClassProcess'])) {
   
    foreach ($allClasses as $class) {

        $processClass = false;
        $processReg = false;
        $updateReg = false;
        $deleteReg = false;
        $addReg = false;
        $processReg = false;
        $processClass = false;
        $reportClass = false;
        $csvClass = false;
        $updateClass = false;
        $deleteClass = false;
        $emailClass = false;
        $duplicateClass = false;
        $archiveClass = false;
        $rpChk = "rp".$class['id'];
        $cvChk = "cv".$class['id'];
        $upChk = "up".$class['id'];
        $dlChk = "dl".$class['id'];
        $emChk = "em".$class['id'];
        $dpChk = "dp".$class['id'];
        $aeChk = "ae".$class['id'];
        $arChk = "ar".$class['id'];
        $drChk = "dr".$class['id'];
        $urChk = "ur".$class['id'];
        $mbSrch = "srch".$class['id'];
   //  class check boxes 

    if (isset($_POST["$rpChk"])) {
        $reportClass = true;
        $processClass = true;
        break;
       
    }
    if (isset($_POST["$cvChk"])) {
        $csvClass = true;
        $processClass = true;
        break;
       
    }
    if (isset($_POST["$aeChk"])) {
        $archiveClass = true;
        $processClass = true;
        break;
       
    }
    if (isset($_POST["$upChk"])) {
        $updateClass = true;
        $processClass = true;

        break;
    }
    if (isset($_POST["$dlChk"])) {
        $deleteClass = true;
        $processClass = true;
        break;
    }
    if (isset($_POST["$emChk"])) {
        $emailClass = true;
        $processClass = true;
        break;
    }
    
    if (isset($_POST["$dpChk"])) {
        $duplicateClass = true;
        $processClass = true;
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
    $classid = $class['id'];
    $result = $classReg->readLike($classid, $search);
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
              'registeredby' => $registeredby,
              'dateregistered' => $dateregistered,
              'userid' => $userid
           
          );
          array_push( $regs, $reg_item);
    
      }
   
    } else {
      $result = $classReg->read_ByClassId($classid);
      
      $rowCount = $result->rowCount();
      $num_regs = $rowCount;
      if($rowCount > 0) {
      
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              extract($row);
              $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,  
                'registeredby'  => $registeredby,
                'dateregistered' => $dateregistered,
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
    <title>SBDC Ballroom Dance - Process Classes</title>
</head>
<body>
<nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../administration.php">Back to administration</a></li>
        </ul>
        </div>
    </nav>
 
<section id="processclasses" class="content">
<div class="section-back">
<h1>Process Classes</h1>
<?php

  if ($processReg) {

    require '../processClassRegs.php';
  }
  if ($processClass) {

    require '../processClassItems.php';
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