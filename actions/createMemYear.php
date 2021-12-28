<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/MemberPaid.php';
$users = [];
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
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