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
  if (isset($_POST['testmodeON'])) {
    $_SESSION['testmode'] = 'YES';
  }
 if (isset($_POST['testmodeOFF'])) {
    $_SESSION['testmode'] = 'NO';
  }
  

}
$redirect = "Location: ".$_SESSION['adminurl'];
header($redirect);  
?>