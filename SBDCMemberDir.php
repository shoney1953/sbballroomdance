<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/User.php';
$directory = $_SESSION['directory']
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Member Directory</title>
</head>
<body>
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="index.php">Back to Home</a></li>

    </ul>
     </div>
</nav>  
<div class="container-section ">
    <section id="directory" class="content">

        <h4 class="section-header">Membership Directory</h4><br>
    <?php

     if (isset($_SESSION['username'])) {
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] != 'visitor') {

        echo '<div class="form-grid3">';
          
        echo '<form target="_blank" method="POST" action="actions/reportDirectory.php">'; 
        echo '<div class="form-grid-div">';

        echo '<button type="submit" name="submitUserRep">Create Directory Report</button>';   
        echo '</div> ';  
        echo '</form>';
            
   
        echo '<div class="form-grid-div">';
        echo '<form target="_blank" method="POST" action="actions/searchDirectory.php" >';
        echo '<input type="text"  name="search" >';
        echo '<button type="submit" name="searchUser">Search Directory by Name or Email</button>';  
        echo '</form>';
     
     echo '</div>';
     echo '</div> ';    
     echo '<h4>List of Members</h4>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
              
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';          
                echo '<th>Address</th>';
   
                echo '</tr>';
        echo '</thead>';
        echo '<tbody>' ;           
                foreach($directory as $user) {
                    echo "<tr>";
                
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
 
                      echo "</tr>";
                  }
             
        echo '</tbody>';
            echo '</table><br>';       
 
            echo '</section>';
                }
            }
        }
        else {
            echo '<h3><a style="color: red;font-weight: bold;font-size: large"
            href="login.php"> <strong><em>Please Login as a Member to View Directory</em></strong></a></h3><br><br>'; 
        }
    
    

?>
    </section>

</div>

<?php
  include 'footer.php';
?>
</body>
</html>