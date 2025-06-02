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
$_SESSION['adminurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
$num_visitors = 0;
$visitors = [];
$database = new Database();
$db = $database->connect();
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Visitor Administration</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Home Page" href="administration.php">Back to Administration</a></li>
        <li><a title="List Historical Data" href="SBDCAAVisitors.php">Visitors Archived</a></li>
      </ul>
    </div>

</nav>
<?php
if ($_SESSION['role'] != 'INSTRUCTOR') {
 

 echo '<div class="container-section ">';
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
<footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>