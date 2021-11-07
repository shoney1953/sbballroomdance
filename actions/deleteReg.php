<?php
session_start();

include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);

   
    $classReg->id = $_POST['id'];
   
    $classReg->delete();
    echo ' Registration was deleted <br>';
    $redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;

?>