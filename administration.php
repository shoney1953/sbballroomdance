<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Contact.php';
require_once 'models/Visitor.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];

$allClasses = [];
$visitors = [];
$allEvents = [];
$contacts = [];
$users = [];
unset($_SESSION['process_users']);
$classRegistrations = [];
$eventRegistrations = [];
$num_registrations = 0;
$num_events = 0;
$num_classes = 0;
$num_visitors = 0;
$memberStatus1 = [];
$memberStatus2 = [];
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 
$current_month = date('m');
$current_year = date('Y');
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');

$rpChk = '';
$upChk = '';
$dlChk = '';
$emChk = '';
$dpChk = '';
$arChk = '';

$database = new Database();
$db = $database->connect();
// refresh events

if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
 
$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;
$_SESSION['allEvents'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventregend' => $eventregend,
            'eventcost' => $eventcost,
            'eventform' => $eventform,
            'orgemail' => $orgemail,
            'eventdj' => $eventdj,
            "eventdesc" => html_entity_decode($eventdesc),
            "eventroom" => $eventroom,
            'eventnumregistered' => $eventnumregistered
        );
        array_push($allEvents, $event_item);
    
    }
  
    $_SESSION['allEvents'] = $allEvents;
} 
/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();
$num_classes = $rowCount;
$_SESSION['allClasses'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $class_item = array(
            'id' => $id,
            'classname' => $classname,
            'classlevel' => $classlevel,
            'classlimit' => $classlimit,
            'date' => $date,
            'time' => date('h:i:s A', strtotime($time)),
            'time2' => $time,
            'instructors' => $instructors,
            'classnotes' => $classnotes,
            "registrationemail" => $registrationemail,
            "room" => $room,
            'numregistered' => $numregistered
        );
        array_push($allClasses, $class_item);

    }
    $_SESSION['allClasses'] = $allClasses;
} 
/* get class registrations */
$classReg = new ClassRegistration($db);
$result = $classReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['classregistrations'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'classid' => $classid,
            'classname' => $classname,
            'classdate' => $classdate,
            'classtime' => date('h:i:s A', strtotime($classtime)),
            'userid' => $userid,
            'email' => $email,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
      
        array_push($classRegistrations, $reg_item);
  
    }
  
    $_SESSION['classregistrations'] = $classRegistrations;
} 
/* get event registrations */
$eventReg = new EventRegistration($db);
$result = $eventReg->read();

$rowCount = $result->rowCount();
$num_registrations = $rowCount;
$_SESSION['eventregistrations'] = [];
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
            'orgemail' => $orgemail,
            'message' => $message,
            'userid' => $userid,
            'email' => $email,
            'paid' => $paid,
            'ddattenddance' => $ddattenddance,
            'ddattenddinner' => $ddattenddinner,
            'registeredby' => $registeredby,
            'dateregistered' => date('m d Y h:i:s A', strtotime($dateregistered))
        );
        array_push($eventRegistrations, $reg_item);
  
    }
  
    $_SESSION['eventregistrations'] = $eventRegistrations;
} 
/* get contacts */
$contact = new Contact($db);
$result = $contact->read();

$rowCount = $result->rowCount();
$num_contacts = $rowCount;
if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $contact_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'message' => $message,
            'email' => $email,
            'danceFavorite' => $danceFavorite,
            'danceExperience' => $danceExperience,
            "contactdate" => date('m d Y h:i:s A', 
            strtotime($contactdate))
           
        );
        array_push($contacts, $contact_item);
  
    }
  $_SESSION['contacts'] = $contacts;

} 
$visitor = new Visitor($db);
$result = $visitor->read();

$rowCount = $result->rowCount();
$num_visitors = $rowCount;
if($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    
        extract($row);
        $visitor_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'message' => $message,
            'email' => $email,
            "logindate" => date('m d Y h:i:s A', 
            strtotime($logindate)),
            "datelogin" => $logindate,
            'notes' => $notes,
            'numlogins' => $numlogins
           
        );
        array_push($visitors, $visitor_item);
  
    }
  $_SESSION['visitors'] = $visitors;

}
//*********************** superadmin  */
$num_users = 0;


