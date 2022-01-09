<?php
session_start();
require_once '../includes/sendEmail.php';
require_once '../config/Database.php';
require_once '../models/Contact.php';
date_default_timezone_set("America/Phoenix");

$database = new Database();
$db = $database->connect();
$contact = new Contact($db);


if (isset($_POST['submit'])) {
    $contact->firstname = htmlentities($_POST['firstname']);
    $contact->lastname = htmlentities($_POST['lastname']);
    $contact->email = htmlentities($_POST['email']);
    $contact->message = htmlentities($_POST['message']);
    $contact->danceExperience = $_POST['danceexperience'];
    $contact->danceFavorite = $_POST['dancefavorite'];
    $contact->email = filter_var($contact->email, FILTER_SANITIZE_EMAIL);  

    $fromEmailName = 'SBDC Ballroom Dance Club';
    $toName = $contact->firstname.' '.$contact->lastname; 
    $mailSubject = 'Thanks for Contacting us at SBDC Ballroom Dance Club!';
    $replyTopic = "Information";
    $replyEmail = 'sbbdcschedule@gmail.com';
    $actLink = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
    Click to view Activities Calendar</a><br>";
    $mailAttachment = '../img/Membership Form 2022 Dance Club.pdf'; 
    $fromCC = 'sbbdcschedule@gmail.com';
    $emailBody = "<br>We would love to have <b>you, $toName </b>, as a new member to our club.<br>
    Please see attached membership form if you are interested.<br>".
    $actLink.
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
            $mailAttachment
        );
        $contact->create();
        $redirect = "Location: ".$_SESSION['homeurl']."?success='Contact Message Sent'";
        header($redirect);
        exit; 
        }
    } else {
        echo "Email is missing or Invalid .. Please enter Valid email";
    }

  
 




?>