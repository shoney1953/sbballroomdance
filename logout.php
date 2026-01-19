<?php
  session_start();
  $_SESSION = array();
  if (isset($_SESSION['homeurl'])) {
       
    
             $redirect = "Location: ".$_SESSION['homeurl'];
 
        
             header($redirect);
            exit;
  } else {
    if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           
             header($redirect);
            exit;
            
  }


  exit;   