<?php
session_start();

include_once '../config/Database.php';
include_once '../models/Contact.php';
$database = new Database();
$db = $database->connect();
$contact = new Contact($db);

  var_dump($_POST) ;
    if(isset($_POST['delContactBefore'])) {
        echo 'is set <br>';
        $delContactBefore = $_POST['delContactBefore'];
        $contact->delete_beforeDate($delContactBefore);
        echo ' Contacts were deleted <br>';
       }

    $redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;

?>