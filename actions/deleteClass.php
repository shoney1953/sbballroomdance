
<?php
session_start();

include_once '../config/Database.php';
include_once '../models/DanceClass.php';
$database = new Database();
$db = $database->connect();
$class = new DanceClass($db);

   
    $class->id = $_POST['id'];
   
    $class->delete();
    echo ' Class was deleted <br>';
    $redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);
exit;

?>