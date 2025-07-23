<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Email Class Registrants</title>
</head>
<body>
<br><br>
    <div class="content">

    <h1>Email Class Registrants</h1>
    <h4>This process takes a while to generate the email so please be patient. Just close the window when the process is complete.</h4>
      <form method="POST" action="actions/emailClass.php"> 
        <div class="form-grid-div">

        <input type="text" class="text-small" name="classId" required > 
        <label for="classId"><em> &larr; 
        Specify Class ID from Table On the Previous Page:</em> </label><br>
        <label for="replyEmail">Email to reply to: </label>> <br>
        <?php
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>'; 
        ?>
        <label for="emailBody">Email Text</label><br>
        <textarea  name="emailBody" rows="50" cols="150"></textarea><br>
      
        <br>
        <button type="submit" name="submitClassEmail">Send Email</button>   
        </div>   
</form>
    </div>


<?php
  include 'footer.php';
?>
</body>
</html>