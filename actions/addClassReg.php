<?php
session_start();

include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
include_once '../models/DanceClass.php';
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);

   
    $classReg->classid = $_POST['classid'];
    $classReg->firstname = $_POST['firstname'];
    $classReg->lastname = $_POST['lastname'];
    $classReg->email = $_POST['email'];
    $classReg->userid = 0;
 
    $classReg->create();
    $danceClass->addCount($classReg->classid);
    echo ' Registration was created <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>