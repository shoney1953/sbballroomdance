<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/ClassRegistrationArch.php';
require_once '../models/DanceClass.php';
require_once '../models/DanceClassArch.php';

$allClasses = $_SESSION['allClasses'];


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

$classReg = new ClassRegistration($db);
$classRegArch = new ClassRegistrationArch($db);

$class = new DanceClass($db);
$classArch = new DanceClassArch($db);

if (isset($_POST['submitArchive'])) {  
  foreach($allClasses as $ca) {
    $clSelectChk = "clselect".$ca['id'];
    if (isset($_POST["$clSelectChk"])) {

      $classArch->previd = $ca['id'];
      $classArch->classlevel = $ca['classlevel'];
      $classArch->classname = $ca['classname'];
      $classArch->room = $ca['room'];
      $classArch->registrationemail = $ca['registrationemail'];
      $classArch->date = $ca['date'];
      $classArch->time = $ca['time2'];
      $classArch->instructors = $ca['instructors'];
      $classArch->classlimit = $ca['classlimit'];
      $classArch->numregistered = $ca['numregistered'];
      $classArch->classnotes = $ca['classnotes'];

      $classArch->create();
      $classRegArch->classid = $db->lastInsertId();
    
      $classReg->classid = $ca['id'];
      $result = $classReg->read_ByClassid($ca['id']);
      $rowCount = $result->rowCount();

      if ($rowCount > 0) {

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'classid' => $classid,
                'classname' => $classname,
                'classdate' => $classdate,
                'classtime' => $classtime,
                'userid' => $userid,
                'email' => $email,
                'registeredby' => $registeredby,
                'dateregistered' => $dateregistered
            );

            $classRegArch->archclassid = $reg_item['classid'];
            $classRegArch->firstname = $reg_item['firstname'];
            $classRegArch->lastname = $reg_item['lastname'];
            $classRegArch->email = $reg_item['email'];
            $classRegArch->userid = $reg_item['userid'];
            $classRegArch->dateregistered = $reg_item['dateregistered'];
            $classRegArch->registeredby = $reg_item['registeredby'];
            $classRegArch->classname = $reg_item['classname'];     
            $classRegArch->classdate = $reg_item['classdate'];
            $classRegArch->classtime = $reg_item['classtime'];

            $classRegArch->create();
      
        }
       $classReg->deleteClassid($ca['id']);
     
  }
  $class->id = $ca['id'];
  $class->delete();
}
}
}

   
   
    
$redirect = "Location: ".$_SESSION['adminurl']."#classes";
header($redirect);
exit; 
 
?>