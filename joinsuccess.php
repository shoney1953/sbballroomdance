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
            echo "<input type='hidden' name='firstname' value='".$potentialMember1['firstname']."'>";
            echo "<input type='hidden' name='lastname' value='".$potentialMember1['lastname']."'>";
            echo "<input type='hidden' name='email' value='".$potentialMember1['email']."'>";
            echo "<input type='hidden' name='username' value='".$userdefault."'>";
            echo "<input type='hidden' name='phone1' value='".$potentialMember1['phone1']."'>";
            echo "<input type='hidden' name='streetaddress' value='".$potentialMember1['streetaddress']."'>";
            echo "<input type='hidden' name='city' value='".$potentialMember1['city']."'>";
            echo "<input type='hidden' name='state' value='".$potentialMember1['state']."'>";
            echo "<input type='hidden' name='zip' value='".$potentialMember1['state']."'>";
            echo "<input type='hidden' name = 'hoa' value='".$potentialMember1['hoa']."'>";
            echo "<input type='hidden' name = 'fulltime' value='".$potentialMember1['fulltime']."'>";
            echo "<input type='hidden' name = 'role' value='".$roledefault."'>";
            echo "<input type='hidden' name='initPass'  value='".$passdefault."' >";
            echo "<input type='hidden' name='initPass2'  value='".$passdefault."'>";
            echo "</form>";

            echo "<script>";
            echo 'function submitForm1() {
                let form1 = document.getElementById("hiddenForm1");
                form1.submit();
             
            }';
            echo "</script>";
            if (count($potentialMember2) > 0) {
                $userdefault = ucfirst($potentialMember2['firstname']).ucfirst(substr($potentialMember2['lastname'],0,1));
                echo "<form method='POST' name='hiddenForm2' id ='hiddenForm2' action='actions/addoUser.php'>";
                echo "<input type='hidden' name='firstname' value='".$potentialMember2['firstname']."'>";
                echo "<input type='hidden' name='lastname' value='".$potentialMember2['lastname']."'>";
                echo "<input type='hidden' name='email' value='".$potentialMember2['email']."'>";
                echo "<input type='hidden' name='username' value='".$userdefault."'>";
                echo "<input type='hidden' name='phone1' value='".$potentialMember2['phone1']."'>";
                echo "<input type='hidden' name='streetaddress' value='".$potentialMember2['streetaddress']."'>";
                echo "<input type='hidden' name='city' value='".$potentialMember2['city']."'>";
                echo "<input type='hidden' name='state' value='".$potentialMember2['state']."'>";
                echo "<input type='hidden' name='zip' value='".$potentialMember2['state']."'>";
                echo "<input type='hidden' name = 'hoa' value='".$potentialMember2['hoa']."'>";
                echo "<input type='hidden' name = 'fulltime' value='".$potentialMember2['fulltime']."'>";
                echo "<input type='hidden' name = 'role' value='".$roledefault."'>";
                echo "<input type='hidden' name='initPass'  value='".$passdefault."' >";
                echo "<input type='hidden' name='initPass2'  value='".$passdefault."'>";
                echo "</form>";
    
                echo "<script>";
                echo 'function submitForm2() {
                    let form2 = document.getElementById("hiddenForm2");
                    form2.submit();
                 
                }';
                echo "</script>";
            }

     
       ?>
           <br><br><br>
    <div class="container-section ">
      <h1>You have successfully joined the Saddlebrooke Ballroom Dance Club!</h1>
      <h3>Your payment has been successfully processed, and should show SaddleBrooke Ballroom Dance Club on your statement</h3>
      <h3>Your Password will initally be "test123" and your userid will be the email you joined with or</h3>
      <h3>Your first name with the first inital capitalized, followed by the first inital of your last name capitalized.</h3>
    
      <h3>You will need to click the button(s) below to finish membership setup for yourself and your partner if you have one</h3>
      <br>
      <h3 ><a href="#" onclick="submitForm1()">Click Me to Finish Signing Up! I'll go set your membership for the website!</a>         
        </h3> 
        <?php
         if (count($potentialMember2) > 0) {
        echo "<br><h3 ><a href='#' onclick='submitForm2()'>Click Me set your Partner's membership for the website!</a></h3>";
         }
         
        ?>
          <h3>Return to the home page to login and start dancing and enjoying the fun.</h3>
              <br><br><br>
    </div>

    </section>
</body>
</html>