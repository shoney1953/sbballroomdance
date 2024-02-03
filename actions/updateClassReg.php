<?php
session_start();
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
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
$classReg = new ClassRegistration($db);

$regs = $_SESSION['classRegs'];


if (isset($_POST['submitUpdateReg'])) {
  foreach ($regs as $reg) {
    $updID = "upd".$reg['id'];
   
    $fnamID = "fnam".$reg['id'];
    $lnamID = "lnam".$reg['id'];
    $emailID = "email".$reg['id'];
    $useridID = "userid".$reg['id'];
  
    if (isset($_POST["$updID"])) {
        $regID = (int)substr($updID,3);
        $classReg->id = $regID;
        $classReg->firstname = $_POST["$fnamID"];
        $classReg->lastname = $_POST["$lnamID"];
        $classReg->classid = $_POST['classid'];
        $classReg->email = $_POST["$emailID"];
        $classReg->userid = $_POST["$useridID"];
        $classReg->update();
    }
    }
   
}
$redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;
?>