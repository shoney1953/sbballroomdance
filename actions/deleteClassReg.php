<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && 
        ($_SESSION['role'] != 'SUPERADMIN') &&
        ($_SESSION['role'] != 'INSTRUCTOR') &&
        ($_SESSION['role'] != 'MEMBER')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['profileurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);

   
    $classReg->id = $_POST['id'];
    $classid = $_POST['classid'];
    $classReg->delete();
    
    $danceClass->decrementCount($classid);

  
  
    if ($_SESSION['role'] != 'MEMBER') {
        $redirect = "Location: ".$_SESSION['adminurl']."#classregistrations";
        header($redirect);
        exit;
    } else {
        $redirect = "Location: ".$_SESSION['profileurl']."#";
        header($redirect);
        exit;
    }


?>