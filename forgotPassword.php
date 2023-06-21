<?php
  session_start();

if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} 
$_SESSION['loginurl'] = $_SERVER['REQUEST_URI']; 
date_default_timezone_set("America/Phoenix");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Reset Password</title>
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
    <section id="resetpassword" class="content">
   <br><br><br><h1>Reset Your Password</h1>
 
        
        <div class="form-grid3">
        <div class="form-grid-div">
        <h1 class="section-header">SBDC Members Please enter your Email for a Password Reset Link to be sent to you.</h1>
      
            <form method="POST" action="actions/resetPWD.php">
               

                <label for="email">Member Email to send Password Link</label><br>
                <input type="email" name="email" required><br>
                
                <br>
                <button type="submit" name="SubmitResetPwd">Reset Your Password</button><br>              
        </form>
        <?php
       if (isset($_GET['reset'])) {
        if ($_GET['reset'] == 'success') {
            echo '<br><p>Reset Success; Please Check Your Email!</p><br>';
        }
        if ($_GET['reset'] == 'noemail') {
            echo '<br><p>Reset Failed; We did not find your email!</p><br>';
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