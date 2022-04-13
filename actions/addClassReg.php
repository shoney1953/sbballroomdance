<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';

date_default_timezone_set("America/Phoenix");

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
$upcomingClasses = [];
$upcomingClasses = $_SESSION['upcoming_classes'] ;
$users = [];
$users = $_SESSION['regUsers'] ;
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$danceClass = new DanceClass($db);
$search = '%';
if (isset($_POST['submitAddReg'])) {
   
    
    foreach($upcomingClasses as $class) {
       
        $chkboxID = "cb".$class['id'];
     
        
        if (isset($_POST["$chkboxID"])) {
            $classReg->classid = $class['id'];
            
         
            foreach($users as $usr) {
                $usrID = "us".$usr['id'];
          
                if (isset($_POST["$usrID"])) {
              
                    $classReg->firstname = $usr['firstname'];
                    $classReg->lastname = $usr['lastname'];
                    $classReg->email = $usr['email'];
                    $classReg->userid = $usr['id'];
                    $classReg->create();
                    $danceClass->addCount($classReg->classid);
                }
            }
        
        
        } //end isset
     } // end foreach
       
}
   $redirect = "Location: ".$_SESSION['adminurl']."#classregistrations";
   header($redirect);
exit;

?>