if ($_SESSION['role'] === 'SUPERADMIN') {
    $user = new User($db);
    $result = $user->read();
    
    $rowCount = $result->rowCount();
    $num_users = $rowCount;
    $_SESSION['members'] = [];
    if($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'role' => $role,
                'email' => $email,
                'phone1' => $phone1,
                'phone2' => $phone2,
                'password' => $password,
                'partnerId' => $partnerid,
                'hoa' => $hoa,
                'passwordChanged' => $passwordChanged,
                'streetAddress' => $streetaddress,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'notes' => $notes,
                'lastLogin' => date('m d Y h:i:s A', strtotime($lastLogin)),
                'numlogins' => $numlogins,
                'directorylist' => $directorylist,
                'fulltime' => $fulltime,
                'robodjnumlogins' => $robodjnumlogins,
                'robodjlastlogin' => $robodjlastlogin
            );
            array_push($users, $user_item);
      
        }
   
        $_SESSION['members'] = $users;
        $_SESSION['process_users'] = $users;
    } 
    $memPaid = new MemberPaid($db);
    $result = $memPaid->read_byYear($nextYear);
    
    $rowCount = $result->rowCount();
    $num_memPaid = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $member_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'userid' => $userid,
                'year' => $year,
                'email' => $email,
                'paid' => $paid

            );
            array_push($memberStatus2, $member_item);
      
        }
     $_SESSION['memberStatus2'] = $memberStatus2;
    
    } 
    $result = $memPaid->read_byYear($thisYear);
    
    $rowCount = $result->rowCount();
    $num_memPaid = $rowCount;
    if ($rowCount > 0) {
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $member_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'userid' => $userid,
                'email' => $email,
                'year' => $year,
                'paid' => $paid
            );
            array_push($memberStatus1, $member_item);
      
        }
     $_SESSION['memberStatus1'] = $memberStatus1;
    
    } 
} // end of superadmin check


    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container">
  
     <ul> 
        <?php
    if (($_SESSION['role'] === 'ADMIN') ||
        ($_SESSION['role'] === 'SUPERADMIN')) 
       {
        echo '<li><a href="index.php">Back to Home</a></li>';
        echo '<li><a href="#events">Maintain Events</a></li>';
        echo '<li><a href="#classes">Maintain Classes</a></li>';
     

        echo '</ul>';
        echo '</div>';
        echo '<div class="container">';
  
        echo '<ul>';
        // echo '<li><a href="#contacts">Contacts</a></li>';
        echo '<li><a href="#visitors">Visitors</a></li>';
        echo '<li><a href="https://drive.google.com/drive/folders/1PQSs3_gNDuSfQ2L24Gw0Fnsoe9_vQlQ-?usp=sharing">
             Booking Reports</a></li>';

     }
     if ($_SESSION['role'] === 'INSTRUCTOR') {
        echo '<li><a href="index.php">Back to Home</a></li>';
        echo '<li><a href="#classes">Maintain Classes</a></li>';
        echo '</ul>';
        echo '</div>';
     }
        
        if ($_SESSION['role'] === 'SUPERADMIN') {

            echo '<li><a href="#users">Maintain Members</a></li>';
            echo '<li><a href="#membership">Membership</a></li>';
            echo '<li><a href="archives.php">Archives</a></li>';
            echo '</ul>';
            echo '</div>';
        }
        ?>
    </ul>
     </div>
</nav>
    <div class="container-section" >
    <br>
    <br>
    <br>
    <br>
    <br>
    <h1 style="text-align: center; margin-top: 40px; color:white">Administrative Functions for SaddleBrooke Ballroom Dance Club</h1>
    <br>
    </div>

