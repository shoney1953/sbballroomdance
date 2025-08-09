<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DinnerMealChoices.php';
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
$dinnermealchoices = new DinnerMealChoices($db);
$rowCount = 0;
$eventId = 0;
$mealChoices = [];
$mealChoices = $_SESSION['mealChoices'];


if (isset($_POST['updateMealChoices'])) {
    if (isset($_POST['eventid'])) {
       foreach($mealChoices as $mealchoice)  {
        $chkboxID = "mc".$mealchoice['id'];
        $mchoiceNM = "mcname".$mealchoice['id'];
        $mcdescNM = "mcdesc".$mealchoice['id'];
        $mPrice = "mprice".$mealchoice['id'];
        $gPrice = "gprice".$mealchoice['id'];

        if (isset($_POST[$chkboxID])) {
          
            $dinnermealchoices->id = (int)$mealchoice['id'];
            $dinnermealchoices->mealname = $_POST[$mchoiceNM];
            $dinnermealchoices->mealdescription = $_POST[$mdescNM];
            $dinnermealchoices->guestprice = $_POST[$gPrice];
            $dinnermealchoices->memberprice = $_POST[$mPrice];

            $dinnermealchoices->update();
        }
       }

    }
}



$redirect = "Location: ".$_SESSION['adminurl']."#events";
header($redirect);
exit; 


?>
