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
    $visitorNotes = htmlentities($_POST['notes']); 
    $user->email = filter_var($visitorEmail, FILTER_SANITIZE_EMAIL); 
    $visitor->email = filter_var($visitorEmail, FILTER_SANITIZE_EMAIL); 
    $visitor->firstname = $visitorFirst;
    $visitor->lastname = $visitorLast; 
    $visitor->notes = $visitorNotes;

    if ($user->validate_email($user->email)) { 
        $existingUser = "YES";
       echo '<script>alert("You are already a member; Please use Member Login.")</script>';    
    }
 
    if ($existingUser === 'NO') {
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['userid']);
        unset($_SESSION['useremail']);
        unset($_SESSION['username']);
        unset($_SESSION['visitorfirstname']);
        unset($_SESSION['visitorlastname']);
        unset($_SESSION['userfirstname']);
        unset($_SESSION['userlastname']);
        unset($_SESSION['partnerid']);
  
        $_SESSION['visitorfirstname'] = $visitor->firstname;
        $_SESSION['visitorlastname'] = $visitor->lastname;
        $_SESSION['username'] = $visitor->email;
        $_SESSION['useremail'] = $visitor->email;
        $_SESSION['role'] = "visitor";
        if ($visitor->read_ByEmail($visitorEmail)) {
          $visitor->update($visitorEmail);
        } else {
           $visitor->create();
            unset($_SESSION['username']);
        }
      
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
         exit;   
    }


    } 

    