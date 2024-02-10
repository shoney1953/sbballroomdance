<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>SBDC Ballroom Dance - Email Members</title>
</head>
<body>
<br><br>
    <div class="content">

    <h1>Email Members</h1>
    <h4>This process takes a while to generate the email so please be patient. Just close the window when the process is complete.</h4>
      <form method="POST" action="emailMember.php"> 
        <div class="form-grid-div">

      <label for"emailAll">Email All Members</label>
       <input type="checkbox" name="emailAll" >
       <br><br>OR<br><br>
       <label for"emailByHOA">Email Members by HOA</label>
       <input type="checkbox" name="emailByHOA" ><br>
       <label for"HOA">Select HOA</label>
       <select name = 'HOA'> 
         <option value="1">HOA 1</option>
         <option value="2">HOA 2</option>
       </select>
       <br><br>
        <label for="replyEmail">Email to reply to: </label>> <br>
        <?php
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>'; 
        ?>
        <label for="emailBody">Email Text</label><br>
        <textarea  name="emailBody" rows="30" cols="75"></textarea><br>
      
        <br>
        <button type='submit' name='submitEmailMember'>Send Email</button>    
        </div>   
</form>
    </div>

  <footer >
    <?php
    require '../footer.php';
   ?>
    </footer>
</body>
</html>