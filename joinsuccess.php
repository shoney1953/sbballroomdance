<?php
session_start();
$potentialMember1 = $_SESSION['potentialMember1'];
$potentialMember2 = $_SESSION['potentialMember2'];

$_SESSION['successurl'] = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">     
    
</script>
    <title>Successful Membership</title>
</head>
<body>
    <nav class="nav">
        <div class="container">
        
        <ul>
            <li><a href="index.php">Back to Home</a></li>
        </ul>
        </div>
    </nav>
    <section id="joinSuccessful" class="content">

    
        <?php

        $userdefault = ucfirst($potentialMember1['firstname']).ucfirst(substr($potentialMember1['lastname'],0,1));
        $roledefault = 'MEMBER';
        $passdefault = 'test1234'; 

            echo "<form method='POST' name='hiddenForm1' id ='hiddenForm1' action='actions/addoUser.php'>";
            
            echo "<input type='hidden' name='firstname1' value='".$potentialMember1['firstname']."'>";
            echo "<input type='hidden' name='lastname1' value='".$potentialMember1['lastname']."'>";
            echo "<input type='hidden' name='email1' value='".$potentialMember1['email']."'>";
            echo "<input type='hidden' name='username1' value='".$userdefault."'>";
            echo "<input type='hidden' name='phone11' value='".$potentialMember1['phone1']."'>";
            echo "<input type='hidden' name='streetaddress1' value='".$potentialMember1['streetaddress']."'>";
            echo "<input type='hidden' name='city1' value='".$potentialMember1['city']."'>";
            echo "<input type='hidden' name='state1' value='".$potentialMember1['state']."'>";
            echo "<input type='hidden' name='zip1' value='".$potentialMember1['zip']."'>";
            echo "<input type='hidden' name = 'hoa1' value='".$potentialMember1['hoa']."'>";
             echo "<input type='hidden' name = 'directorylist1' value='".$potentialMember1['directorylist']."'>";
            echo "<input type='hidden' name = 'fulltime1' value='".$potentialMember1['fulltime']."'>";
            echo "<input type='hidden' name = 'role1' value='".$roledefault."'>";
            echo "<input type='hidden' name='initPass1'  value='".$passdefault."' >";
            echo "<input type='hidden' name='initPass21'  value='".$passdefault."'>";    
       

            if (count($potentialMember2) > 0) {
          
                $userdefault = ucfirst($potentialMember2['firstname']).ucfirst(substr($potentialMember2['lastname'],0,1));
        
                echo "<input type='hidden' name='firstname2' value='".$potentialMember2['firstname']."'>";
                echo "<input type='hidden' name='lastname2' value='".$potentialMember2['lastname']."'>";
                echo "<input type='hidden' name='email2' value='".$potentialMember2['email']."'>";
                echo "<input type='hidden' name='username2' value='".$userdefault."'>";
                echo "<input type='hidden' name='phone12' value='".$potentialMember2['phone1']."'>";
                echo "<input type='hidden' name='streetaddress2' value='".$potentialMember2['streetaddress']."'>";
                echo "<input type='hidden' name='city2' value='".$potentialMember2['city']."'>";
                echo "<input type='hidden' name='state2' value='".$potentialMember2['state']."'>";
                echo "<input type='hidden' name='zip2' value='".$potentialMember2['zip']."'>";
                echo "<input type='hidden' name = 'hoa2' value='".$potentialMember2['hoa']."'>";
                echo "<input type='hidden' name = 'fulltime2' value='".$potentialMember2['fulltime']."'>";
                 echo "<input type='hidden' name = 'directorylist2' value='".$potentialMember2['directorylist']."'>";
                echo "<input type='hidden' name = 'role2' value='".$roledefault."'>";
                echo "<input type='hidden' name='initPass2'  value='".$passdefault."' >";
                echo "<input type='hidden' name='initPass22'  value='".$passdefault."'>";
   
            }
            echo "</form>";
            echo "<script>";
            echo 'function submitForm1() {
                let form1 = document.getElementById("hiddenForm1");
                form1.submit();
             
            }';
            echo "</script>";
  
     
       ?>
           <br><br><br>
    <div class="container-section ">
      <h1>You have successfully joined the Saddlebrooke Ballroom Dance Club!</h1>
      <h3>Your payment has been successfully processed, and should show SaddleBrooke Ballroom Dance Club on your statement</h3>

      <h3 style="color: red"><em>BUT THERE'S ONE MORE STEP REQUIRED: </em></h3>
      <h3>You MUST click the button below to finish membership setup for yourself and your partner if you have one</h3>
      
      <h5>Your Password will initally be "test1234" and your userid will be the email you joined with or</h5>
      <h5>Your first name with the first inital capitalized, followed by the first inital of your last name capitalized.</h5>
      <h3>The process generates emails, and will take a few moments. Please be patient while we complete the setup.</h3>
      <br>
      <h3>You will be returned to the home page when the setup is complete.</h3>
      <br>
      <button><a href="#" onclick="submitForm1()">Click Me to Finish Signing Up! I'll go set your membership(s) for the website!</a>         
        </button> 
       
              <br><br><br>
    </div>

    </section>
</body>
</html>