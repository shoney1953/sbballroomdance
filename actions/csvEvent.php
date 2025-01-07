<?php
session_start();

require_once '../config/Database.php';
require_once '../models/EventRegistration.php';
require_once '../models/Event.php';
require_once '../models/User.php';
require_once '../includes/CreateCSV.php';

$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);
$user = new User($db);
$event = new Event($db);
$regArr = [];
$memReg = 0;
$nonMemReg = 0;
$eventName = '';
$eventType = '';
$_SESSION['csvEvent'] = [];
$csvEvent = [];
$number = 0;
$title_array = 
[
'#',  
'First Name',
'Last Name', 
'Email',
'Member' 

];
  array_push($csvEvent, $title_array);



    if (isset($_POST['eventId'])) {
        if ($_POST['eventId'] !== '') {
            $event->id = htmlentities($_POST['eventId']);
            $event->read_single();

                $eventName = $event->eventname;
                $eventType = $event->eventtype;
                $eventId = htmlentities($_POST['eventId']);
            
            $result = $eventReg->read_ByEventId($eventId);
        } else {
        $result = $eventReg->read();
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
            array_push($csvEvent, $reg_item);
        }
    }


}

$today = date("m-d-Y");
$fileName = $eventName." ".$eventType." ".$today.'.csv';
writeToCsv($csvEvent, $fileName);



?>
