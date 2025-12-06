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
    <title>SBDC Ballroom Dance - Contact</title>
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
    <section id="contact" class="content">
    <br>
    <?php

        echo '<h1 class="section-header">To correspond with one of the board members or directors, Just click on any of the names below: </h1>';

        echo '<ul>';
            echo '<li class="li-none li-large li-left">
                    <a href="mailto:'.$president.'?subject=SBDC Contact Info">
                       Ann Pizzitola</a>, President</li>';
            echo '<li class="li-none li-large li-left">  
                <a href="mailto:'.$vicePresident.'?subject=SBDC Info">
                Lynne Pendlebury</a>, Vice President, Questions about Events</li>';
            echo '<li class="li-none li-large li-left">
                <a href="mailto:'.$treasurer.'?subject=SBDC Contact Info">
                  Linda Shamburg</a>, Treasurer, Questions about costs of events or membership </li>';
            echo '<li class="li-none li-large li-left">   
                <a href="mailto:'.$secretary.'?subject=SBDC Contact Info">
                Pat Knepler</a>, Secretary, General Questions </li>';
            echo '<li class="li-none li-large li-left"> 
                <a href="mailto:'.$danceDirector.'?subject=SBDC Contact Info">
                Dale Pizzitola</a>, Dance Director, Questions about Class</li>';
            echo '<li class="li-none li-large li-left">
                <a href="mailto:'.$volunteerDirector.'?subject=SBDC Contact Info">
                Valerie Green</a>, Questions about Volunteering   </a></li>';
           echo '<li class="li-none li-large li-left">
                <a href="mailto:'.$djDirector.'?subject=SBDC Contact Info">
                Rick Baumgartner</a>, DJ Director, Questions about Music or DJing </li>';
            echo '<li class="li-none li-large li-left">  
               <a href="mailto:'.$webmaster.'?subject=SBDC Contact Info">
                Sheila Honey</a>, Webmaster, Questions about the Website </li>';
            echo '<li class="li-none li-large li-left"> 
                <a href="mailto:countmein@sbballroomdance.com?subject=SBDC Contact Info">
                countmein@sbballroomdance.com</a> Registering for or questions about events or class</li>';
  
        echo '</ul>';
        echo '<br><br>';
    
    
    ?>
    </section>
    </div>

   

        
<?php
  include 'footer.php';
?>
</body>
</html>