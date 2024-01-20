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
'Email', 
'Phone', 
'Address',
'Date Joined', 
'Paid', 
'Year'];
  array_push($csvMembers, $title_array);
if ($rowCount > 0) {

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $csv_item = array(

          'lastname' => $lastname,
          'firstname' => $firstname,
          'email' => $email,
          'phone' => $phone,
          'streetaddress' => $streetaddress,
          'datejoined' => $datejoined,
          'paid' => $paid,
          'year' => $year

      );
      array_push($csvMembers, $csv_item);
  
  }

  $_SESSION['csvMembers'] = $csvMembers;
} 

writeToCsv($csvMembers);




// $redirect = "Location: ".$_SESSION['adminurl'];
// header($redirect);
// exit;

?>
