<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/MemberPaid.php';
require_once '../includes/CreateCSV.php';
// $members = $_SESSION['members'];
$database = new Database();
$db = $database->connect();
$user = new User($db);
$thisYear = date("Y"); 

$result = $user->readForCsv($thisYear);

$rowCount = $result->rowCount();
$num_members = $rowCount;
$_SESSION['csvMembers'] = [];
$csvMembers = [];
$title_array = 
['Last Name',
'First Name', 
'Phone', 
'Email', 
'Address',
'Date Joined', 
'Joined Online',
'Paid', 
'Year',
'Paid Online'
];
  array_push($csvMembers, $title_array);
if ($rowCount > 0) {

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $csv_item = array(

          'lastname' => $lastname,
          'firstname' => $firstname,
          'phone' => $phone,
          'email' => $email,
          'streetaddress' => $streetaddress,
          'datejoined' => substr($datejoined,0,10),
          'joinedonline' => $joinedonline,
          'paid' => $paid,
          'year' => $year,
          'paidonline' => $paidonline

      );
      if (strpos($csv_item['email'], '@xx')) {
        $csv_item['email'] = ' ';
      }
      array_push($csvMembers, $csv_item);
  
  }

  $_SESSION['csvMembers'] = $csvMembers;
} 
$today = date("m-d-Y");
$fileName = 'SBDCMembers '.$today.'.csv';
writeToCsv($csvMembers, $fileName);




// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
