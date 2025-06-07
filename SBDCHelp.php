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
    <title>SBDC Ballroom Dance - Help</title>
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
<br>
<div class="container-section ">
    <section id="help" class="content">
    <br><br> 
        <h3 class="section-header">For Help</h3><br>
        <p><a href="faq.php">Click to see Frequently Asked Questions</a></p>
        <br>
        <p><a title="Board Members" href="SBDCBoard.php">Contact One of the Board Members</a> or
        <?php

          echo '<a href="mailto:'.$webmaster.'?subject=SBDC Website Help">
                Webmaster</a> listed in the about section or
            read the introduction to the website PDF below.<br>
            <a href="https://drive.google.com/file/d/1P4FHXpaw0oUGfVpCkFnRAroipgOE53s9/view?usp=sharing">
                Click for the Member Guide to the Website  </a>';
        
              if (isset($_SESSION['role'])) {
               if ($_SESSION['role'] === 'INSTRUCTOR') {
                        echo '<h4><a href=" https://drive.google.com/file/d/10C0c7Kyy96TC59KxG7W7khnSnOSTyCwa/view?usp=sharing">Click for Instructors Guide</a></h4>';
                } 
                if (($_SESSION['role'] === 'SUPERADMIN') | ($_SESSION['role'] === 'ADMIN')) {
                    echo '<h4><a href="https://drive.google.com/file/d/1R7kQMmGTbadaz_0ekPAIQXOrYvo0Mut8/view?usp=sharing">Click for Administrators Guide</a></h4>';
                }
             }
         ?>
        </p><br><br>
    </section>
   </div>
<?php
  include 'footer.php';
?>
</body>
</html>