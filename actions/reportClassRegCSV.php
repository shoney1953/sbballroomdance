<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$user = new User($db);
$regArr = [];
$memReg = 0;
$nonMemReg = 0;

$today = date("m-d-Y");
$fileName = "ClassEmails-".$today.".CSV";
var_dump($fileName);

if (isset($_POST['submitClassRepCSV'])) {
 
    if (isset($_POST['classId'])) {
        if ($_POST['classId'] !== '') {
            $classId = htmlentities($_POST['classId']);
            $result = $classReg->read_ByClassId($classId);
        } 
}
    $rowCount = $result->rowCount();
    $num_reg = $rowCount;
    if ($rowCount > 0) {
       $file = fopen($fileName,'w');
       if ($file === false) {
        die(
            'ERROR Opening File: '.$filename);
       }
       else {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $reg_item = array(

                'email' => $email

            );
            array_push($regArr, $reg_item);
        }
        foreach ($regArr as $row) {
 
            fputcsv($file, $row);
        }
        fclose($file);
       }
      
    }

 



}
/* 
$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);
exit; */

?>
