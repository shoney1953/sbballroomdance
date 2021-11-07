<?php
session_start();

include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);

   
    $classReg->classid = $_POST['classid'];
    $classReg->firstname = $_POST['firstname'];
    $classReg->lastname = $_POST['lastname'];
    $classReg->email = $_POST['email'];
 
    $classReg->create();
    echo ' Registration was created <br>';
    $redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;

?>