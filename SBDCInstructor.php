<?php
$sess = session_start();
require_once 'includes/siteemails.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Ballroom Dance - Instructor Info</title>
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
<br><br><br>
  
<div class="container-section ">
   <section id="instructorinfo" class="content">
   <br>  
       <h3 class="section-header">Become an Instructor</h3>
       <br>
       <p> All our Instructors are volunteers.</p>
        <p>Some folks have been dancing a long time;
           Some not so much.</p> 
        <p>Some have had formal training; Some not so much. </p>
        <p>It doesn't matter to us.</p> 
        <p> It is in the spirit of our club to share what we know. 
            All of our members are very 
               appreciative of anything you can share with them. </p>
      
        <p>If you'd like to become an instructor.. even for an hour class,
          please contact our Dance Instruction Director for more information 
          <?php
          echo ' <a href="mailto:'.$danceDirector.'?subject=SBDC Dance Instruction Info">
           Dance Director</a></p><br><br>';
        ?>
     
   </section>
   </div>
<?php
  include 'footer.php';
?>
</body>
</html>