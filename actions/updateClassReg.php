<?php
session_start();
include_once '../config/Database.php';
include_once '../models/ClassRegistration.php';
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

if (isset($_POST['submitUpdateReg'])) {

    $classReg->id = $_POST['id'];
    $classReg->firstname = $_POST['firstname'];
    $classReg->lastname = $_POST['lastname'];
    $classReg->classid = $_POST['classid'];
    $classReg->email = $_POST['email'];

    $classReg->update();
    echo ' Registration was updated  <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>