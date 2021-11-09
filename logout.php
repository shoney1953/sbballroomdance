<?php
  session_start();
  unset($_SESSION['username']);
  unset($_SESSION['role']);
  
  $redirect = "Location: ".$_SESSION['homeurl'];
  header($redirect);
  exit;   