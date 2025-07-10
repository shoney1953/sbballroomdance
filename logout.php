<?php
  session_start();
  unset($_SESSION['username']);
  unset($_SESSION['role']);
  unset($_SESSION['userid']);
  unset($_SESSION['visitorfirstname']);
  unset($_SESSION['visitorlastname']);
  if (isset($_SESSION['homeurl'])) {
     $redirect = "Location: ".$_SESSION['homeurl'];
       header($redirect);
  }


  exit;   