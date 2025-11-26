<?php
session_start();

require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/PwdReset.php';
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$user = new User($db);
$pwdReset = new PwdReset($db);
if (isset($_POST['SubmitCreatePwd'])) {
  $selector = $_POST['selector'];
  $validator = $_POST['validator'];
  $pwd = $_POST['pwd'];
  $pwd2 = $_POST['pwd2'];
  if (empty($pwd) || empty($pwd2)) {
    $redirect = "Location: ../createNewPassword.php?error=newpwempty&selector=".$selector."&validator=".$validator."";;
    header($redirect); 
    exit();
  } elseif ($pwd != $pwd2) {
    $redirect = "Location: ../createNewPassword.php?error=pwdnomatch&selector=".$selector."&validator=".$validator."";;
    header($redirect); 
    exit();
  }
  $currentDate = new DateTime();
  $expirationDate = $currentDate->format('U');

  if ($pwdReset->readBy_selector($selector, $expirationDate)) {

      $tokenBin = hex2bin($validator);
      $tokenCheck = password_verify($tokenBin, $pwdReset->pwdResetToken);
      if ($tokenCheck === true) {
        if ($user->getUserName($pwdReset->pwdResetEmail)) {
          $passHash = password_hash($pwd, PASSWORD_DEFAULT);
          $user->password = $passHash;
          $user->updatePassword();
          $pwdReset->deleteByEmail($pwdReset->pwdResetEmail);
          $redirect = "Location: ../login.php";
        header($redirect); 
        exit();
       }
      }
      if ($tokenCheck === false) {
        echo '<p>Internal Error Occured; please start over</p>';
      }
    } else {

      $redirect = "Location: ../createNewPassword.php?error=timeout&selector=".$selector."&validator=".$validator."";;
      header($redirect); 
      exit();

    }

  }  else {
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
?>