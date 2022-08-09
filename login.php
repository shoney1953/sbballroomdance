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
   <br<h2>If you are a member and having trouble logging on please contact <a href="mailto:webmaster@sbballroomdance.com?subject=SBDC Info">
                webmaster@sbballroomdance.com</a></h2><br>
        
        <div class="form-grid3">
        <div class="form-grid-div">
        <h1 class="section-header">SBDC MEMBERS Please Log in here</h1>
            <form method="POST" action="actions/logInact.php">
               
            

                <label for="username">User Name for the Website</label><br>
                <input type="text" name="username" required><br>
                <label for="password">Enter Password</label><br>
                <input type="password" name="password" required minlength="8"><br>

                <br>
                <button type="submit" name="SubmitLogIN">Submit</button><br>
                
        </form>
    
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

                <label for="email">Vistor Email</label><br>
                <input type="email" name="email" required><br>
               
                <br>
                <button type="submit" name="SubmitVisitorLogIN">Submit</button><br>
                </div>
        </form>
        </div>
    </section>
    </div>

    </div>
    <footer >

<div class="footer-section">

    <p>Copyright &copy; 2022    Sheila Honey  - All Rights Reserved</p>
    
</div> 

</footer>
</body>
</html>