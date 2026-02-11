<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/ClassRegistration.php';
require_once '../models/MemberPaid.php';
require_once '../models/MemberPaidArchive.php';
require_once '../models/DanceClass.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../models/UserArchive.php';
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
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
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
        }
       } else {
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
       }
}
$eventRegs = [];
$classRegs = [];
$users = $_SESSION['process_users'];
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$classReg = new ClassRegistration($db);
$memberPaid = new MemberPaid($db);
$memberPaidArch = new MemberPaidArch($db);
$class = new DanceClass($db);
$event = new Event($db);
$user = new User($db);
$userArc = new UserArchive($db);

if (isset($_POST['submitArchiveUser'])) {
  foreach ($users as $usr) {
    $upChk = "ar".$usr['id'];
    $usrSelChk ="userSel".$usr['id']; 
    $idID = "id".$usr['id'];
    if (isset($_POST["$usrSelChk"])) {
        $user->id = $_POST["$idID"];
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
                    'registeredby' => $registeredby,
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
                    'registeredby' => $registeredby,
                    'dateregistered' => $dateregistered,
                    'numlogins' => $numlogins
                );
            
                $event->decrementCount($eventid);
            }
        
        
        } 
    /* Archive the user before deleting */
        $userArc->firstname = $user->firstname;
        $userArc->previd = $user->id;
        $userArc->lastname = $user->lastname;
        $userArc->username = $user->username;
        $userArc->email = $user->email;
        $userArc->role = $user->role;
        $userArc->password = $user->password;
        $userArc->created = $user->created;
        $userArc->role = $user->role;
        $userArc->passwordChanged = $user->passwordChanged;
        $userArc->streetAddress = $user->streetAddress;
        $userArc->city = $user->city;
        $userArc->state = $user->state;
        $userArc->zip = $user->zip;
        $userArc->hoa = $user->hoa;
        $userArc->phone1 = $user->phone1;
        $userArc->phone2 = $user->phone2;
        $userArc->notes = $user->notes;
        $userArc->directorylist = $user->directorylist;
        $userArc->fulltime = $user->fulltime;
        $userArc->lastLogin = $user->lastLogin;
        $userArc->numlogins = $user->numlogins;
        $userArc->robodjnumlogins = $user->robodjnumlogins;
        $userArc->robodjlastlogin = $user->robodjlastlogin;
        $userArc->regformlink = $user->regformlink;
        $userArc->memberorigcreated = $user->created;
        $userArc->create();

        $eventReg->deleteUserid($user->id);
        $classReg->deleteUserid($user->id);
        $result = $memberPaid->read_byUserid($user->id);

        $rowCount = $result->rowCount();


        if ($rowCount > 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $paid_item = array(
                    'id' => $id,
                    'paid' => $paid,
                    'year' => $year,
                    'paidonline' => $paidonline

                );
             $memberPaidArch->userid = $user->id;
             $memberPaidArch->paid = $paid_item['paid'];
             $memberPaidArch->year = $paid_item['year'];
             $memberPaidArch->paidonline = $paid_item['paidonline'];
             $memberPaidArch->create();

            }
}
        $memberPaid->deleteUserid($user->id);
        $user->delete();
    }
  }
}  
    unset($_SESSION['process_users']);
    $redirect = "Location: ".$_SESSION['adminurl']."#users";
header($redirect);
exit;

?>