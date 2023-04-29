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
    <title>SBDC Join Us Today</title>
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
   <h1>Join Our Club Today</h1>
   <h2>Members enjoy the benefits of attending any of our classes at no cost!
   They also receive reduced prices for our dinner dances!
   We would love to have you join us! It's easy.</h2>

  <h2>Just fill out the form below and send it along with member dues to the treasurer of our club (name and address is on the form).</h2>
  <h1><a  
            href='img/Membership Form 2023 Dance Club.pdf' target='_blank'>
            Click for Membership Form</a></h1>
    <h3> As soon as your information is entered, you'll get a login and password and can login to the website to register for events and classes.</h3>  
    <br><br>
   
    </section>
    </div>

    <footer >

<div class="footer-section">
<?php
$year = date("Y"); 
    echo '<p>Copyright &copy; '.$year.'    Sheila Honey  - All Rights Reserved</p>';
?>
    
</div> 

</footer>
</body>
</html>