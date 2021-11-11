<?php
  session_start();
  unset($_SESSION['username']);
  unset($_SESSION['role']);
  unset($_SESSION['userid']);
  
  $redirect = "Location: ".$_SESSION['homeurl'];
  header($redirect);
  exit;   