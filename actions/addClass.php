
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
$allClasses = $_SESSION['allClasses'];

if (isset($_POST['submitAdd'])) {
   foreach ($allClasses as $ca) {
     
       $clSelectChk = "clselect".$ca['id'];
       $clidID = "clid".$ca['id'];
    if (isset($_POST["$clidID"])) {
     if ($ca['id'] === $_POST["$clidID"]) {
 
       $clnamID = "clnam".$ca['id'];
       $cllevelID = "cllevel".$ca['id'];
       $clnotesID = "clnotes".$ca['id'];
       $clroomID = "clroom".$ca['id'];
       $cldateID = "cldate".$ca['id'];
       $cltimeID = "cltime".$ca['id'];
       $clinstructorsID = "clinstructors".$ca['id'];
       $clregemailID = "clregemail".$ca['id'];
       $clnumregID = "clnumreg".$ca['id'];
       $cllimitID = "cllimit".$ca['id'];

    $class->classname = $_POST["$clnamID"];
    $class->classlevel = $_POST["$cllevelID"];
    $class->classlimit = $_POST["$cllimitID"];
    $class->instructors = $_POST["$clinstructorsID"];
    $class->registrationemail = $_POST["$clregemailID"];
    $class->classnotes = $_POST["$clnotesID"];
    $class->room = $_POST["$clroomID"];
    $class->date = $_POST["$cldateID"];
    $class->time = $_POST["$cltimeID"];
    $class->numregistered = 0;
    $class->create();
    break;
       }
    }
}
}
 
    $redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;

?>