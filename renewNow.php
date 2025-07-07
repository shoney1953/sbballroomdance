<?php
session_start();

date_default_timezone_set("America/Phoenix");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Renew Today</title>
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
    <section id="joinUs" class="content">
   <br><br><br>
   <h1>Renew Your Membership Today!</h1>
   <h3>Members enjoy the benefits of attending any of our classes at no cost!
   They also receive reduced prices for our dinner dances!</h3>
   <h3>We would love to have you continue with us! It's easy. </h3>
      <?php
//    if (isset($_SESSION['testmode'])){
//    if ($_SESSION['testmode'] === 'YES') {
 
     echo "<button><a href='renewNowO.php'>Renew & Pay Online</a></button>";
         echo '<h3>OR</h3>';
//    }
//    }

   ?>
   <h3>You can print the membership form and send it it with your check</h3>
   <h3><a href='img/SBDC Membership Form 2025.pdf' target='_blank'>
            Click for Membership Form</a></h3>

 
       


 

    <br><br>
   
    </section>
    </div>

    <footer >

    <?php
  require 'footer.php';
?>
    
</div> 

</footer>
</body>
</html>