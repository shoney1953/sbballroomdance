<?php
session_start();

include_once '../config/Database.php';
include_once '../models/EventRegistration.php';
include_once '../models/ClassRegistration.php';
include_once '../models/DanceClass.php';
include_once '../models/Event.php';
include_once '../models/User.php';
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
$eventRegs = [];
$classRegs = [];
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$classReg = new ClassRegistration($db);
$class = new DanceClass($db);
$event = new Event($db);
$user = new User($db);
   
    $user->id = $_POST['id'];


    $result = $classReg->read_ByUserid($user->id);
    
    $rowCount = $result->rowCount();

    
    if($rowCount > 0) {
      
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'classid' => $classid,
                'classname' => $classname,
                'classtime' => $classtime,
                'classdate' => $classdate,
                'email' => $email,
                "dateregistered" => $dateregistered
            );
           $class->decrementCount($classid);
        
        }
      
    
    } 
 
    $result = $eventReg->read_ByUserid($user->id);
    
    $rowCount = $result->rowCount();

    
    if($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'eventid' => $eventid,
                'eventname' => $eventname,
                'eventdate' => $eventdate,
                'email' => $email,
                "dateregistered" => $dateregistered
            );
           
            $event->decrementCount($eventid);
        }
      
    
    } 
    $eventReg->deleteUserid($user->id);
    $classReg->deleteUserid($user->id);
    $user->delete();
    $redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit;

?>