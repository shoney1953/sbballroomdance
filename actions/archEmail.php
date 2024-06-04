       <?php
       session_start();
       require_once '../config/Database.php';
       require_once '../models/ClassRegistrationArch.php';
       require_once '../models/DanceClassArch.php';
       $database = new Database();
       $db = $database->connect();
       $class = new DanceClassArch($db);

       $_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
       if (!isset($_SESSION['username']))
       {
           $redirect = "Location: ".$_SESSION['archiveurl'];
           header($redirect);
       }
       if (isset($_POST['submitArchEmail'])) {
        if (isset($_POST['classId'])){
             $class->id = $_POST['classId'];
             $class->read_single();

        } else {
          $redirect = "Location: ".$_SESSION['archiveurl'];
          header($redirect);
          exit;
        }
       }
       ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Archive Email</title>
</head>
<body>

<nav class="nav">
    <div class="container">    
    <ul>
        <li><a href="../archives.php">Back to Archives</a></li>

    </ul>
    </div>
</nav>  
<br><br><br>
<div class="content">
      <?php
       echo "<h4>Emailing registrants for  ".$class->classname."  ".$class->date."</h4>";
        echo '<form method="POST" action="emailArchClass.php"> ';
        echo '<div class="form-grid-div">';
        echo "<input type='hidden' name='classId' value='".$class->id."'>"; 
       
        echo '<label for="replyEmail">Email to reply to: </label>';
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>';  

        echo '<label for="emailBody">Email Text</label><br>';
        echo '<textarea  name="emailBody" rows="25" cols="50"></textarea><br>';
      
        echo '<br>';
        echo '<button type="submit" name="submitClassEmail">Send Email</button> ';  
        echo '</div> ';  

        echo '</form>';
        ?>
        </div>
        </div>
</body>
</html>
        