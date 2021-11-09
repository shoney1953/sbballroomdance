<?php
session_start();

include_once '../config/Database.php';
include_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);
$pass2 = '';
$isValid = false;

   if(isset($_POST['SubmitSignUP'])) {
    $user->firstname = htmlentities($_POST['firstname']);
    $user->lastname = htmlentities($_POST['lastname']);
    $user->email = htmlentities($_POST['email']);
    $user->username = htmlentities($_POST['username']);
    $user->password = htmlentities($_POST['password']);
    $pass2 = htmlentities($_POST['pass2']);
    $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL);   

    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    } else {
        $redirect = "Location: ".$_SESSION['signurl'].'?error=EmailInvalid';
        header($redirect);
        exit;
    }
  
    if (!$user->password == $pass2) {
        $redirect = "Location: ".$_SESSION['signurl'].'?error=PasswordMatch';
        header($redirect);
        exit;
    }


    if ($user->validate_user($user->username)) {
      
      $redirect = "Location: ".$_SESSION['signurl'].'?error=UserExists';
        header($redirect);
        exit;  
    } 
       
    

      if ($user->validate_email($user->email)) {
          
         $redirect = "Location: ".$_SESSION['signurl'].'?error=EmailExists';
        header($redirect);
        exit;  
      }

       $passHash = password_hash($user->password, PASSWORD_DEFAULT);
       $user->password = $passHash;
       $user->firstname = htmlentities($_POST['firstname']);
       $user->lastname = htmlentities($_POST['lastname']);
       $user->email = htmlentities($_POST['email']);
       $user->username = htmlentities($_POST['username']);
       $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL);   
     
       $user->create();

       $redirect = "Location: ".$_SESSION['signurl'].'?success=UserAdded';
       header($redirect);
       exit;  
 

}
    
 


