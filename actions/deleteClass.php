
<?php
session_start();

require_once '../config/Database.php';
require_once '../models/DanceClass.php';
require_once '../models/ClassRegistration.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

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
$classReg = new ClassRegistration($db);
$allClasses = $_SESSION['allClasses'];

if (isset($_POST['submitDelete'])) {
    foreach ($allClasses as $ca) {
        $clSelectChk = "clselect".$ca['id'];
        if (isset($_POST["$clSelectChk"])) {

            $class->id = $ca['id'];
     
            $class->delete();
            $classReg->deleteClassid($class->id);
        }
    }

}
   
    $redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit;

?>