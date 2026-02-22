
<?php
session_start();

require_once '../config/Database.php';
require_once '../models/DanceClass.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username']))
{
   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
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
       $cldate2ID = "cldate2".$ca['id'];
       $cldate3ID = "cldate3".$ca['id'];
       $cldate4ID = "cldate4".$ca['id'];
       $cldate5ID = "cldate5".$ca['id'];
       $cldate6ID = "cldate6".$ca['id'];
       $cldate7ID = "cldate7".$ca['id'];
       $cldate8ID = "cldate8".$ca['id'];
       $cldate9ID = "cldate9".$ca['id'];

    $class->classname = $_POST["$clnamID"];
    $class->classlevel = $_POST["$cllevelID"];
    $class->classlimit = $_POST["$cllimitID"];
    $class->instructors = $_POST["$clinstructorsID"];
    $class->registrationemail = $_POST["$clregemailID"];
    $class->classnotes = $_POST["$clnotesID"];
    $class->room = $_POST["$clroomID"];
    $class->date = $_POST["$cldateID"];
    $class->time = $_POST["$cltimeID"];


    $class->date2 = $_POST["$cldate2ID"];
    $class->date3 = $_POST["$cldate3ID"];
    $class->date4 = $_POST["$cldate4ID"];
    $class->date5 = $_POST["$cldate5ID"];
    $class->date6 = $_POST["$cldate6ID"];
    $class->date7 = $_POST["$cldate7ID"];

    $class->date8 = $_POST["$cldate8ID"];
    $class->date9 = $_POST["$cldate9ID"];
    
    
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