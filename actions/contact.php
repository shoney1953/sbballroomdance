<?php

require_once '../includes/sendEmail.php';
require_once '../includes/siteemails.php';
require_once '../config/Database.php';
require_once '../models/Contact.php';
date_default_timezone_set("America/Phoenix");
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$contact = new Contact($db);
$user = new User($db);
$existingUser = 'NO';


if (isset($_POST['submit'])) {
    $contact->firstname = htmlentities($_POST['firstname']);
    $contact->lastname = htmlentities($_POST['lastname']);
    $contact->email = htmlentities($_POST['email']);
    $user->email = htmlentities($_POST['email']);
    if ($user->validate_email($user->email)) { 
        $existingUser = "YES";
    }

    $contact->message = htmlentities($_POST['message']);
    $contact->danceExperience = $_POST['danceexperience'];
    $contact->danceFavorite = $_POST['dancefavorite'];
    $contact->email = filter_var($contact->email, FILTER_SANITIZE_EMAIL);  
    $contact->create();
    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toCC2 = $webmaster;
    $toCC3 = '';
    $toCC4 = '';
    $toCC5 = '';
    $toName = $contact->firstname.' '.$contact->lastname; 
    $replyEmail = $webmaster;
    $actLink = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
    Click to view Activities Calendar</a><br>";
    if ($existingUser === 'NO') {
        $mailSubject = 'Thanks for Contacting us at SBDC Ballroom Dance Club!';
        $replyTopic = "Club Information"; 
    } else {
        $mailSubject = 'We have gotten your message!';
        $replyTopic = "Message from Member"; 
    }

    $replyEmail = $webmaster;
    $actLink = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
    Click to view Activities Calendar</a><br>";

    if ($existingUser === 'NO') {
        $mailAttachment = ''; 
        $emailBody = "<br>We would love to have <b>you, $toName </b>, as a new member to our club.<br>
        Please see attached membership form if you are interested in joining.<br>";
    } else {
        $mailAttachment = ''; 
        $emailBody = "<br>$toName </b>, thanks for being a member of our club<br>
        We'll try to get back to you to answer your concern as soon as possible.<br>
        If you need website help please refer to help section 
        of the website or email ".$webmaster."<br>
        The PDF with an introduction to the website is also attached.<br>
        The link to the activites calendar is provided below for your 
        convenience.<br>";
    }
    $emailBody .= "<br> <b> Message:</b><br>$contact->message<br>";
    $emailBody .= "<br>$actLink";
    $fromCC = $webmaster;
  
    "<br><br>Thanks!
    <br>SBDC Ballroom Dance Club";

    if (filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
        sendEmail(
            $contact->email, 
            $toName, 
            $fromCC,
            $fromEmailName,
            $emailBody,
            $mailSubject,
            $replyEmail,
            $replyTopic,
            $mailAttachment,
            $toCC2,
            $toCC3,
            $toCC4,
            $toCC5
        );
    
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
    } else {
        echo "Email is missing or Invalid .. Please enter Valid email";
    }

  
 




?>