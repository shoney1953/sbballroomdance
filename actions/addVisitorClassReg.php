<?php
session_start();
require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/Visitor.php';

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
$class = new DanceClass($db);
$visitor = new Visitor($db);
var_dump($_POST);

if (isset($_POST['submitAddVisitorReg'])) {
    $classReg->classid = $_POST['classid'];
    $classReg->firstname = $_POST['firstname'];
    $classReg->lastname = $_POST['lastname'];
    $classReg->email = $_POST['email'];
    $classReg->paid = 0;
    $classReg->userid = 0;
 
    $classReg->create();
    $class->addCount($classReg->classid);
    /* assume not a member so add to visitor file */
    $visitor->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
    $visitor->firstname = $_POST['firstname'];
    $visitor->lastname = $_POST['lastname'];
    $visitor->create();

}  
   


    $redirect = "Location: ".$_SESSION['adminurl']."#classregistrations";
header($redirect);
exit;

?>