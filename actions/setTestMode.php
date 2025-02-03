<?php
session_start();

if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}

if (isset($_POST['submitTestMode'])) { 
  if (isset($_POST['testmode'])) {
    $_SESSION['testmode'] = 'YES';
  }

  

}
$redirect = "Location: ".$_SESSION['homeurl'];
header($redirect);  
?>