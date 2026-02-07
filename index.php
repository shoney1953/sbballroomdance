<?php 

session_start();

require_once 'config/Database.php';
require_once 'models/Event.php';
require_once 'models/DanceClass.php';
require_once 'models/ClassRegistration.php';
require_once 'models/User.php';
require_once 'models/Options.php';
require_once 'models/Keys.php';
require_once 'includes/siteemails.php';




if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. 
    Please Validate Input</h4><br>';

    unset($_GET['error']);
} elseif (isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> '.$_GET['success'].'</h4><br>';

    unset($_GET['success']);
} else {
    $_SESSION['homeurl'] = $_SERVER['REQUEST_URI']; 
   
}

$_SESSION['upcoming_eventnumber'] = 0;
$option_item = [];

$_SESSION['user'] = null;
$_SESSION['joiningonline'] = 'NO';
$_SESSION['numupcomingclasses'] = 0;
date_default_timezone_set("America/Phoenix");
$num_classes = 0;
$num_events = 0;
$classes = [];
$events = [];
$num_users = 0;
$upcomingClasses = [];
$upcomingEvents = [];
$directory = [];
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
  $current_year = date('Y');

  $next_year = date('Y', strtotime('+1 year'));
  $current_year = date('Y');
// require 'includes/db.php';
$database = new Database();
$db = $database->connect();
$allOptions = [];
$options = new Options($db);
$result = $options->read();

$rowCount = $result->rowCount();

$num_options = $rowCount;

$_SESSION['allOptions'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
 
        $option_item = array(
            'id' => $id,
            'year' => $year,
            'renewalmonth' => $renewalmonth,
            'discountmonth' => $discountmonth   
        );
        array_push($allOptions, $option_item);

    }
    $_SESSION['allOptions'] = $allOptions;
} 
foreach($allOptions as $option) {
    if ($current_year === $option['year']) {
        $_SESSION['renewalmonth'] = $option['renewalmonth'];
        $_SESSION['discountmonth'] = $option['discountmonth'];

        break;
    }
}
$allKeys = [];
$keys = new Keys($db);
$result = $keys->read();

$rowCount = $result->rowCount();

$num_options = $rowCount;

$_SESSION['allKeys'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
 
        $key_item = array(
            'id' => $id,
            'year' => $year,
            'testkey' => $testkey,
            'prodkey' => $prodkey   
        );
        array_push($allKeys, $key_item);

    }
    $_SESSION['allKeys'] = $allKeys;
} 
foreach($allKeys as $key) {
    if ($current_year === $key['year']) {
        $_SESSION['testkey'] = $key['testkey'];
        $_SESSION['prodkey'] = $key['prodkey'];

        break;
    }
}
// get events
$event = new Event($db);
$result = $event->read();

$rowCount = $result->rowCount();
$num_events = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event_item = array(
            'id' => $id,
            'eventname' => $eventname,
            'eventtype' => $eventtype,
            'eventdate' => $eventdate,
            'eventcost' => $eventcost,
            'eventform' => $eventform,
            'orgemail' => $orgemail,
            'eventdj' => $eventdj,
            'eventdesc' => $eventdesc,
            'eventroom' => $eventroom,
            'eventregend' => $eventregend,
            'eventregopen' => $eventregopen,
            'eventnumregistered' => $eventnumregistered,
            'eventproductid' => $eventproductid,
            'eventmempriceid' => $eventmempriceid,
            'eventguestpriceid' => $eventguestpriceid,
            'eventdinnerregend' => $eventdinnerregend,
            'eventguestcost' => $eventguestcost
        );
        array_push($events, $event_item);
   
        if (strtotime($compareDate) <= strtotime($row['eventdate'])) {
            array_push($upcomingEvents, $event_item);
        }

    }

} 

$_SESSION['events'] = $events;
$_SESSION['upcoming_events'] = $upcomingEvents;


/* get classes */

$class = new DanceClass($db);
$result = $class->read();

$rowCount = $result->rowCount();
$num_classes = $rowCount;

$current_month = date('m');
$current_year = date('Y');

$numUpcomingClasses = 0;
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
            'instructors' => $instructors,
            "registrationemail" => $registrationemail,
            "room" => $room,
            "classnotes" => $classnotes,
            'numregistered' => $numregistered,
            'date2' => $date2,
            'date3' => $date3,
            'date4' => $date4,
            'date5' => $date5,
            'date6' => $date6,
            'date7' => $date7,
            'date8' => $date8,
            'date9' => $date9
        );
        array_push($classes, $class_item);
        $class_month = substr($row['date'], 5, 2);
        $class_year = substr($row['date'], 0, 4);

        if ($current_year < $class_year) {
            $numUpcomingClasses++;
            array_push($upcomingClasses, $class_item);
        } else {         
            if ($current_month <= $class_month) {
                $numUpcomingClasses++;
                  array_push($upcomingClasses, $class_item);
             } 
            
        }

   $_SESSION['numupcomingclasses'] = $numUpcomingClasses;
       
     
    }

} 

