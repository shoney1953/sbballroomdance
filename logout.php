<?php
  session_start();
  unset($_SESSION['username']);
  unset($_SESSION['role']);
  unset($_SESSION['userid']);
  unset($_SESSION['visitorfirstname']);
  unset($_SESSION['visitorlastname']);
  if (isset($_SESSION['homeurl'])) {
 
           if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
  }


  exit;   