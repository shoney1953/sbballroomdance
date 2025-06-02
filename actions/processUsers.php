<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
date_default_timezone_set("America/Phoenix");
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
$allUsers = $_SESSION['process_users'] ;
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
$user = new User($db);

$users = $_SESSION['process_users'];
$num_users = 0;

$processUser = false;
$updateUser = false;
$archiveUser = false;


$upChk = false;
$arChk = false;


$user_count = 0;

if (isset($_POST['submitUserProcess'])) {
   
    foreach ($allUsers as $user) {

        $user_count++;
        $processUser = false;
        $updateUser = false;
        $deleteUser = false;


        $upChk = "up".$user['id'];
        $arChk = "ar".$user['id'];

       
        $mbSrch = "srch".$user['id'];
   //  user check boxes 

   
    if (isset($_POST["$upChk"])) {
        $updateUser = true;
        $processUser = true;

        break;
    }
    if (isset($_POST["$arChk"])) {
        $archiveUser = true;
        $processUser = true;
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
    <title>SBDC Ballroom Dance - Process Members</title>
</head>
<body>
<nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="../administration.php">Back to Administration</a></li>
        </ul>
        </div>
</nav>
 
<section id="processusers" class="content">
<div class="section-back">
<h1>Process Users</h1>
<?php

 
  if ($processUser) {

    require '../processUserItems.php';
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