$_SESSION['classes'] = $classes;
$_SESSION['upcoming_classes'] = $upcomingClasses;
$_SESSION['allClasses'] = $upcomingClasses;

if (isset($_SESSION['username'])) {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'visitor') {
    $user = new User($db);
    $result = $user->read();
    
    $rowCount = $result->rowCount();
    $num_users = $rowCount;
    $_SESSION['num_members'] = $num_users;
    $_SESSION['directory'] = [];
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
                'password' => $password,
                'partnerId' => $partnerid,
                'hoa' => $hoa,
                'passwordChanged' => $passwordChanged,
                'streetAddress' => $streetaddress,
                'lastLogin' => date('m d Y h:i:s A', strtotime($lastLogin)),
                'numlogins' => $numlogins,
                'directorylist' => $directorylist,
                'fulltime' => $fulltime,
                'robodjnumlogins' => $robodjnumlogins,
                'robodjlastlogin' => $robodjlastlogin
            );
            if ($user_item['directorylist']) {
                array_push($directory, $user_item);
            }
         
      
        }
   
        $_SESSION['directory'] = $directory;
    } 
}
}
}
/* get options */
$option_item = [];
$allOptions = [];
$options = new Options($db);
$result = $options->read();

$rowCount = $result->rowCount();

$num_options = $rowCount;

$_SESSION['allOptions'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
 
        $option_item = array(
            'id' => $id,
            'year' => $year,
            'renewalmonth' => $renewalmonth,
            'discountmonth' => $discountmonth   
        );
        array_push($allOptions, $option_item);

    }
    $_SESSION['allOptions'] = $allOptions;
} 

// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" 
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css?v=5">
    <title>SBDC Ballroom Dance</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<!-- <div class="container-section"> -->
<nav class="nav">
    <div class="container">
     <ul>
       
         <li> <a href="#" >Home</a></li>
     
            <?php
             if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] != 'visitor') {
           
                  echo ' <li><a href="yourProfile.php">
                  <img title="Click to see or update your information or registrations" src="img/profile.png" alt="Your Profile" style="width:32px;height:32px;">
                  <br>Your Profile</a></li>';
     
                }
                if ($_SESSION['role'] === 'visitor') {
        
                echo ' <li style="color: red;font-weight: bold;font-size: medium">
                Welcome '.$_SESSION["visitorfirstname"].'</li>'; 
 
                }
            }
       
            ?>
           <li><a title="More information about our club" href="SBDCAbout.php">About Us</a></li>
            <!-- <li><a title="Combined Calendar of Events and Classes" href="SBDCCalendar.php">Activities Calendar</a></li> -->
            <li><a style="font-weight: bold"
        href="https://calendar.google.com/calendar/embed?src=sbbdcschedule%40gmail.com&ctz=America%2FPhoenix" target="_blank">
         Activities Calendar
        </a></li>
            <li><a title="Pictures from various activities and class videos" href="SBDCPictures.php">Photos</a></li>
                <?php
  
        echo '  <li><a title="List of Upcoming Events" href="SBDCEventst.php">Upcoming Events</a></li>';
    
    
          
  
       echo '<li><a title="List of Upcoming Classes" href="SBDCClassest.php">Upcoming Classes</a></li>';
  
      ?>
          
<!-- 
            <li><a title="2025 New Years Cruise" href="specialevent.php">2025 Cruise Info</a></li> -->
   
     </ul>
   
