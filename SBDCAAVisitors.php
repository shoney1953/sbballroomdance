<?php
session_start();
require_once 'config/Database.php';

require_once 'models/VisitorsArch.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
$_SESSION['archiveurl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
$database = new Database();
$db = $database->connect();

$visitors = [];
$num_visitors = 0;


/* get archived visitors */
$visitorArch = new VisitorArch($db);
$result = $visitorArch->read();

$rowCount = $result->rowCount();
$num_visitors = $rowCount;

if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reg_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'logindate' => date('m d Y h:i:s A', strtotime($logindate)),
            'notes' => $notes,
            'numlogins' => $numlogins
        );
        array_push($visitors, $reg_item);
  
    }
}

  


   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Archived Visitors</title>
</head>
<body>
<nav class="nav">
    <div class="container"> 
     <ul> 
        <li><a title="Return to Home Page" href="index.php">Back to Home</a></li>    
        <li><a title="Return to Administration Page" href="administration.php">Back to Administration</a></li>
        <li><a title="Return to Visitors" href="SBDCAVisitors.php">Back to Visitors</a></li>

      </ul>
    </div>

</nav>
<?php

            echo '<div class="container-section ">';
          
            echo '<section id="visitorsarchived" class="content">';
                echo '<h3 class="section-header">Visitors Archived</h3> '; 
                echo "<div class='form-grid3'>";
                echo '<div class="form-grid-div">';
                echo '<form method="POST" action="actions/reportVisitorsArchived.php">';
                echo '<label for="reportVisitor">Report on Archived Visitors </label><br>';  
                echo '<input type="checkbox" name="reportVisitor"><br>';
                echo '<button type="submit" name="submitVisitorRep">Report</button>';    
                echo '</form>';
                echo '</div>';   
                echo '</div>';  
                echo '<br>';
                
                echo '<table>';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Login Date</th> '; 
                        echo '<th>Logins #</th> '; 
                        echo '<th>First Name</th>';
                        echo '<th>Last Name    </th>';
                        echo '<th>Email</th>';
                        echo '<th>Notes</th>';
                      
                   echo '</tr>';
                   echo '</thead>';
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
                ?>
<footer>
    <?php
    require 'footer.php';
   ?>
    </footer>
   
</body>
</html>