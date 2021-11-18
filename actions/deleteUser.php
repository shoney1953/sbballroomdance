<?php
session_start();

include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/ClassRegistration.php';
include_once '../models/User.php';
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
$eventReg = new EventRegistration($db);
$classReg = new ClassRegistration($db);
$user = new User($db);
   
    $user->id = $_POST['id'];
    $eventReg->deleteUserid($user->id);
    $classReg->deleteUserid($user->id);
    $user->delete();

    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>