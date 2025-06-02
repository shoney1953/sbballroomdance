<?php
$sess = session_start();
require_once 'config/Database.php';
require_once 'models/Options.php';
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username'])) {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
}
 if ($_SESSION['role'] != 'SUPERADMIN') {
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
 }
$_SESSION['optionurl'] = $_SERVER['REQUEST_URI']; 
$upChk = '';
$dpChk = '';

$database = new Database();
$db = $database->connect();
/* get options */
$option_item = [];
$allOptions = [];
$options = new Options($db);
$result = $options->read();

$rowCount = $result->rowCount();

$num_options = $rowCount;

$_SESSION['allOptions'] = [];
if ($rowCount > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
 
        $option_item = array(
            'id' => $id,
            'year' => $year,
            'renewalmonth' => $renewalmonth,
            'discountmonth' => $discountmonth   
        );
        array_push($allOptions, $option_item);

    }
    $_SESSION['allOptions'] = $allOptions;
} 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SBDC ADMIN OPTIONS</title>
</head>
<body>
<div class="profile">
<nav class="nav">
    <div class="container">
     
     <ul>
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="administration.php">Back to Administration</a></li>

    </ul>
     </div>
</nav>  


    <div class="container-section">
    <section id="options" class="content">
   <br><br><br>
       
        <h4>Please have a care changing these options as they control some online payment options.</h4>

    <?php
if ($_SESSION['role'] === 'SUPERADMIN') {


echo '<br>';
echo '<section id="options" class="content">';
echo '<form method="POST" action="actions/processOptions.php">';

   echo '<h3 class="form-title">Maintain Options</h3>';

   foreach($allOptions as $option) {

    $upChk = "up".$option['id'];
    $dpChk = "dp".$option['id'];

    echo '<div class="form-container">';

    echo '<div class="form-grid">';

   echo '<div class="form-item">';
   echo "<h4 class='form-title'>Year: ".$option['year']."</h4>";
   echo '</div>';
   echo '<div class="form-item">';
   echo "<h4 class='form-title'>Renewal Month: ".$option['renewalmonth']."</h4>";
   echo '</div>';
   echo '<div class="form-item">';
   echo "<h4 class='form-title'>Discount Month: ".$option['discountmonth']."</h4>";
   echo '</div>';
   echo '<div class="form-item">';
   echo "<h4 class='form-title'>ID: ".$option['id']."</h4>";
   echo '</div>';

   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Duplicate?</h4>';
   echo "<input type='checkbox' title='Only select 1 event to Duplicate' name='".$dpChk."'>";
   echo '</div>';


   echo '<div class="form-item">';
   echo '<h4 class="form-item-title">Update?</h4>';
   echo "<input type='checkbox' title='Select to Update Event(s)' name='".$upChk."'>";   
   echo '</div>';
    
        echo '<div class="form-item">';
        echo '<button type="submit" name="submitOptionProcess">Process This Option</button>';
        echo '</div>';
         echo '</div>';
         echo '</div>';
    }
   
  

    }
 
echo '</div>';

echo '</form>';
echo '<br>';
echo '</div>';

echo '</section>';
echo '</div>';

   


  include 'footer.php';
?>
</body>
</html>