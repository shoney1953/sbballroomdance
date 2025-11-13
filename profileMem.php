<?php
$sess = session_start();

require_once 'config/Database.php';

require_once 'models/User.php';
require_once 'models/MemberPaid.php';
date_default_timezone_set("America/Phoenix");

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

$classRegs = [];
$pclassRegs = [];
$eventRegs = [];
$peventRegs = [];

$numEvents = 0;
$pnumEvents = 0;
$numClasses = 0;
$pnumClasses = 0;
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

/* get class registrations */

$memberPaid = new MemberPaid($db);
$yearsPaid = [];

$result = $memberPaid->read_byUserid($_SESSION['userid']);

$rowCount = $result->rowCount();


if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paid_item = array(
            'id' => $id,
            'paid' => $paid,
            'year' => $year,
            'paidonline' => $paidonline

        );
        array_push($yearsPaid, $paid_item);

    }
} 
$pyearsPaid = [];

$result = $memberPaid->read_byUserid($partner->id);

$rowCount = $result->rowCount();


if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paid_item = array(
            'id' => $id,
            'paid' => $paid,
            'paidonline' => $paidonline,
            'year' => $year

        );
        array_push($pyearsPaid, $paid_item);

    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC - Profile Membership Status</title>
</head>
<body>

  
<nav class="nav">
    <div class="container">     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="yourProfile.php">Back to Your Profile</a></li>

    </ul>
    </div>
</nav> 


<div class="container-section" >
<div class="content">
    <br><br>
    <h3>Membership Status</h3>
    <?php
    echo '<section id="membership" class="content">';
    echo '<div class="form-grid3">';
    echo "<div class='form-grid-div'>";

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=3 style="text-align: center">Membership Status</th>';
    echo '</tr>';
    echo "<tr>";
    echo "<td>YEAR</td>";
    echo "<td>PAID?</td>";
    echo "<td>PAID ONLINE?</td>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($yearsPaid as $year) {
        echo "<tr>";
        echo "<td>".$year['year']."</td>";
        
        if ($year['paid'] == true ) {
            echo "<td>&#10004;</td>"; 
          } else {
              echo "<td>&times;</td>"; 
          }  
         if ($year['paidonline'] == true ) {
            echo "<td>&#10004;</td>"; 
          } else {
              echo "<td>&times;</td>"; 
          }  
        echo "</tr>";
    }
    echo "</tbody>";
    echo '</table>';
  
    echo '</div>';
    if ($user->partnerId > 0) {
        echo "<div class='form-grid-div'>";

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=3 style="text-align: center">Partner Membership Status</th>';
        echo '</tr>';
        echo "<tr>";
        echo "<td>YEAR</td>";
        echo "<td>PAID?</td>";
        echo "<td>PAID ONLINE?</td>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($pyearsPaid as $year) {
            echo "<tr>";
            echo "<td>".$year['year']."</td>";
            
            if ($year['paid'] == true ) {
                echo "<td>&#10004;</td>"; 
            } else {
                echo "<td>&times;</td>"; 
            }  
            if ($year['paidonline'] == true ) {
                echo "<td>&#10004;</td>"; 
            } else {
                echo "<td>&times;</td>"; 
            }  
            echo "</tr>";
        }
        echo "</tbody>";
        echo '</table>';
    
        echo '</div>';
    }
    echo "<div class='form-grid-div'>";

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=1 style="text-align: center">Membership Form</th>';
    echo '</tr>';
    echo "<tr>";
    echo "<td>FORM</td>";
 
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    echo "<tr>";
    echo '<td><a href="'.$user->regformlink.'">VIEW REGISTRATION FORM</a></td>';
    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo '</div>';

    // echo '</div>';
    echo '<br><br>';
    ?>
        </section>
    <footer >
    </div>
<?php
  require 'footer.php';
?>
</body>
</html>