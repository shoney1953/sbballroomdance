<?php
session_start();

include_once '../config/Database.php';
include_once '../models/Contact.php';
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
$contact = new Contact($db);


    if(isset($_POST['delContactBefore'])) {
        $delContactBefore = $_POST['delContactBefore'];
        $contact->delete_beforeDate($delContactBefore);
        echo ' Contacts were deleted <br>';
       }

$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>