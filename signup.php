<?php
session_start();

if(isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif(isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '.$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} 
else {
    $_SESSION['signurl'] = $_SERVER['REQUEST_URI']; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Sign Up</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <h1 class="logo" style="background-color: rgba(161, 121, 133, 0.2); border-radius: 45%; width: 70px;align-items:center">
            <a href="index.html"><img src="img/logobox.png" alt="" style="width: 50px;align-items:center"></a></h1>
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <div class="container-section ">
    <section id="signup" class="content">
   
        
        <div class="form-grid1">
   
        <h1 class="section-header">Please Sign Up for our Website</h1>
            <form method="POST" action="actions/signUp.php">
                <div class="form-grid-div">
                
                    <label for="firstname">First Name</label><br>
                    <input type="text" name="firstname" required><br>
                    <label for="lastname">Last Name</label><br>
                    <input type="text" name="lastname" required><br>
                    <label for="email">Email</label><br>
                    <input type="email" name="email" required><br>
                    <label for="username">User Name for the Website</label><br>
                    <input type="text" name="username" required><br>
                    <label for="password">Enter Password minimum 8</label><br>
                    <input type="password" name="password" required minlength="8"><br>
                    <label for="pass2">Reenter Password</label><br>
                    <input type="password" name="pass2" required minlength="8"><br>
                    <br>
                    <button type="submit" name="SubmitSignUP">Submit</button><br>
                </div>
        </form>
        </div>
    </section>
    </div>
    </div>
</body>
</html>