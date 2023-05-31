<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';


$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);
$regs = $_SESSION['classRegistrations'];

if (isset($_POST['submitDeleteReg'])) {
  foreach ($regs as $reg) {

    $delID = "del".$reg['id'];

   if (isset($_POST["$delID"])) {

    $classReg->id = $reg['id'];
    $classid = $reg['classid'];
    $classReg->delete();
    
    $danceClass->decrementCount($classid);
   }
  }
}
  

        $redirect = "Location: ".$_SESSION['returnurl'];
        header($redirect);
        exit;
  
   

?>