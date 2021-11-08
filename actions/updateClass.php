<?php

session_start();
include_once '../config/Database.php';
include_once '../models/DanceClass.php';
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