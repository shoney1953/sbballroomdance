<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
var_dump($_POST);
$database = new Database();
$db = $database->connect();
$user = new User($db);

if (isset($_POST['submitDirUser'])) {

 

    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>