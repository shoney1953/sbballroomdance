<?php
session_start();
require_once '../config/Database.php';
require_once '../models/UserArchive.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username'])) {

   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
   if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
        }
    } else {
       if (isset($_SESSION['homeurl'])) {
             $redirect = "Location: ".$_SESSION['homeurl'];
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/index.php';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/index.php';  
            }
           } 
             header($redirect);
            exit;
    }
}

$database = new Database();
$db = $database->connect();
$user = new UserArchive($db);
$users = [];
$num_users = 0;
if (isset($_POST['searchUser'])) {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $search .= '%';
        $user = new UserArchive($db);
        $result = $user->readLike($search);
        
        $rowCount = $result->rowCount();
        $num_users = $rowCount;
  
        if($rowCount > 0) {
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    'id' => $id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'role' => $role,
                    'email' => $email,
                    'phone1' => $phone1,
                    'password' => $password,
                    'partnerId' => $partnerid,
                    'previd' => $previd,
                    'hoa' => $hoa,
                    'passwordChanged' => $passwordChanged,
                    'streetAddress' => $streetaddress,
                    'joinedonline' => $joinedonline,
                    'lastLogin' => $lastLogin
                );
                array_push( $users, $user_item);
          
            }
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
    <title>SBDC Ballroom Dance - Selected Archive Members</title>
</head>
<body>

    <div class="section-back">
    <section id="selectedMembers" class="container content">
   
      <br>
      <?php
      if ($num_users > 0 ) {
      echo '<table>';
        echo '<tr>';
             
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Role</th>'; 
                echo '<th>Part ID</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                echo '<th>HOA</th>';
                echo '<th>Address</th>';
                echo '<th>Last Login</th>';
                echo '<th>PWD Changed</th>';
                echo '<th>Joined Online</th>';
                echo '</tr>';
                
        
                foreach($users as $user) {
                    if ($user['previd'] !== null) {
                        $hr = '../archmember.php?previd=';
                       $hr .= $user["previd"];
                       echo '<td> <a href="'.$hr.'">'.$user["previd"].'</a></td>';
                    } else {
                       $hr = '../archmember.php?id=';
                       $hr .= $user["id"];
                       echo '<td> <a href="'.$hr.'">'.$user["id"].'</a></td>';
                    } 
             

             
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['partnerId']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['hoa']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
                        echo "<td>".substr($user['lastLogin'],0,10)."</td>"; 
                        echo "<td>".substr($user['passwordChanged'],0,10)."</td>"; 
                         echo "<td>".$user['joinedonline']."</td>"; 
                       
                        
                      echo "</tr>";
                  }
             
                
            echo '</table><br>';  
                } else {
                    echo '<h4>No matching members found.</h4>';
                }  
            ?>   
    </section>
    </div>
</body>