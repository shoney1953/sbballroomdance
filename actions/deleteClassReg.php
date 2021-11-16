<?php
session_start();

include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
include_once '../models/DanceClass.php';
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
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);

   
    $classReg->id = $_POST['id'];
    $classid = $_POST['classid'];
    $classReg->delete();
    
    $danceClass->decrementCount($classid);

    echo ' Registration was deleted <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>