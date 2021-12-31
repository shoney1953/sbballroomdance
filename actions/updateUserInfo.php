<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} 

$database = new Database();
$db = $database->connect();
$user = new User($db);


if (isset($_POST['submitUpdateUser'])) {

    $user->id = $_POST['id'];
    $user->firstname = $_POST['firstname'];
    $user->lastname = $_POST['lastname'];
    $user->username = $_POST['username'];
    $user->streetAddress = $_POST['streetaddress'];
    $user->city = $_POST['city'];
    $user->state = $_POST['state'];
    $user->zip = $_POST['zip'];
    $user->phone1 = $_POST['phone1'];
    $user->phone2 = $_POST['phone2'];
    $user->hoa = $_POST['hoa'];
    $user->notes = $_POST['notes'];
    $user->partnerId = $_POST['partnerid'];
    
    $user->password = $_POST['password'];

    $user->email = $_POST['email'];
  
    $user->role = $_POST['role'];
    
    if ($_POST['newemail'] != $_POST['email']) {  
        $newemail = $_POST['newemail'];
        if (filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
            $newemail = htmlentities($_POST['newemail']);
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
  
 
    if ($_POST['newuser'] != $_POST['username']) {
        $newuser = $_POST['newuser'];
        $newuser = htmlentities($_POST['newuser']);
        if ($user->validate_user($newuser)) {
      
            $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
              header($redirect);
              exit;  
          } else {
              $user->username = $newuser;
          }

    }
    $pass2 = NULL;
    if (isset($_POST['resetPass'])) {
        if ($_POST['resetPass'] != NULL) {
        $newpass = $_POST['resetPass'];
        if (isset($_POST['resetPass2'])) {
            $pass2 = $_POST['resetPass2'];
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

       $user->firstname = htmlentities($_POST['firstname']);
       $user->lastname = htmlentities($_POST['lastname']);
       $user->streetAddress = htmlentities($_POST['streetaddress']); 
       $user->city = htmlentities($_POST['city']); 
       $user->notes = htmlentities($_POST['notes']);
       
       if ($_POST['hoa'] === '1') {
           $user->hoa = 1;
       } else {
        $user->hoa = 2;
       }
    
      
    $user->update();

$redirect = "Location: ".$_SESSION['profileurl'];
header($redirect);
exit;
}
?>