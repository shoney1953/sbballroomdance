
<?php
session_start();

include_once '../config/Database.php';
include_once '../models/DanceClass.php';
$database = new Database();
$db = $database->connect();
$class = new DanceClass($db);

   
    $class->classname = $_POST['classname'];
    $class->classlevel = $_POST['classlevel'];
    $class->classlimit = $_POST['classlimit'];
    $class->instructors = $_POST['instructors'];
    $class->registrationemail = $_POST['registrationemail'];
    $class->room = $_POST['room'];
    $class->date = $_POST['date'];
    $class->time = $_POST['time'];
    $class->numregistered = 0;
    $class->create();
    echo ' Class was created <br>';
    $redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;

?>