<?php
session_start();

require_once '../config/Database.php';
require_once '../models/Visitor.php';
date_default_timezone_set("America/Phoenix");
require_once '../models/User.php';
$database = new Database();
$db = $database->connect();
$visitor = new Visitor($db);
$user = new User($db);
$existingUser = 'NO';

$visitorEmail = '';
$visitorFirst = '';
$visitorLast = '';

   if(isset($_POST['SubmitVisitorLogIN'])) {

    $visitorEmail = htmlentities($_POST['email']);
    $visitorFirst = htmlentities($_POST['firstname']);
    $visitorLast = htmlentities($_POST['lastname']); 
    $user->email = filter_var($visitorEmail, FILTER_SANITIZE_EMAIL); 
    $visitor->email = filter_var($visitorEmail, FILTER_SANITIZE_EMAIL); 
    $visitor->firstname = $visitorFirst;
    $visitor->lastname = $visitorLast; 

    if ($user->validate_email($user->email)) { 
        $existingUser = "YES";
   
        echo '<script>alert("You are already a member; Please use Member Login.")</script>';    

    }
    if ($existingUser === 'NO') {
        $visitor->create();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['userid']);
        unset($_SESSION['visitorfirstname']);
        unset($_SESSION['visitorlastname']);
  
        $_SESSION['visitorfirstname'] = $visitor->firstname;
        $_SESSION['visitorlastname'] = $visitor->lastname;
        $_SESSION['username'] = $visitor->email;
        $_SESSION['role'] = "visitor";
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
         exit;   
    }


    } 

    