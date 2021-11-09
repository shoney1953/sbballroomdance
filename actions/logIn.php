<?php
session_start();

include_once '../config/Database.php';
include_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);
$pass2 = '';
$isValid = false;

   if(isset($_POST['SubmitLogIN'])) {

    $user->username = htmlentities($_POST['username']);
    $passEntered = htmlentities($_POST['password']);
 
    $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL);   

    if($user->getUserName($user->username)) {

        if(password_verify($passEntered, $user->password )) {
           
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;

            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect);
            exit;   
        }
        else {
           
            if(isset($_SESSION['loggedinuser'])) {
                unset($_SESSION['loggedinuser']);
            }
            $redirect = "Location: ".$_SESSION['loginurl'].'?error=InvalidPassword';
            header($redirect);
            exit;  
        } 
    } else {
        
        if(isset($_SESSION['loggedinuser'])) {
            unset($_SESSION['loggedinuser']);
        }
      $redirect = "Location: ".$_SESSION['signurl'].'?error=NoUser';
        header($redirect);
        exit;  
    } 
}
    
 


