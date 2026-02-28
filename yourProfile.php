<?php
$sess = session_start();

require_once 'config/Database.php';
require_once 'models/ClassRegistration.php';
require_once 'models/EventRegistration.php';
require_once 'models/User.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}

if (isset($_GET['error'])) {
    echo '<br><h4 style="text-align: center"> ERROR:  '
    .$_GET['error'].'. Please Reenter Data</h4><br>';
    unset($_GET['error']);
} elseif (isset($_GET['success'])) {
    echo '<br><h4 style="text-align: center"> Success:  '
    .$_GET['success'].'</h4><br>';
    unset($_GET['success']);
} else {
    $_SESSION['profileurl'] = $_SERVER['REQUEST_URI']; 
    $_SESSION['returnurl'] = $_SERVER['REQUEST_URI']; 
   
}



$database = new Database();
$db = $database->connect();
$userid = $_SESSION['userid'];
$user = new User($db);
$partner = new User($db);
$user->id = $_SESSION['userid'];
$user->read_single();
if ($user->partnerId !== 0) {
    $partner->id = $user->partnerId;
    $partner->read_single();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v3">
    <title>SBDC Ballroom Dance - Profile</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
    
        <li><a href="profileMem.php">Membership Status</a></li>
    </ul>
    </div>
</nav> 


<div class="content">
    <br><br>
    <h3>Member Profile</h3>
    <form method='POST' action='actions/updateUserInfo.php'>
  
    <!-- <div class="form-container"> -->
    <fieldset>
        <legend>Update Your Profile Information</legend>
    <!-- <h4 class="form-title">Your Profile Information</h4> -->
        <div class="form-grid">
            <div class="form-item">
            <h4 class="form-item-title">First Name</h4>
             <input type='text' name='firstname' value='<?php echo $user->firstname ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Last Name</h4>
             <input type='text' name='lastname' value='<?php echo $user->lastname ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Email</h4>
             <input type='email' name='newemail' value='<?php echo $user->email?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">User Name</h4>
             <input type='text' name='newuser' value='<?php echo $user->username ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Directory List: </h4>
             <input type='number' name='directorylist' min='0' max='1' value='<?php echo $user->directorylist ?>'>
             <br><label style='font: smaller' for='directorylist'><em>1 to list, 0 to Remove</em></label>
            </div>
       
           
            <div class="form-item">
            <h4 class="form-item-title">HOA</h4>
            <select name = 'hoa' value='<?php echo $user->hoa ?>'>
            <?php
                if ($user->hoa == '1') {
                    echo "<option value = '1' selected>HOA 1</option>";
                } else {
                    echo "<option value = '1' >HOA 1</option>";
                }
                if ($user->hoa == 2) {
                    echo "<option value = '2' selected>HOA 2</option>";
                } else {
                    echo "<option value = '2' >HOA 2</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Fulltime Resident: </h4>

             <select name = 'fulltime' value='<?php echo $user->fulltime ?>'>
            <?php
                if ($user->fulltime == '1') {
                    echo "<option value = 1 selected>Fulltime</option>";
                } else {
                    echo "<option value = '1' >Fulltime</option>";
                }
                if ($user->fulltime == '0') {
                    echo "<option value = '0' selected>Gone for the Summer</option>";
                } else {
                    echo "<option value = '0' >Gone for the Summer</option>";
                }
                ?>
            </select>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Primary Phone: </h4>
            <!-- <input type='tel'  name='phone1'  value='<?php echo $user->phone1 ?>'><br> -->
            <input type='tel'  name='phone1' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' value='<?php echo $user->phone1 ?>'><br>
           
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Secondary Phone: </h4>
            <input type='tel'  name='phone2'  pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' value='<?php echo $user->phone2 ?>'><br>
            
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Street Address: </h4>
            <input type='text' name='streetaddress' value='<?php echo $user->streetAddress ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">City: </h4>
            <input type='text' name='city' value='<?php echo $user->city ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">State: </h4>
            <input class='text-small' type='text' name='state' maxsize='2' value='<?php echo $user->state ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">zip: </h4>
            <input  type='text' name='zip' maxsize='10' value='<?php echo $user->zip ?>'>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Partner ID: </h4>
            <input type='number' name='partnerid' value='<?php echo $user->partnerId ?>'>
            </div>
             <div class="form-item">
            <h4 class="form-item-title">Dietary Restriction: </h4>
            <textarea  name='dietaryrestriction' rows='2' cols='50'><?php echo $user->dietaryrestriction ?></textarea>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Notes: </h4>
            <textarea  name='notes' rows='4' cols='50'><?php echo $user->notes ?></textarea>
            </div>

        </div>
        <input type='hidden' name='id' value='<?php echo $user->id?>'>
        <input type='hidden' name='username' value='<?php echo $user->username?>'>
        <input type='hidden' name='email' value='<?php echo $user->email?>'>
        <input type='hidden' name='password' value='<?php echo $user->password ?>'>
        <input type='hidden' name='role' value='<?php echo $user->role ?>'>
      
        <button type="submit" name="submitUpdateUser">Update Your Information</button>
        </form>
    <!-- </div> -->
        </fieldset>
        <fieldset>
            <legend>Change Your Password</legend>
        <!-- <div class="form-container"> -->
        <form method="POST" action="actions/updateUserPass.php">
        <!-- <h4 class="form-title">Change Your Password</h4> -->
        <div class="form-grid">
            <div class="form-item">
            <h4 class="form-item-title">Enter Your Current Password</h4>
            <div class="form-group">
            <input type="password" id="oldpassword" name="oldpassword" required minlength="8">
            <svg onclick="togglePass1()" id="Layer_1" data-name="Layer 1" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg onclick="togglePass1()" hidden id="Layer_2" data-name="Layer 2" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
            </div>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Enter Your New Password</h4>
            <div class="form-group">
            <input type="password" id="newpassword" name="newpassword" required minlength="8">
            <svg onclick="togglePass2()" id="Layer_1" data-name="Layer 1" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg onclick="togglePass2()" hidden id="Layer_2" data-name="Layer 2" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
            </div>
            </div>
            <div class="form-item">
            <h4 class="form-item-title">Re-Enter Your New Password</h4>
            <div class="form-group">
            <input type="password" id="newpass2" name="newpass2" required minlength="8">
            <svg onclick="togglePass3()" id="Layer_1" data-name="Layer 1" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg onclick="togglePass3()" hidden id="Layer_2" data-name="Layer 2" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
            </div>
            </div>
            </div>
                <script>
                function togglePass1() {
                var x = document.getElementById("oldpassword");
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
                 function togglePass2() {
                var x = document.getElementById("newpassword");
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
                  function togglePass3() {
                var x = document.getElementById("newpass2");
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
        <input type="hidden" name="currentpass" value='<?php echo $user->password ?>'>
        <input type="hidden" name="id" value='<?php echo $user->id ?>'>   
        <button type='submit' name='SubmitPassChange'>Change Your Password</button>
        </form>
        <!-- </div> -->
            </fieldset>
 
  
       
      
    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>