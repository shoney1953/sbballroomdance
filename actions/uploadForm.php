<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Event.php';
$database = new Database();
$db = $database->connect();

$fileurl = "/uploads/forms/";
$event = new Event($db);
if (!isset($_POST['submitUpload'])) {
   $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
     exit;
}

if (isset($_POST['submitUpload'])) {
  $file = $_FILES['fileToUpload'];

  $fileTemp = $_FILES['fileToUpload']['tmp_name'];
  $fileName = $_FILES['fileToUpload']['name'];
  $fileEx = explode('.',$fileName);

  $fileActualEX = strtolower(end($fileEx));

  $allowed = array('pdf');
  if (in_array($fileActualEX, $allowed)) {
    $newFileName = $fileEx[0].".".$fileActualEX;
    $fileDestination = "../uploads/forms/";
    makeDir($fileDestination);
    $fileDestination .= "/".$newFileName;


    move_uploaded_file($fileTemp, $fileDestination);

    $event->id = $_POST['eventid'];
    $event->eventform = $newFileName;
    $event->update_form();
   //  $redirect = "Location: ".$_SESSION['requrl']."?success=fileuploaded";
   //  header($redirect);
   //   exit;
  } else {
        $redirect = "Location: ".$_SESSION['returnurl']."?error=filetype";
        header($redirect);
     exit;
  
  }


}
function makeDir($path)
{
     return is_dir($path) || mkdir($path, 0777);
}


?>