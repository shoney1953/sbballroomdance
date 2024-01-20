<?php
function writeToCsv($array) {

  header('Content-Type: text/csv');
  $today = date("m-d-Y");
  $fileName = 'SBDCMembers '.$today.'.csv';
  header('Content-Disposition: attachment; filename="'.$fileName.'"'); 
  $fp = fopen('php://output', 'wb');
  
  foreach ( $array as $entry ) {

    fputcsv($fp, $entry, ',');
}

  fclose($fp);
}

?>