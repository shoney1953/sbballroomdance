<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DanceClass.php';
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$class = new DanceClass($db);
$classes = [];
$num_classes = 0;
$searchName = '%';
$searchLevel = '%';
$upcomingClasses = [];

if (isset($_POST['searchClasses'])) {
  $_SESSION['filtered_classes'] = [];
    if (isset($_POST['searchname'])) {
        $searchName .= $_POST['searchname'];
        $searchName .= '%';
    }
     if (isset($_POST['searchlevel'])) {
        $searchLevel .= $_POST['searchlevel'];
        $searchLevel .= '%';
    }

        $result = $class->searchNameLevel($searchName, $searchLevel);
        
        $rowCount = $result->rowCount();
        $num_classes = $rowCount;
    
$current_month = date('m');
$current_year = date('Y');

$numUpcomingClasses = 0;
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
  
        $class_item = array(
            'id' => $id,
            'classname' => $classname,
            'classlevel' => $classlevel,
            'classlimit' => $classlimit,
            'date' => $date,
            'time' => date('h:i:s A', strtotime($time)),
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            "classnotes" => $classnotes,
            'numregistered' => $numregistered,
            'date2' => $date2,
            'date3' => $date3,
            'date4' => $date4,
            'date5' => $date5,
            'date6' => $date6,
            'date7' => $date7,
            'date8' => $date8,
            'date9' => $date9
        );
        array_push($classes, $class_item);
        $class_month = substr($row['date'], 5, 2);
        $class_year = substr($row['date'], 0, 4);
           
        if ((int)$current_year < (int)$class_year) {
          
            $numUpcomingClasses++;
            array_push($upcomingClasses, $class_item);
        } elseif ((int)$class_year >= (int)$current_year) {         
            if ((int)$current_month <= (int)$class_month) {
                $numUpcomingClasses++;
                array_push($upcomingClasses, $class_item);
             } 
            
        }

   $_SESSION['numfilteredclasses'] = $numUpcomingClasses;
       
     
    }
 $_SESSION['filtered_classes'] = $upcomingClasses;

} 
  $redirect = "Location: ".$_SESSION['returnurl']."?name=".$searchName."&level=".$searchLevel;
    header($redirect);
    exit;
}

?>