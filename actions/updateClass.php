<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DanceClass.php';
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
$class = new DanceClass($db);

if (isset($_POST['submitUpdate'])) {

    $class->id = $_POST['id'];
    $class->classname = $_POST['classname'];
    $class->classlevel = $_POST['classlevel'];
    $class->classlimit = $_POST['classlimit'];
    $class->instructors = $_POST['instructors'];
    $class->registrationemail = $_POST['registrationemail'];
    $class->numregistered = $_POST['numregistered'];
    $class->room = $_POST['room'];
    $class->date = $_POST['date'];
    $class->time = $_POST['time'];
    $class->update();
    echo ' Class was updated  <br>';
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>