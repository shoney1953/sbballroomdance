<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Contact.php';
date_default_timezone_set("America/Phoenix");
if (isset($_SESSION['role'])) {

} else {
   header("Location: https://www.sbballroomdance.com/");
     exit;
}
if (!isset($_SESSION['username']))
{
    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if (($_SESSION['role'] != 'ADMIN') && ($_SESSION['role'] != 'SUPERADMIN')) {
            $redirect = "Location: ".$_SESSION['homeurl'];
            header($redirect); 
        }
       } else {
        $redirect = "Location: ".$_SESSION['homeurl'];
        header($redirect);
       }
}
$database = new Database();
$db = $database->connect();
$contact = new Contact($db);


$reportContact = false;
$deleteContact = false;

$contacts = $_SESSION['contacts'];;
if (isset($_POST['submitContact'])) {

 
    if(isset($_POST['deleteContact'])) {$deleteContact = $_POST['deleteContact'];}
    

    if ($deleteContact) {
        if(isset($_POST['delContactBefore'])) {
          $delContactDate = htmlentities($_POST['delContactBefore']);
        }
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
    <title>SBDC Ballroom Dance - Contact Administration</title>
</head>
<body>

    <div class="section-back">
    <section id="events" class="container content">
   
      <br>
      <?php 
      
        echo '<h1 class="section-header">Contacts</h1><br>';
        echo '<table>';
        echo '<tr>';
                echo '<th>ID   </th>';  
                echo '<th>Contact Date    </th>';
                echo '<th>First Name    </th>';
                echo '<th>Last Name</th>';
                echo '<th>Message </th>';
       
  
            echo '</tr>';
          foreach($contacts as $contactEnt) {
                echo "<tr>";
                    echo "<td>".$contactEnt['id']."</td>";
                    echo "<td>".$contactEnt['contactdate']."</td>";
                    echo "<td>".$contactEnt['firstname']."</td>";
                    echo "<td>".$contactEnt['lastname']."</td>";
                    echo "<td>".$contactEnt['message']."</td>";


                echo "</tr>";
            }
          
        echo '</table><br>';

        if($deleteContact) {
            echo '<p> You have selected to delete contact with dates prior to: '.$delContactDate.'<br>';
            echo '<br><br><strong><em> Please click the button below to confirm delete.</em></strong></p>';
            echo '<form method="POST" action="deleteContact.php">';
            echo '<input type="hidden" name="delContactBefore" value="'.$delContactDate.'">';
            echo '<button type="submit" name="submitDelete">Delete the Contact</button><br>';
            echo '</form>';
        }

        ?> 
    </section>
    </div>
</body>
</html>

