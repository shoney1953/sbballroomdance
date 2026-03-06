<?php
session_start();
require_once '../config/Database.php';
require_once '../models/DinnerMealChoices.php';
require_once '../models/EventRegistration.php';
$allEvents = $_SESSION['upcoming_events'];
$eventRegistrations = [];
$database = new Database();
$db = $database->connect();
$eventReg = new EventRegistration($db);

if (isset($_POST['eventid'])) {
  $eventid = $_POST['eventid'];
 if ((isset($_POST['regdate'])) && ($_POST['regdate'] === '1') ) {

$result = $eventReg->read_ByEventIdRegDate($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByRegDate'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    } // while
} // rowcount
$_SESSION['eventRegByRegDate']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=RegDate&id=".$eventid;

    header($redirect);
    exit;


 } // regdate
   

    if ((isset($_POST['paid'])) && ($_POST['paid'] === '1') ) {

$result = $eventReg->read_ByEventIdPaid($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByPaid'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    } // while
} // rowcount

$_SESSION['eventRegByPaid']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=paid&id=".$eventid;

    header($redirect);
    exit;


 } // paid
   if ((isset($_POST['meal'])) && ($_POST['meal'] === '1') ) {

$result = $eventReg->read_ByEventIdMeal($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByMeal'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    } // while
} // rowcount

$_SESSION['eventRegByMeal']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=meal&id=".$eventid;

    header($redirect);
    exit;


 } // meal

   if ((isset($_POST['moddate'])) && ($_POST['moddate'] === '1') ) {

$result = $eventReg->read_ByEventIdModDate($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByModDate'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    } // while
} // rowcount

$_SESSION['eventRegByModDate']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=moddate&id=".$eventid;

    header($redirect);
    exit;


 } // moddate

   if ((isset($_POST['attenddinner'])) && ($_POST['attenddinner'] === '1') ) {
$result = $eventReg->read_ByEventIdAttendDinner($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByAttendDinner'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegByAttendDinner']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=attenddinner&id=".$eventid;
header($redirect);
exit;
 } // attenddinner
   if ((isset($_POST['firstname'])) && ($_POST['firstname'] === '1') ) {
$result = $eventReg->read_ByEventIdFirstName($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByFirstName'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegByFirstName']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=firstname&id=".$eventid;
header($redirect);
exit;
 } // firstname
   if ((isset($_POST['lastname'])) && ($_POST['lastname'] === '1') ) {
$result = $eventReg->read_ByEventIdLastName($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByFirstName'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegByLastName']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=lastname&id=".$eventid;
header($redirect);
exit;
 } // lastname

    if ((isset($_POST['email'])) && ($_POST['email'] === '1') ) {
$result = $eventReg->read_ByEventIdEmail($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByEmail'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegByEmail']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=email&id=".$eventid;
header($redirect);
exit;
 } // email

     if ((isset($_POST['cornhole'])) && ($_POST['cornhole'] === '1') ) {
$result = $eventReg->read_ByEventIdCornHole($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegByCornHole'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegByCornHole']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=cornhole&id=".$eventid;
header($redirect);
exit;
 } // cornhole
if ((isset($_POST['softball'])) && ($_POST['softball'] === '1') ) {
$result = $eventReg->read_ByEventIdSoftBall($_POST['eventid']);
$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventRegBySoftBall'] = [];
if ($rowCount > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'eventid' => $eventid,
            'eventname' => $eventname,
            'eventdate' => $eventdate,
            'eventtype' => $eventtype,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
              'paidonline' => $paidonline,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'mealchoice' => $mealchoice,
            'mealname' => $mealname,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
            'dwop' => $dwop,
            'guest' => $guest,
            'numhotdogs' => $numhotdogs,
            'numhdbuns' => $numhdbuns,
            'numhamburgers' => $numhamburgers,
            'numhbbuns' => $numhbbuns,
            'vegetarian' => $vegetarian,
            'cornhole' => $cornhole,
            'softball' => $softball,
            'dateregistered' => date('m d Y', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
    } // while
} // rowcount
$_SESSION['eventRegBySoftBall']  = $eventRegistrations;
$redirect = "Location: ".$_SESSION['eventmemurl']."?sort=softball&id=".$eventid;
header($redirect);
exit;
 } // email
    } // eventid

  

?>