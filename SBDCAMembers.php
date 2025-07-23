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
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];

$users = [];
unset($_SESSION['process_users']);

$_POST = array();
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
$num_users = 0;
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
$database = new Database();
$db = $database->connect();
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
              'joinedonline' => $joinedonline,
              'fulltime' => $fulltime,
              'regformlink' => $regformlink,
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
              'paidonline' => $paidonline,
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
              'paidonline' => $paidonline,
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
    <title>SBDC Ballroom Dance - Member Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Home Page" href="administration.php">Back to Administration</a></li>
        <li><a title="Add, Update, Report on Members" href="#users">Maintain Members</a></li>
        <li><a title="List Members Status" href="#membership">Maintain Membership Payments</a></li>
        <li><a title="Maintain Club Membership Options" href="SBDCAOptions.php">Membership Options</a></li>
        <li><a title="List Historical Data" href="SBDCAAMembers.php">Archived Members</a></li>
      </ul>
    </div>

</nav>

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
            echo '<div class="form-grid6">';
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
         
            echo '<div class="form-grid-div">';
            echo '<form target="_blank" method="POST" action="actions/reportRoboDJUsage.php">'; 
            echo '<button type="submit" name="submitRoboDJRep">Report Member Robo DJ Usage</button>';   
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
        echo '<th colspan="15" style="text-align:center">Member List</th>';
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
                echo '<th>Joined Online?</th>';
           
  
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
                       if ($user['joinedonline']) {
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
        echo '<input type="checkbox" name="reportPaid"><br>';
        // echo '<label for="reportUsers">Report Membership</label><br>';    
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
                echo '<th>PAID?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Paid Online</th>';
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
                              echo "<td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>";
                        echo "<td>".$memStat['email']."</td>";  
       
                        if ($memStat['paidonline'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<td><em>&times;</em></td>"; 
                          }   

                 
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
                echo '<th>PAID?</th>';
                echo '<th>Mark Paid</th>';
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Paid Online</th>';
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
                              echo "<td><em>&times;</em></td>"; 
                          }   
                        $ckboxId = "pd".$memStat['id'];
                        echo "<td>";
                          echo '<input type="checkbox" name="'.$ckboxId.'">';
                        echo "</td>";
                        echo "<td>".$memStat['firstname']."</td>";               
                        echo "<td>".$memStat['lastname']."</td>"; 
                        echo "<td>".$memStat['email']."</td>";  
                                               
                        if ($memStat['paidonline'] == true ) {
                            echo "<td><em>&#10004;</em></td>"; 
                          } else {
                              echo "<td><em>&times;</em></td>"; 
                          }   

                 
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
              

    ?>
  
    <footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>
