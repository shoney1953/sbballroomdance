<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");
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
$users = $_SESSION['process_users'];
$database = new Database();
$db = $database->connect();
$user = new User($db);
var_dump($_POST);
if (isset($_POST['submitUpdateUser'])) {
    foreach ($users as $usr) {
      $upChk = "up".$usr['id'];
      $usrSelChk ="userSel".$usr['id'];
      $fnamID = "fnam".$usr['id'];
      $lnamID = "lnam".$usr['id'];
      $nemailID = "nemail".$usr['id'];
      $nuserID = "nuser".$usr['id'];
      $dlistID = "dlist".$usr['id'];
      $hoaID = "hoa".$usr['id'];
      $phone1ID = "phone1".$usr['id'];
      $phone2ID = "phone2".$usr['id'];
      $staddID = "stadd".$usr['id'];
      $cityID = "city".$usr['id'];
      $stateID = "state".$usr['id'];
      $zipID = "zip".$usr['id'];
      $notesID = "notes".$usr['id'];
      $partID = "part".$usr['id'];
      $emailID = "email".$usr['id'];
      $userID = "user".$usr['id'];
      $idID = "id".$usr['id'];
      $pwdID  = "pwd".$usr['id'];
      $roleID = "role".$usr['id'];
      $rpwdID = "rpwd".$usr['id'];
      $rpwd2ID = "rpwd2".$usr['id'];
 
   if (isset($_POST["$usrSelChk"])) {
  
    $user->id = $_POST["$idID"];
    $user->firstname = $_POST["$fnamID"];
    $user->lastname = $_POST["$lnamID"];
    $user->username = $_POST["$userID"];
    $user->streetAddress = $_POST["$staddID"];
    $user->city = $_POST["$cityID"];
    $user->state = $_POST["$stateID"];
    $user->zip = $_POST["$zipID"];
    $user->phone1 = $_POST["$phone1ID"];
    $user->phone2 = $_POST["$phone2ID"];
    $user->hoa = $_POST["$hoaID"];
    $user->notes = $_POST["$notesID"];
    $user->partnerId = $_POST["$partID"];
    
    $user->password = $_POST["$pwdID"];

    $user->email = $_POST["$emailID"];
    $user->directorylist = $_POST["$dlistID"];
  
    $user->role = $_POST["$roleID"];
    
    if ($_POST["$nemailID"] != $_POST["$emailID"]) {  
        $newemail = $_POST["$nemailID"];
        if (filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
            $newemail = htmlentities($_POST["$nemailID"]);
            $newemail = filter_var($newemail, FILTER_SANITIZE_EMAIL);
        } else {
            $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailInvalid';
            header($redirect);
            exit;
        }
        if ($user->validate_email($newemail)) {
            
            $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailExists';
        header($redirect);
        exit;  
        } else {
            $user->email = $newemail;
        }
        
    }   
  
 
    if ($_POST["$nuserID"] != $_POST["$userID"]) {
        $newuser = $_POST["$nuserID"];
        $newuser = htmlentities($_POST["$nuserID"]);
        if ($user->validate_user($newuser)) {
      
            $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
              header($redirect);
              exit;  
          } else {
              $user->username = $newuser;
          }

    }
    $pass2 = NULL;
    if (isset($_POST["$rpwdID"])) {
        if ($_POST["$rpwdID"] != NULL) {
        $newpass = $_POST["$rpwdID"];
        if (isset($_POST["$rpwd2ID"])) {
            $pass2 = $_POST["$rpwd2ID"];
        }
    
        if (!$newpass == $pass2) {
            $redirect = "Location: ".$_SESSION['userurl'].'?error=PasswordMatch';
            header($redirect);
            exit;
        } else {
            $newHash = password_hash($newpass, PASSWORD_DEFAULT);
            if ($newHash != $user->password) {
                $user->password = $newHash;
                $user->updatePassword();
            }
        }
    }

    }

       $user->firstname = htmlentities($_POST["$fnamID"]);
       $user->lastname = htmlentities($_POST["$lnamID"]);
       $user->streetAddress = htmlentities($_POST["$staddID"]); 
       $user->city = htmlentities($_POST["$cityID"]); 
       $user->notes = htmlentities($_POST["$notesID"]); 
      
    $user->update();

}
   
}
}
unset($_SESSION['process_users']);
// $redirect = "Location: ".$_SESSION['adminurl']."#users";
// header($redirect);
// exit;
?>