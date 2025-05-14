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
    <title>SBDC Ballroom Dance - DJ Information</title>
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
   <section id="djinfo" class="content">
   <br>  
       <h1 class="section-header">Become a DJ</h1>
       <p> All our DJs are volunteers. We have two speakers available, 
           and quite a large 
           body of club music available. 
           We even have some pre-made song lists to facilitate
           DJ duties. See below for all the documents we've put together. </p>

       <a  target="_blank"
href="https://drive.google.com/drive/folders/1LjnghlW8uftZHNxDG1YN4hbkq5AU2f7f?usp=sharing">
DJ Documents</a><br>
     <a target="_blank" href="https://drive.google.com/file/d/1y6GRdngzNWCgx-xSKYwk2wtc-8qK8eGC/view?usp=sharing">DJ Policy</a><br><br>
             <p>
            Also, Check out the Automated DJ APP (ROBO DJ) that can be used where WIFI is available
        </p>
       
        <a target="_blank" href="https://drive.google.com/file/d/1lRa1Sr_RIpyj-yLF_9qeZgdC7QGZ5q-X/view?usp=sharing"><em>Click for ROBO DJ Guide</em></a><br>
         <a  target="_blank" href="https://sbdcrobodj.com/">Go to ROBO DJ App</a><br>
   
        <?php
        echo '<p>Contact our DJ coordinator,<a href="mailto:'.$djDirector.'?subject=SBDC DJ Info">';
        ?>
        Rick Baumgartner</a><br><br></p>
       
     
   </section>
   </div>
<?php
  include 'footer.php';
?>
</body>
</html>