<?php
    echo '<div class="container-section ">';
    echo '<section  class="content">';
    echo '<br>';
    echo '<h4><a href="https://drive.google.com/file/d/1R7kQMmGTbadaz_0ekPAIQXOrYvo0Mut8/view?usp=sharing">Click for Administrators Guide</a></h4>';
    echo '<br>';
    echo '</div>';
    echo '</div>';

    if ($_SESSION['role'] != 'INSTRUCTOR') {

    echo '<div class="container-section ">';
    echo '<br>';
    echo '<section id="events" class="content">';
    echo '<form method="POST" action="actions/processEvents.php">';

       echo '<h3 class="form-title">Maintain Events</h3>';
  
       foreach($allEvents as $event) {
        $rpChk = "rp".$event['id'];
        $upChk = "up".$event['id'];
        $dlChk = "dl".$event['id'];
        $emChk = "em".$event['id'];
        $aeChk = "ae".$event['id'];
        $dpChk = "dp".$event['id'];
        $arChk = "ar".$event['id'];
        $drChk = "dr".$event['id'];
        $urChk = "ur".$event['id'];
 
        $mbSrch = "srch".$event['id'];
        $class_month = substr($event['eventdate'], 5, 2);
        $class_year = substr($event['eventdate'], 0, 4);
        $showReg = 0;
        echo '<div class="form-container">';
        echo '<div class="form-grid">';

       echo '<div class="form-item">';
       echo "<h4 class='form-title'>Name: ".$event['eventname']." </h4>";
       echo '</div>';
       echo '<div class="form-item">';
       echo "<h4 class='form-title'>Type: ".$event['eventtype']."</h4>";
       echo '</div>';
       echo '<div class="form-item">';
       echo "<h4 class='form-title'>Date: ".$event['eventdate']."</h4>";
       echo '</div>';
       echo '<div class="form-item">';
       echo "<h4 class='form-title'>Cost: ".$event['eventcost']."</h4>";
       echo '</div>';
       echo '<div class="form-item">';
       echo "<h4 class='form-title'>ID: ".$event['id']."</h4>";
       echo '</div>';
       echo '<div class="form-item">';
       $hr = 'eventMem.php?id=';
       $hr .= $event["id"];
       echo "<h4 class='form-title'>Number Registered: 
            <a href='".$hr."'>".$event['eventnumregistered']."</a></h4>";
       echo '</div>';
  
       echo '</div>';
    //    echo '<hr>';
       echo '<h4 class="form-title form-division">Event Level Actions</h4>';
       echo '<div class="form-grid">';

        echo '<div class="form-item">';
        echo '<h4 class="form-item-title">Report?</h4>';
        echo "<input type='checkbox' title='Only select 1 event for Report' name='".$rpChk."'>";
        echo '</div>';

       echo '<div class="form-item">';
       echo '<h4 class="form-item-title">Email?</h4>';
       echo "<input type='checkbox' title='Only select 1 event for Email' name='".$emChk."'>";
       echo '</div>';

       echo '<div class="form-item">';
       echo '<h4 class="form-item-title">Duplicate?</h4>';
       echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
       echo '</div>';

       echo '<div class="form-item">';
       echo '<h4 class="form-item-title">Update?</h4>';
       echo "<input type='checkbox' title='Select to Update Event(s)' name='".$upChk."'>";   
       echo '</div>';

       echo '<div class="form-item">';
       echo '<h4 class="form-item-title">Delete?</h4>';
       echo "<input type='checkbox' title='Select to Delete Event(s)' name='".$dlChk."'>";
       echo '</div>';

       if ($_SESSION['role'] === 'SUPERADMIN') {
        echo '<div class="form-item">';
       echo '<h4 class="form-item-title">Archive?</h4>';
       echo "<input type='checkbox' title='Select to Archive Events' name='".$aeChk."'>";
       echo '</div>';
       }
    
        echo '</div>';  
        if ($compareDate <= $event['eventdate']) {
        echo '<h4 class="form-title form-division">Event Registration Actions</h4>';
     
        echo '<div class="form-grid">';
         echo '<div class="form-item">';
         echo '<h4 class="form-item-title">Add Registrations</h4>';
              echo "<input type='checkbox' 
              title='Only select 1 event to Add Registrations' name='".$arChk."'>";
         echo '</div>';
              echo '<div class="form-item">';
        echo '<h4 class="form-item-title">Update Registrations</h4>';
        echo "<input type='checkbox' 
                 title='Only select 1 event to Update Registrations' name='".$urChk."'>";
        echo '</div>';
           echo '<div class="form-item">';
        echo '<h4 class="form-item-title">Delete Registrations?</h4>';
        echo "<input type='checkbox' 
              title='Only select 1 event to Delete Registrations' name='".$drChk."'>";
        echo '</div>';
            echo '<div class="form-item">';
        echo '<h4 class="form-item-title">Enter Search Criteria to limit Registrations</h4>';
        echo "<input type='text'  
              title='Enter Partial or Full Name to qualify Registrations' name='".$mbSrch."' >"; 
        echo '</div>';
        echo '<div class="form-item">';
        echo '<button type="submit" name="submitEventProcess">Process This Event</button>'; 
    
        echo '</div>';
        
         echo '</div>'; 
        } else {
   
            echo '<h4 class="form-title">Registration Options Not Available for Past Events</h4>';
            echo '<button type="submit" name="submitEventProcess">Process This Event</button>';
  
        }
     
       echo '</div>';
       }
       echo '<button type="submit" name="submitEventProcess">Process Events</button>'; 
    
    }
   
    echo '</form>';
       echo '<br>';
        echo '</div>';

        echo '</section>';
    echo '</div>';
   
   ?>
 
    <div class="container-section ">
    
    <section id="classes" class="content">
   <?php
   
           
            echo '<h3 class="form-title">Maintain Classes</h3>';
            echo '<h4> Please check only 1 action per class.</h4>';
            echo '<form method="POST" action="actions/processClasses.php">';
      
            foreach($allClasses as $class) {
                $rpChk = "rp".$class['id'];
                $cvChk = "cv".$class['id'];
                $upChk = "up".$class['id'];
                $dlChk = "dl".$class['id'];
                $emChk = "em".$class['id'];
                $aeChk = "ae".$class['id'];
                $dpChk = "dp".$class['id'];
                $arChk = "ar".$class['id'];
                $drChk = "dr".$class['id'];
                $urChk = "ur".$class['id'];
         
                $mbSrch = "srch".$class['id'];
                $class_month = substr($class['date'], 5, 2);
                $class_year = substr($class['date'], 0, 4);
                $showReg = 0;
                echo "<div class='form-container'>";
                echo "<div class='form-grid'>";
                echo "<h4 class='form-title'>Class Name: ".$class['classname']."</h4>";
                echo "<h4 class='form-title'>Class Level: ".$class['classlevel']."</h4>";
                echo "<h4 class='form-title'>Class Date: ".$class['date']."</h4>";
                $hr = 'classMem.php?id=';
                $hr .= $class["id"];
              
                echo "<h4 class='form-title'>
                       Number Registered: <a href='".$hr."'>".$class['numregistered']."</a></h4>";
                
                echo "</div>"; // end of form grid
                // echo '<hr>';
                echo '<h4 class="form-title form-division">Class Level Actions</h4>';
                
                echo "<div class='form-grid'>";

                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Report?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Report' name='".$rpChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Create CSV file?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Report' name='".$cvChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Email?</h4>";
                echo "<input type='checkbox' title='Only select 1 event for Email' name='".$emChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Duplicate?</h4>";
                echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Update?</h4>";
                echo "<input type='checkbox' title='Select to Update Classes(s)' name='".$upChk."'>";  
                echo "</div>";
                echo "<div class='form-item'>";
                echo "<h4 class='form-item-title'>Delete?</h4>";
                echo "<input type='checkbox' title='Select to Delete Classes(s)' name='".$dlChk."'>";
                echo "</div>";
                if ($_SESSION['role'] === 'SUPERADMIN') {
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Archive?</h4>";
                    echo "<input type='checkbox' title='Select to Archive Classes' name='".$aeChk."'>";
                    echo "</div>";
                }
                echo "</div>"; // end of form grid
          
                $class_month = substr($class['date'], 5, 2);
                $class_year = substr($class['date'], 0, 4);
                // if ($compareDate <= $class['date']) {
                if (($current_month <= $class_month ) || ($current_year < $class_year)){
                    echo '<h4 class="form-title form-division">Class Registration Actions</h4>';
                    echo "<div class='form-grid'>";
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Add Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Add Member Registrations' name='".$arChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Update Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Update Registrations' name='".$urChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Delete Registrations?</h4>";
                    echo "<input type='checkbox' 
                          title='Only select 1 event to Delete Registrations' name='".$drChk."'>";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo "<h4 class='form-item-title'>Search Criteria to Qualify Registrations?</h4>";
                    echo "<input type='text'  
                          title='Enter Partial or Full Name to qualify Registrations' name='".$mbSrch."' >";
                    echo '</div>';
                    echo "<div class='form-item'>";
                    echo '<button type="submit" name="submitClassProcess">Process This Class</button>'; 
                    echo '</div>';
                    // echo "</div>"; // end of form grid
                    echo '</div>';
                    
                } else {
   
                    echo "<h4 class='form-title'>Registration Options not Available for Past Class</h4>";
                    echo '<button type="submit" name="submitClassProcess">Process This Class</button>'; 
       
                }


            
        
                echo "</div>"; // end of form container
            }
            echo '<button type="submit" name="submitClassProcess">Process Classes</button><br><br>'; 
            echo '</form>';
            
  
    
            echo '</section>';
        echo '</div>';
       

    ?>


    </section>
    </div>

  
