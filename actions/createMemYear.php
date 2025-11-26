<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
$users = [];
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
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
$currentYear = date("Y"); 

$yearInput = $_POST['populateYear'];
if ($yearInput < $currentYear ) {
   $redirect = "Location: ".$_SESSION['adminurl'];
   header($redirect);
   exit;
}

$database = new Database();
$db = $database->connect();
$user = new User($db);
$result = $user->read();
    
$rowCount = $result->rowCount();

if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_item = array(
            'id' => $id
        );
        array_push( $users, $user_item);
  
    }
}

$memberPaid = new MemberPaid($db);

if (isset($_POST['createMemYear'])) {

    foreach ($users as $user ) {
        $memberPaid->year = $yearInput;
        $memberPaid->userid = $user['id'];
        $memberPaid->paid = 0;
        $memberPaid->create();
    }

   $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>