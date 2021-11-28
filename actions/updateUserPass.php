<?php
session_start();
include_once '../config/Database.php';
include_once '../models/User.php';
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$database = new Database();
$db = $database->connect();
$user = new User($db);
$pass2 = '';
$isValid = false;

   if(isset($_POST['SubmitPassChange'])) {
 
    $oldpassword = htmlentities($_POST['oldpassword']);
    $newpass = htmlentities($_POST['newpassword']);
    $newpass2 = htmlentities($_POST['newpass2']);
    $currentpass = htmlentities($_POST['currentpass']);
    $user->id = $_POST['id'];
  
    if(!password_verify($oldpassword, $currentpass )) {
        $redirect = "Location: ".$_SESSION['profileurl'].'?error=CurrentPassMismatch';
        header($redirect);
        exit;
    }
    if (!$newpass == $newpass2) {
        $redirect = "Location: ".$_SESSION['profileurl'].'?error=NewPasswordMismatch';
        header($redirect);
        exit;
    }
    

       $passHash = password_hash($newpass, PASSWORD_DEFAULT);
       $user->password = $passHash;
    
     
       $user->updatePassword();

       $redirect = "Location: ".$_SESSION['profileurl'].'?success=PasswordChanged';
       header($redirect);
       exit;  
 

}