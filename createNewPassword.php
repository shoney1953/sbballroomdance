<?php
session_start();


require_once 'config/Database.php';
require_once 'models/User.php';
require_once 'models/PwdReset.php';
date_default_timezone_set("America/Phoenix");
$database = new Database();
$db = $database->connect();
$user = new User($db);
$pwdReset = new PwdReset($db);
$selector = null;
$validator = null;
$token = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Create New Password</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <div class="container-section ">
     
    <section id="createnewpassword" class="content">

 
        
        <div class="form-grid3">
        <div class="form-grid-div">
        <?php
        
         $selector = $_GET['selector'];
         $validator = $_GET['validator'];
         if (empty($selector) || empty($validator)) {
          echo '<p>We could not validate your request!</p>';
         } else {
          if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
            echo '<h1 class="section-header">Enter New Password</h1>';
      
            echo '<form method="POST" action="actions/createNewPWD.php">';
               echo "<input type='hidden' name='selector' value=".$selector.">";
               echo "<input type='hidden' name='validator' value=".$validator.">";

                echo '<label for="pwd">New Password - Minimum 8 characters</label><br>';
                echo '<input type="password" name="pwd" minlength="8" required><br>';
                echo '<label for="pwd2">Reenter New Password</label><br>';
                echo '<input type="password" name="pwd2" minlength="8" required><br>';
               
                echo '<br><button type="submit" name="SubmitCreatePwd">Create New Password</button><br>';              
          echo '</form>';
          
          if (isset($_GET['error'])) {
            if ($_GET['error'] === 'newpwempty') {
              echo '<p>One or both of the new passwords was empty; please try again.</p>';
            }
            if ($_GET['error'] === 'pwdnomatch') {
              echo '<p>Passwords do not match; please try again.</p>';
            }
            if ($_GET['error'] === 'timeout') {
              echo '<p>Timeout Error in validation; please return to login page and begin again.</p>';
            }
          }
          } else {
            echo '<p>Error in Validation; please go back and try again.</p>';
          }
         }
       ?>
     
        </div>
        </div>
    </section>
    </div>  


 <?php
  require 'footer.php';
?>   
</div> 

</footer>
</body>
</html>