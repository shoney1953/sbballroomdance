<?php
  session_start();
  require_once 'includes/siteemails.php';
if (isset($_GET['error'])) {
    echo '<div class="container-error">';
    echo '<br><br><br><h3 class="error"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h3><br>';
    echo '</div>';
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
    <title>SBDC Login</title>
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
    <section id="login" class="content">
   <br><br><br><h1>If you are a member, please use the SBDC member log in. If not, please log in as a visitor.</h1>
   <br<h2>If you are a member and having trouble logging on 
             <?php
                echo '<a href="faq.php">Click to See Our Frequently Asked Questions</a>, or 
                please contact <a href="mailto:'.$webmaster.'?subject=SBDC Login Info">
                Webmaster</a>.';
                ?>
            </h2><br>
        
        <div class="form-grid6">
        <div class="form-grid-div">
        <h1 class="section-header">SBDC MEMBERS Please Log in here</h1>
        <h4>HINT: <br>Your User Name will be <em>either</em> your email <em>or</em> your first name and last initial 
            with the first letter of your first name and first letter of your last name capitalized.</h4><br>
            <form method="POST" action="actions/logInact.php">
               

                <label for="username">User Name for the Website</label><br>
                <input type="text" name="username" required><br>
                <label for="password">Enter Password</label><br>
                <input type="password" name="password" required minlength="8"><br>

                <br>
                <button type="submit" name="SubmitLogIN">Submit</button><br>              
        </form>
       
        <br>
        
              <a style="font-weight: bold; font-size: 16px" href="forgotPassword.php"><em>Forgot Your Member Password?  Click to get a reset password email.</em></a>
              <br><br><br>
              

        </div>
        <div class="form-grid-div">
        </div>
        <div class="form-grid-div">
        <h1 class="section-header">Visitors Please Log in here.</h1>
            <form method="POST" action="actions/visitorAct.php">
                     
                <label for="firstname">Visitor First Name</label><br>
                <input type="text" name="firstname" required><br>
                <label for="lastname">Visitor Last Name</label><br>
                <input type="text" name="lastname" required><br>

                <label for="email">Visitor Email</label><br>
                <input type="email" name="email" required><br>
                <label for="notes">Visitor Notes</label><br>
                <input type="text" name="notes" ><br>
                
               
                <br>
                <button type="submit" name="SubmitVisitorLogIN">Submit</button><br>
                </div>
        </form>
        </div>
    </section>
    </div>

    </div>
    <footer >

    <?php
  require 'footer.php';
?>
    
</div> 

</footer>
</body>
</html>