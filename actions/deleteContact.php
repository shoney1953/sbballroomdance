<?php
session_start();

include_once '../config/Database.php';
include_once '../models/Contact.php';
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