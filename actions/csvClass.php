<?php
session_start();

require_once '../config/Database.php';
require_once '../models/ClassRegistration.php';
require_once '../models/DanceClass.php';
require_once '../models/User.php';
require_once '../includes/CreateCSV.php';

$database = new Database();
$db = $database->connect();
$classReg = new ClassRegistration($db);
$user = new User($db);
$class = new DanceClass($db);
$regArr = [];
$memReg = 0;
$nonMemReg = 0;
$className = '';
$classLevel = '';
$_SESSION['csvClass'] = [];
$csvClass = [];
$title_array = 
['Last Name',
'First Name', 
'Email', 
'Member'
];
  array_push($csvClass, $title_array);



    if (isset($_POST['classId'])) {
        if ($_POST['classId'] !== '') {
            $class->id = htmlentities($_POST['classId']);
            $class->read_single();
            $className = $class->classname;
            $classLevel = $class->classlevel;
            $classId = htmlentities($_POST['classId']);

            $result = $classReg->read_ByClassId($classId);
        } else {
        $result = $classReg->read();
    }

    $rowCount = $result->rowCount();
    $num_reg = $rowCount;
    $member = '';
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            if ($user->getUserName($email)) {
              $member = "YES"; 
              } else {
              $member = "NO";
               }
            $reg_item = array(

                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'member' => $member

            );
            array_push($csvClass, $reg_item);
        }
    }

    


}
$today = date("m-d-Y");
$fileName = $className." ".$classLevel." ".$today.'.csv';
writeToCsv($csvClass, $fileName);



?>
