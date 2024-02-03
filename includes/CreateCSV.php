<?php
function writeToCsv($array, $fileName) {

  header('Content-Type: text/csv');

  header('Content-Disposition: attachment; filename="'.$fileName.'"'); 
  $fp = fopen('php://output', 'wb');
  
  foreach ( $array as $entry ) {

    fputcsv($fp, $entry, ',');
}

  fclose($fp);
}

?>