<?php
session_start();

include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
include_once '../models/DanceClass.php';
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