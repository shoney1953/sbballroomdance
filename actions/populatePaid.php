<?php
session_start();
require_once '../config/Database.php';
require_once '../models/MemberPaid.php';
if (isset($_POST['submitPopPaid'])) {
  $database = new Database();
$db = $database->connect();
$memPaid = new MemberPaid($db);
$memPaidNew = new MemberPaid($db);
$thisYear = date("Y");
$nextYear = date('Y', strtotime('+1 year')); 
$result = 0;
$curpaid =  $_SESSION['memPaidCurrent'];
foreach($curpaid as $c) {
    if ($c['userid'] == 10) {
      var_dump($c);
      $result = $memPaid->read_byUseridYear($c['userid'], $nextYear);
      var_dump($result);
    }

   if (!$memPaid->read_byUseridYear($c['userid'], $nextYear)) {

      $memPaidNew->userid = $c['userid'];

      $memPaidNew->year = $nextYear; 

      $memPaidNew->paid = 0;
      $memPaidNew->paidonline = 0;
 
      $memPaidNew->create();
   }

}

}
// $redirect = "Location: ".$_SESSION['returnurl'];
// header($redirect);
// exit;
?>