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
        if ($eventType === 'BBQ Picnic') {
            $title_array = 
            [
            '#',  
            'First Name',
            'Last Name', 
            'Email',
            'Member',
            'Cornhole',
            'Softball'

            ];
            array_push($csvEvent, $title_array);
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
                    'member' => $member,
                    'cornhole' => $cornhole,
                    'softball' => $softball

    
                );
            
                array_push($csvEvent, $reg_item);
            }
        } else {
            $title_array = 
            [
            '#',  
            'First Name',
            'Last Name', 
            'Email',
            'Member',
            'Dinner',
            'Paid',
            'Meal',
            'Restriction'

            ];
            array_push($csvEvent, $title_array);

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
                'member' => $member,
                'ddattenddinner' => $ddattenddinner,
                'paid' => $paid,
                'mealname' => $mealname,
                'dietaryrestriction' => $dietaryrestriction


            );
        
            array_push($csvEvent, $reg_item);
        }
    }
    }


}

$today = date("m-d-Y");
$fileName = $eventName." ".$eventType." ".$today.'.csv';

writeToCsv($csvEvent, $fileName);





?>
