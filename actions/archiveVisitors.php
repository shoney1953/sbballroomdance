<?php
session_start();

require_once '../config/Database.php';

require_once '../models/Visitor.php';
require_once '../models/VisitorsArch.php';



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

$database = new Database();
$db = $database->connect();

$visitor = new Visitor($db);
$visitorArch = new VisitorArch($db);
$visitors = $_SESSION['visitors'];

if (isset($_POST['submitArchive'])) {
  foreach($visitors as $vi) {
 

      $visitorArch->firstname = $vi['firstname'];
      $visitorArch->lastname = $vi['lastname'];
      $visitorArch->email = $vi['email'];
      $visitorArch->logindate = $vi['datelogin']; /* unformatted date */
      $visitorArch->notes = $vi['notes'];
      $visitorArch->numlogins = $vi['numlogins'];
      $visitor->id = $vi['id'];
      $visitorArch->create();
      $visitor->delete();
}
}


$redirect = "Location: ".$_SESSION['adminurl']."#visitors";
header($redirect);
exit;

?>