</div>
     <div class="container">
     <ul>
    <?php
  
        if ((isset($_SESSION['username'])) | (isset($_SESSION["visitorfirstname"]))) {
        if (isset($_SESSION['role'])) {
            echo ' <li><a  title="Logout from the Website" style="color: red;font-weight: bold;font-size: medium" href="logout.php">Logout</a></li>'; 
   
         if ($_SESSION['role'] != 'visitor') {
         if ($_SESSION['renewThisYear'] === 1)  {
                        echo '<li><a class="menu-blink" title="Renew Your Membership"  style="color: red;font-weight: bold;font-size: medium" href="renewNow.php"> Renew For '.$current_year.'</a></li>';  
            } else {
        if ($_SESSION['renewNextYear'] === 1) {
                        echo '<li><a class="menu-blink" title="Renew Your Membership"  style="color: red;font-weight: bold;font-size: medium" href="renewNow.php"> Renew For '.$next_year.'</a></li>';  
            }
           }
          }
    
        }
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'visitor') {
              
              echo ' <li><a title="List of SaddleBrooke Ballroom Dance Club Members" href="SBDCMemberDirectory.php">Member Directory</a></li>';
            //  echo ' <li><a title="Volunteer Nomination Form" href="https://drive.google.com/file/d/1IidsVacsyALADJhXKUVwaPahdYxI0XIK/view?usp=drive_link">Volunteer Nomination Form</a></li>';
              echo '<li><a title="List of Volunteer Activites" href="#">Volunteer &dtrif;</a>';
              echo '<ul >';
                  echo '<li><a href="SBDCDJInfo.php">DJ Info</a></li>';
                  echo '<li><a href="SBDCInstructor.php">Instructor Info</a></li>';
                  echo '<li><a href="SBDCOtherVol.php">Other Opportunities</a></li>';
              echo '</ul>';

            }
        }
 
    }
        if (isset($_SESSION['role'])) {
            if (($_SESSION['role'] === 'ADMIN') ||
                ($_SESSION['role'] === 'SUPERADMIN') ||
                ($_SESSION['role'] === 'EVENTADMIN') ||
                ($_SESSION['role'] === 'DJ') ||
                ($_SESSION['role'] === 'INSTRUCTOR')
             ) {
                echo '<li><a title="Club Administrative Functions" href="administration.php">Administration</a></li>';
            }
        if ($_SESSION['role'] === 'visitor') {
      
            echo '<li><a class="menu-blink" title="Information on Joining the Club" style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
             }
        }
     else {
        echo '<li><a  title="Login as a Member or Visitor" style="color: red;font-weight: bold;font-size: medium" href="login.php"> Login</a></li>';
        echo '<li><a class="menu-blink" title="Information on Joining the Club" style="color: red;font-weight: bold;font-size: medium" href="joinUsNow.php"> Join Us Now</a></li>';
    }
 

    
   
    echo '<li><a title="Board Members" href="SBDCBoard.php">Board Members</a></li>';
    echo '<li><a title="Frequently Asked Questions" href="faq.php">FAQs</a></li>';
    echo '<li><a title="Additional Options" href="#">More ... &dtrif;</a>';
    echo '<ul>';
    echo '<li><a title="How to Contact Us" href="SBDCContact.php">Contact Us</a></li>';
    echo '<li><a title="Where to find Help" href="SBDCHelp.php">Help</a></li>';
    echo '<li><a title="Various Sources of More information" href="resources.php">Resources</a></li>';
    echo '<li><a title="Sponsors for the SaddleBrooke Ballroom Dance Club" href="sponsor.php">Sponsors</a></li>';
    echo '</ul>';
    echo '</li>';
    ?>
        </ul> 
</div>
</nav>
<!-- </div> -->
    <div class="hero">
        <div class="container">
        <!-- <img class="motto-img2" 
            src="img/SBDC LOGO.png" alt="logo">  -->
            <br><br>
            <!-- <h1><img class="motto-img2" 
            src="img/SBDC LOGO.png" alt="logo"></h1> -->
            <h1 > Welcome to the SaddleBrooke Ballroom Dance Club Website        </h1>
       
            <p>We are a primarily social club that provides dance lessons,
                 and opportunities to dance and socialize.</p><br>
         
            <?php
            echo '<p>We are comprised of around '.$num_users.' members from both SaddleBrooke HOA 1 and HOA 2.</p><br>';
            ?>
            <p>No previous dance experience is required, we can teach you how to dance!</p>
            <p>We're not <em>"strictly ballroom"</em>. Latin, Western, and Line Dance 
               are also generes of music we play at our dances. </p><br><br>
            <p>Click one of the menu tabs above for more information</p><br>

        </div> 
<!--      
    </div> -->
 
    
  

  

</div>
    <!-- <section id="nominationform" class="content">
    <h1>Volunteer Nomination Form</h1><br>
 
   <ul>
    <li class="li-none"><a  href="https://drive.google.com/file/d/1Mr3NHDtOXQiHQbhaA_5kheREf5UuRF4G/view?usp=sharing">Click to download Volunteer Nomination form</a></li>
   </ul>

    </section>
   <br>
   </div> --> 
<?php
  require 'footer.php';
?>
</body>
</html>
