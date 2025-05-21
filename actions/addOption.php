<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Options.php';
date_default_timezone_set("America/Phoenix");
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
$option = new Options($db);
   
    $option->year = $_POST['optionyear'];
    $option->renewalmonth = $_POST['renewalmonth'];
    $option->discountmonth = $_POST['discountmonth'];

    $option->create();

    $redirect = "Location: ". $_SESSION['optionurl'];
 
     header($redirect);
     exit;

?>