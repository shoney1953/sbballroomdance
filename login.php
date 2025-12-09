<?php
  session_start();
  require_once 'includes/siteemails.php';


date_default_timezone_set("America/Phoenix");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC Login</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
  
    <div id="login" class="content">

   <br><br><br><h4>If you are a member, please use the SBDC member log in. If not, please log in as a visitor.</h4>
   <h4>If you are a member and having trouble logging on     
         <a href="faq.php">Click to See Our Frequently Asked Questions</a>, or 
       <a href="mailto:'.$webmaster.'?subject=SBDC Login Info"> Click to contact Webmaster</a></h4>
       <br><br>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="container-error">';
            echo '<h4 class="error"> ERROR:  '.$_GET['error'].'. Please Reenter Data</h4>';
            echo '</div>';
            unset($_GET['error']);
        } else {
            $_SESSION['loginurl'] = $_SERVER['REQUEST_URI'];    
        }
                ?>
          
        <div class="form-grid6">
     <div class="form-grid-div"> 
       <!-- <h4 class="section-header">SBDC MEMBERS Please Log in here</h4>  -->

      
            <form method="POST" action="actions/logInact.php">
               
              <fieldset>
                <legend> SBDC MEMBERS Please Log in here</legend>
                <br><label for="username">Member User Name or Email</br>
                <input type="text" name="username" title="Your User Name will be either your email or your first name and last initial 
            with the first letter of your first name and first letter of your last name capitalized." required><br>
                <label for="password">Enter Password</label><br>
                <div class="form-group">
                <input type="password" name="password" id="login-form-password" title="default password is test1234" required minlength="8"><br>
                <svg onclick="togglePass()" id="Layer_1" data-name="Layer 1" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
                <svg onclick="togglePass()" hidden id="Layer_2" data-name="Layer 2" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
                </div>
                <script>
                function togglePass() {
                var x = document.getElementById("login-form-password");
                var l1 = document.getElementById("Layer_1");
                var l2 = document.getElementById("Layer_2");
                if (x.type === "password") {
                    x.type = "text";
                    l1.setAttribute('hidden', true);
                    l2.removeAttribute('hidden');
                } else {
                    x.type = "password";
                    l1.removeAttribute('hidden');
                    l2.setAttribute('hidden', true);
                }
                }
                </script>
                <br>
                <button type="submit" name="SubmitLogIN">Submit</button><br>      
                
        </form>
       
        <br>
        
              <a style="font-weight: bold; font-size: 16px" href="forgotPassword.php"><Click>Forgot Your Member Password? <br>Click here to get a reset password email. </em></a>
              <p>This email may go to your Spam or Junk folders, so please check those folders if you do not see the email.
              <br><br>
              
         </fieldset>    
        </div>
        <div class="form-grid-div">
        </div>
        <div class="form-grid-div">
        <!-- <h4 class="section-header">Visitors Please Log in here.</h4> -->
            <form method="POST" action="actions/visitorAct.php">
                 <fieldset>
                    <legend>VISITORS Please Log in here</legend>
                <br><label for="firstname">Visitor First Name</br>
                <input type="text" name="firstname" required><br>
                <label for="lastname">Visitor Last Name</label><br>
                <input type="text" name="lastname" required><br>

                <label for="email">Visitor Email</label><br>
                <input type="email" name="email" required><br>
                <label for="notes">Visitor Notes</label><br>
                <input type="text" name="notes" ><br>
                
               
                <br>
                <button type="submit" name="SubmitVisitorLogIN">Submit</button><br>
                </div>
                </fieldset>    
        </form>
        </div>
    </section>
    </div>


    <footer >

    <?php
  require 'footer.php';
?>
    
</div> 

</footer>
</body>
</html>