<?php

 
    if ($_SESSION['role'] === 'SUPERADMIN') {
        echo "<div class='container-section' name='users'>  <br><br>";
        echo '<section id="users"  class="content">';
        echo ' <h3 class="section-header">Maintain Members</h3> ';
        echo '<div class="form-grid2">';
            echo "<div class='form-grid-div'>";
            echo "<form method='POST' action='actions/maintainUser.php'>"; 
            echo "<button type='submit' name='submitAddUser'>Add a New Member</button>";   
            echo '</form>'    ;   
            echo '</div> ';   
            echo '</div> ';  
            echo '<div class="form-grid4">';
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportUser.php">'; 
            echo '<button type="submit" name="submitUserRep">Report Members</button>';   
            echo '</form>';
            echo '</div> '; 
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportUserHoa.php">'; 
            echo '<button type="submit" name="submitHOAreport">Report Members by HOA</button>';   
            echo '</form>';
            echo '</div> '; 
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/emailMembers.php">'; 
            echo '<button type="submit" name="submitEmailMembers">Email Members</button>';   
            echo '</form>';
            echo '</div> '; 
            /* */
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportUserByMonth.php">'; 
            echo '<button type="submit" name="submitUserRep">Report Members By Create Date</button>';   
            echo '</form>';
            echo '</div> '; 
            /* */
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportInstructors.php">'; 
            echo '<button type="submit" name="submitInstructorRep">Report Instructors</button>';   
            echo '</form>';
            echo '</div> '; 
            /* */
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportUsage.php">'; 
            echo '<button type="submit" name="submitUsageRep">Report Member Usage</button>';   
            echo '</form>'    ;          
            echo '</div> ';   
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportMemActivity.php">'; 
            echo '<button type="submit" name="submitActivityRep">Report Members W/O Activity</button>';   
            echo '</form>'    ;          
            echo '</div> ';   
          
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/membersCsv.php">'; 
            echo '<button type="submit" name="submitCreateCsv">Create CSV file of members</button>';   
            echo '</form>'    ;          
            echo '</div> ';   
            echo '</div>';
        /* */
        echo '<div class="form-grid3">';
        echo '<form  method="POST" action="actions/searchUser.php" >';
        echo '<div class="form-grid-div">';
        echo '<button type="submit" name="searchUser">Search Criteria to Qualify Members for Maintenance</button>'; 
        echo '<input type="text" title="Enter Full or Partial Name or Email to Search." name="search" >';
        echo '</div>';
        echo '</div>';
        echo '</form>';
     
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan="14" style="color: darkviolet;text-align:center">Member List</th>';
        echo '</tr>';
        echo '<tr>';
                echo '<th>Update</th>';
                echo '<th>Archive</th>';
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name</th>';
                echo '<th>Role</th>'; 
                echo '<th>Part ID</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                echo '<th>HOA</th>';
                echo '<th>Address</th>';
                echo '<th>Directory</th>';
                echo '<th>Fulltime?</th>';
           
  
                echo '</tr>';
                echo '<form method="POST" action="actions/processUsers.php">';
            echo '</thead>' ;
            echo '<tbody>'  ; 
        
                foreach($users as $user) {
                    $upChk = "up".$user['id'];
                    $arChk = "ar".$user['id'];

                    $hr = 'member.php?id=';
                    $hr .= $user["id"];
                    echo "<td><input type='checkbox' name='".$upChk."'>";
                    echo "<td><input type='checkbox' name='".$arChk."'>";
                    echo '<td> <a href="'.$hr.'">'.$user["id"].'</a></td>';
     
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['partnerId']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['hoa']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
                        if ($user['directorylist']) {
                            echo "<td>Yes</td>"; 
                        } else {
                            echo "<td>No</td>"; 
                        }
                        if ($user['fulltime']) {
                            echo "<td>Yes</td>"; 
                        } else {
                            echo "<td>No</td>"; 
                        }
                        // echo "<td>".$user['lastLogin']."</td>"; 
                        // echo "<td>".$user['passwordChanged']."</td>"; 
                       
                        
                      echo "</tr>";
                  }
              
              echo '</tbody>'  ;
            echo '</table><br>';       
            echo '<button type="submit" name="submitUserProcess">Process Users</button>';  
            echo '</form>';
            echo '</section>';
      

        echo '<section id="membership" class="content">';
        echo ' <h3 class="section-header">Membership Maintenance</h3> ';
        echo '<div class="form-grid-div">';  
        echo '<form target="_blank" method="POST" action="actions/reportPaid.php">'; 
        echo '<h4>Report Membership</h4>';
        echo '<input type="checkbox" name="reportPaid">';
        echo '<label for="reportUsers">Report Membership</label><br>';    
        echo '<label for="year" >Reporting Year</label><br>';
        echo '<input type="number" min=2022 maxlength=4 name="year" 
             value="'.$thisYear.'"><br>';
             echo '<button type="submit" name="submitPaidRep">Report</button>';   
             echo '</div> ';  
             echo '</form>';
        echo '<div class="form-grid3">';
        echo '<div class="form-grid-div">';  
        echo '<form method="POST" action="actions/updateMemberPaid.php">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
    
                echo '<th>Year</th>'; 
                echo '<th>ID</th>'; 
                echo '<th>Userid</th>'; 
                echo '<th>Paid UP?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '</tr>';
        echo '</thead>'   ;
        echo '<tbody>';
                foreach ($memberStatus1 as $memStat) {
             
                      echo "<tr>";
                   
                        echo "<td>".$memStat['year']."</td>";
                        echo "<td>".$memStat['id']."</td>"; 
                        echo "<td>".$memStat['userid']."</td>"; 
                        if ($memStat['paid'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<em><td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>";
                        echo "<td>".$memStat['email']."</td>";  

                 
                      echo "</tr>";
                  }
            echo '</tbody>';
            echo '</table><br>'; 
            echo '<input type=hidden name="thisyear" value="1">';
            echo "<button type='submit' name='updateMemPaid'>UPDATE MEMBERSHIP: ".$thisYear."</button>"; 
            echo '</form>';
        echo '</div>';
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="actions/updateMemberPaid.php">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
    
                echo '<th>Year</th>'; 
                echo '<th>ID</th>'; 
                echo '<th>Userid</th>'; 
                echo '<th>Paid UP?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '</tr>';
         echo '</thead>'   ;
         echo '<tbody>'  ;
                foreach ($memberStatus2 as $memStat) {
             
                      echo "<tr>";
                   
                        echo "<td>".$memStat['year']."</td>";
                        echo "<td>".$memStat['id']."</td>"; 
                        echo "<td>".$memStat['userid']."</td>"; 
                        if ($memStat['paid'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<em><td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>"; 
                        echo "<td>".$memStat['email']."</td>";  

                 
                      echo "</tr>";
                  }
            echo '</tbody>';
            echo '</table><br>'; 
            echo '<input type=hidden name="nextyear" value="1">';
            echo "<button type='submit' name='updateMemPaid'>UPDATE MEMBERSHIP: ".$nextYear."</button>"; 
            echo '</form>';
            echo '</div> ';  

            echo '<input type="hidden" name="email" value="'.$memStat['email'].'"><br>';
            echo '<input type="hidden" name="firstname" value="'.$memStat['firstname'].'"><br>';
            echo '<input type="hidden" name="lastname" value="'.$memStat['lastname'].'"><br>';
  
            echo '</div> ';  
            echo '</form>';
        echo '</section>';
        echo '</div>';
   


    }
    if ($_SESSION['role'] != 'INSTRUCTOR') {
 
        // echo '<div class="container-section ">';
        // echo '<br><br>';
        // echo '<section id="contacts" class="content">';
        //      echo '<h3 class="section-header">Contacts</h3>';  
        //      echo '<div class="form-grid3">';
          
        //      echo '<form method="POST" action="actions/maintainContact.php">';
        //      echo '<div class="form-grid-div">';
        //      echo '<h4>Maintain Contacts</h4>';
        //      echo '<input type="checkbox" name="deleteContact">';
        //      echo '<label for="deleteContact">Delete a Range of Contacts</label><br>';
        //      echo '<input type="date"  name="delContactBefore" >';
        //      echo '<label for="delContactBefore"><em> &larr; Specify a Date 
        //          to delete contacts before: </em></label><br>';
        //      echo '<button type="submit" name="submitContact">Delete Contacts</button> ';
        //      echo '</div>';
        //      echo '</form>';
        //      echo '<div class="form-grid-div">';
        //      echo '<h4>Report Contacts</h4>';
        //      echo '<form target="_blank" method="POST" action="actions/reportContact.php">';  
        //      echo '<button type="submit" name="reportContact">Report Contacts</button> ';
           
        //      echo '</div>';     
        //      echo '</form>';
        //      echo '</div>';   
        //      echo '<br>';
        //     echo '<table>';
        //     echo '<thead>';
        //         echo '<tr>';
        //             echo '<th>Date Contacted</th> '; 
        //             echo '<th>First Name</th>';
        //             echo '<th>Last Name    </th>';
        //             echo '<th>Email</th>';
        //             echo '<th>Message</th> ';
        //             echo '<th>Favorite Dance Style</th>';
        //             echo '<th>Dance Experience</th> ';       
                 
        //         echo '</tr>';
        //     echo '</thead>';
        //     echo '<tbody>';
        
        //         foreach($contacts as $contact) {
             
        //               echo "<tr>";
        //                 echo "<td>".$contact['contactdate']."</td>";
        //                 echo "<td>".$contact['firstname']."</td>";               
        //                 echo "<td>".$contact['lastname']."</td>";
        //                 echo "<td>".$contact['email']."</td>";
        //                 echo "<td>".$contact['message']."</td>"; 
        //                 echo "<td>".$contact['danceFavorite']."</td>"; 
        //                 echo "<td>".$contact['danceExperience']."</td>";             
        //               echo "</tr>";
        //           }
             
        //     echo '</tbody>';
        //     echo '</table>';
        //     echo '<br>';
       
            
        
        // echo '</section>';
        // echo '</div>';
        echo '<div class="container-section ">';
        echo '<br><br>';
        echo '<section id="visitors" class="content">';
            echo '<h3 class="section-header">Visitors</h3> '; 
            echo '<div class="form-grid3">';
          
            echo '<div class="form-grid-div">';
            echo '<h4>Report Visitors</h4>';
            echo '<form target="_blank" method="POST" action="actions/reportVisitors.php">';
      
            echo '<button type="submit" name="reportVisitors">Report Visitors</button> ';
          
            echo '</div>';     
            echo '</form>';
            echo '<br>';
            echo '<div class="form-grid-div">';
            echo '<h4>Archive Visitors</h4>';
            echo '<form method="POST" action="actions/archiveVisitors.php">';
            echo '<input type="checkbox" name="archiveVisitor">';
            echo '<label for="archiveVisitor">Archive Visitors </label><br> ';   
            echo '<button type="submit" name="submitArchive">Archive</button> ';
          
            echo '</div>';     
            echo '</form>';
            echo '<br>';
        
            echo '</div>';
            echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>Login Date</th> '; 
                    echo '<th>Login #</th> '; 
                    echo '<th>First Name</th>';
                    echo '<th>Last Name    </th>';
                    echo '<th>Email</th>';
                    echo '<th>Notes</th>';
                  
               echo '</tr>';
              echo '</thead>'  ;
              echo '<tbody>';
        
                foreach($visitors as $visitor) {
             
                      echo "<tr>";
                        echo "<td>".$visitor['logindate']."</td>";
                        echo "<td>".$visitor['numlogins']."</td>";
                        echo "<td>".$visitor['firstname']."</td>";               
                        echo "<td>".$visitor['lastname']."</td>";
                        echo "<td>".$visitor['email']."</td>";  
                        echo "<td>".$visitor['notes']."</td>";           
                      echo "</tr>";
                  }
             echo '</tbody>';
            echo '</table>';   
            echo '<br>';
          
        echo '</section>';
        echo '</div>';
     }
    ?>

    <footer >
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>