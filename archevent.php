<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/ClassRegistrationArch.php';
require_once 'models/EventRegistration.php';
require_once 'models/EventRegistrationArch.php';
require_once 'models/UserArchive.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$eventid = 0;
if (isset($_GET['id'])) {
    $eventid = $_GET['id'];
} else {
    $redirect = "Location: ".$_SESSION['adminurl'];
    header($redirect);
}
?>