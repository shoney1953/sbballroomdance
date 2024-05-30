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
$number = 0;
$title_array = 
[
'#',  
'First Name',
'Last Name', 
'Email', 
'Member',
'Dates Attended'
];
  array_push($csvClass, $title_array);
  


    if (isset($_POST['classId'])) {
        if ($_POST['classId'] !== '') {
            $class->id = htmlentities($_POST['classId']);
            $class->read_single();

            $title_array2 = 
            [
                ' ',  
                ' ',
                ' ', 
                ' ', 
                ' ',
                substr($class->date,5,5),
                substr($class->date2,5,5),
                substr($class->date3,5,5),
                substr($class->date4,5,5),
                substr($class->date5,5,5),
                substr($class->date6,5,5),
                substr($class->date7,5,5),
                substr($class->date8,5,5),
                substr($class->date9,5,5)
                ];
                array_push($csvClass, $title_array2);
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
               $number++;
            $reg_item = array(
                'number' => $number,
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
