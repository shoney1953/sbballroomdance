<?php
function writeToCsv($array) {
  $fp = fopen('php://temp','rb+');
  // $fp = fopen($filename, 'w');

  // $keys = array_keys(get_object_vars($array[0]));
  // fputcsv($fp, $keys);


  foreach ($array as $entry) {

      $values = array_values(get_object_vars($entry));
      fputcsv($fp, $values);
  }

  fclose($fp);
}
?>