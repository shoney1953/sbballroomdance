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
    <title>SBDC Ballroom Dance - Board Members</title>
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

    <section id="board" class="content">
    <h4>Current Board Members (May 2025)</h4>
        <h4>Click on the Email to correspond with one of our Board Members</h4><br>
        <ul>
            <?php
            echo '<li class="li-none li-large">Ann Pizzitola, President 
                   &rarr; <a title="click to email" href="mailto:'.$president.'?subject=SBDC Info">
                       Email Ann</a></li>';
            echo '<li class="li-none li-large">Lynne Pendlebury, Vice-President 
                &rarr;  <a title="click to email" href="mailto:'.$vicePresident.'?subject=SBDC Info">
                Email Lynne</a></li>';
            echo '<li class="li-none li-large">Linda Shamburg, Treasurer 
                  &rarr; <a title="click to email" href="mailto:'.$treasurer.'?subject=SBDC Info">
                  Email Linda</a></li>';
            echo '<li class="li-none li-large">Pat Knepler, Secretary 
                  &rarr; <a title="click to email" href="mailto:'.$secretary.'?subject=SBDC Info">
                  Email Pat</a></li>';
        
            echo '<li class="li-none li-large">Dale Pizzitola, Director of Dance Instruction    
                &rarr; <a title="click to email" href="mailto:'.$danceDirector.'?subject=SBDC Info">
                Email Dale</a></li>';
           echo '<li class="li-none li-large">Valerie Green, Volunteer Coordinator  
                &rarr; <a title="click to email" href="mailto:'.$volunteerDirector.'?subject=SBDC Info">
                Email Valerie</a></li>';
            echo '<li class="li-none li-large">Rick Baumgartner, DJ Coordinator   
                &rarr; <a title="click to email" href="mailto:'.$djDirector.'?subject=SBDC Info">
                Email Rick</a></li>';
            echo '<li class="li-none li-large">Sheila Honey, Web Master    
                &rarr; <a title="click to email" href="mailto:'.$webmaster.'?subject=SBDC Web Info">
                Email Sheila</a></li>';
                ?>
  
        </ul>


        <br>
        <ul>

       
        <a href="https://drive.google.com/file/d/1w5YstWpE8sFMCrSLxKTxjg2qBsQ8ctyY/view?usp=sharing">
         Click Here To Read the Club By Laws.
        </a><br><br>
        </ul>
        
</div>
</section>
</div>
<?php
  include 'footer.php';
?>
</body>
</html>