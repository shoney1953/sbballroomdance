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
            $_SESSION['userid'] = $user->id;

            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect);
            exit;   
        }
        else {
           
            if(isset($_SESSION['username'])) {
                unset($_SESSION['username']);
            }
            if(isset($_SESSION['role'])) {
                unset($_SESSION['role']);
            }
            if(isset($_SESSION['userid'])) {
                unset($_SESSION['userid']);
            }
            $redirect = "Location: ".$_SESSION['loginurl'].'?error=InvalidPassword';
            header($redirect);
            exit;  
        } 
    } else {
        if(isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
        if(isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        if(isset($_SESSION['userid'])) {
            unset($_SESSION['userid']);
        }
      
      $redirect = "Location: ".$_SESSION['signurl'].'?error=NoUser';
        header($redirect);
        exit;  
    } 
}
    
 

