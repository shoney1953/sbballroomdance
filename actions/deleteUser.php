<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/ClassRegistration.php';
require_once '../models/MemberPaid.php';
require_once '../models/DanceClass.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';

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
$memberPaid = new MemberPaid($db);
$class = new DanceClass($db);
$event = new Event($db);
$user = new User($db);
$userArc = new UserArchive($db);
   
    $user->id = $_POST['id'];
    $user->read_single();

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
   /* Archive the user before deleting */
    $userArc->firstname = $user->firstname;
    $userArc->lastname = $user->lastname;
    $userArc->username = $user->username;
    $userArc->email = $user->email;
    $userArc->role = $user->role;
    $userArc->password = $user->password;
    $userArc->created = $user->created;
    $userArc->role = $user->role;
    $userArc->passwordChanged = $user->passwordChanged;
    $userArc->streetAddress = $user->streetaddress;
    $userArc->city = $user->city;
    $userArc->state = $user->state;
    $userArc->zip = $user->zip;
    $userArc->hoa = $user->hoa;
    $userArc->phone1 = $user->phone1;
    $userArc->phone2 = $user->phone2;
    $userArc->notes = $user->notes;
    $userArc->lastLogin = $user->lastLogin;
    $userArc->create();

    $eventReg->deleteUserid($user->id);
    $classReg->deleteUserid($user->id);
    $memberPaid->deleteUserid($user->id);
    $user->delete();
    
    $redirect = "Location: ".$_SESSION['adminurl']."#users";
header($redirect);
exit;

?>