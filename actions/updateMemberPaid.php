<?php
session_start();
require_once '../config/Database.php';
require_once '../models/MemberPaid.php';
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
var_dump($_POST);
if (isset($_POST['thisyear'])) {
    $memberStatus = $_SESSION['memberStatus1'];
}
if (isset($_POST['nextyear'])) {
    $memberStatus = $_SESSION['memberStatus2'];
}



$database = new Database();
$db = $database->connect();
$memberPaid = new MemberPaid($db);


if (isset($_POST['updateMemPaid'])) {

    foreach ($memberStatus as $memStat) {
  
        $ckboxId = "pd".$memStat['id'];
        if (isset($_POST["$ckboxId"])) {
            $memberPaid->update_paid($memStat['id']);
        }
       
    }

 // $redirect = "Location: ".$_SESSION['adminurl'];
 //header($redirect);
 //exit;
}
?>