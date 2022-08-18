
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
        if (($_SESSION['role'] != 'ADMIN') && 
        ($_SESSION['role'] != 'SUPERADMIN') &&
        ($_SESSION['role'] != 'INSTRUCTOR')) {
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

   var_dump($_POST);
    $class->classname = $_POST['classname'];
    $class->classlevel = $_POST['classlevel'];
    $class->classlimit = $_POST['classlimit'];
    $class->instructors = $_POST['instructors'];
    $class->registrationemail = $_POST['registrationemail'];
    $class->classnotes = $_POST['classnotes'];
    $class->room = $_POST['room'];
    $class->date = $_POST['date'];
    $class->time = $_POST['time'];
    $class->numregistered = 0;
    $class->create();
    echo ' Class was created <br>';
 
    $redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;

?>