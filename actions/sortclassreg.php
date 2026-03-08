
<?php
session_start();
require_once '../config/Database.php';

require_once '../models/ClassRegistration.php';

$classRegistrations = [];
$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);



if (isset($_POST['classid'])) {
  $classid = $_POST['classid'];
 if ((isset($_POST['regdate'])) && ($_POST['regdate'] === '1') ) {

$result = $classReg->read_ByClassIdRegDate($_POST['classid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['classRegByRegDate'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'userid' => $userid,
            'email' => $email,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($classRegistrations, $reg_item);
  
    } // while
    
} // rowcount
$_SESSION['classRegByRegDate']  = $classRegistrations;

$redirect = "Location: ".$_SESSION['classmemnurl']."?sort=RegDate&id=".$classid;

    header($redirect);
    exit;


 } // regdate
   

  
    } // classiid

  

?>