<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
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
var_dump($_POST);
$database = new Database();
$db = $database->connect();
$user = new User($db);

if (isset($_POST['submitAddUser'])) {

    $user->firstname = $_POST['firstname'];
    $user->lastname = $_POST['lastname'];
    $user->username = $_POST['username'];
    $user->password = $_POST['initPass'];
    $pass2 = $_POST['initPass2'];
    $user->email = $_POST['email'];
    $user->role = $_POST['role'];
    $user->streetAddress = $_POST['streetaddress'];
    $user->city = $_POST['city'];
    $user->state = $_POST['state'];
    $user->zip = $_POST['zip'];
    $user->phone1 = $_POST['phone1'];
    $user->phone2 = $_POST['phone2'];
    $user->hoa = $_POST['hoa'];
    $user->notes = $_POST['notes'];

    if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    } else {
        $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailInvalid';
        header($redirect);
        exit;
    }
  
    if (!$user->password == $pass2) {
        $redirect = "Location: ".$_SESSION['userurl'].'?error=PasswordMatch';
        header($redirect);
        exit;
    }


    if ($user->validate_user($user->username)) {
      
      $redirect = "Location: ".$_SESSION['userurl'].'?error=UserExists';
        header($redirect);
        exit;  
    } 
       
    

      if ($user->validate_email($user->email)) {
          
         $redirect = "Location: ".$_SESSION['userurl'].'?error=EmailExists';
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
       $user->streetAddress = htmlentities($_POST['streetaddress']); 
       $user->city = htmlentities($_POST['city']); 
       $user->state = htmlentities($_POST['state']); 
       $user->zip = htmlentities($_POST['zip']); 
       $user->notes = htmlentities($_POST['notes']); 
       $user->phone1 = htmlentities($_POST['phone1']);
       $user->phone2 = htmlentities($_POST['phone2']);
  

    $user->create();
 
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
    exit;
}
?>