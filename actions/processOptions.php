<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Options.php';

date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$allOptions = $_SESSION['allOptions'] ;
if (!isset($_SESSION['username']))
{
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
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
       } else {
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
}

$database = new Database();
$db = $database->connect();
$options = new Options($db);
$processOption = false;
$updateOption = false;
$duplicateOption = false;
$upChk = false;
$dpChk = false;
$option_count = 0;

if (isset($_POST['submitOptionProcess'])) {
   
    foreach ($allOptions as $option) {

        $option_count++;
        $processOption = false;
        $processOption = false;
        $updateOption = false;
        $duplicateOption = false;
        $upChk = "up".$option['id'];
        $dpChk = "dp".$option['id'];
   
   //  option check boxes 


    if (isset($_POST["$upChk"])) {
        $updateOption = true;
        $processOption = true;

        break;
    }
 
    if (isset($_POST["$dpChk"])) {
   
        $duplicateOption = true;
        $processOption = true;
        break;
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
    <title>SBDC Ballroom Dance - Process Options</title>
</head>
<body>
<nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../administration.php">Back to Administration</a></li>
        </ul>
        </div>
</nav>
 
<section id="processoptions" class="content">
<div class="section-back">
<h1>Process Options</h1>
<?php

  if ($processOption) {

    require '../processOptionItems.php';
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