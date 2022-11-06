<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username'])) {

    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} 


if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'visitor') {
        $redirect = "Location: ".$_SESSION['homeurl'];
         header($redirect);

}}

$database = new Database();
$db = $database->connect();
$user = new User($db);
$users = [];
$num_users = 0;
if (isset($_POST['searchUser'])) {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $search .= '%';
        $user = new User($db);
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
                    'hoa' => $hoa,
                    'passwordChanged' => $passwordChanged,
                    'streetAddress' => $streetaddress,
                    'lastLogin' => $lastLogin,
                    'directorylist' => $directorylist
                );
                if ($user_item['directorylist']) {
                    array_push( $users, $user_item);  
                }
          
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
    <title>SBDC Ballroom Dance - Selected Members</title>
</head>
<body>

    <div class="section-back">
    <section id="selectedMembers" class="container content">
   
      <br>
      <?php
      if ($num_users > 0 ) {
      echo '<table>';
        echo '<tr>';
             
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                echo '<th>Address</th>';
                echo '</tr>';
                
        
                foreach($users as $user) {
    
        
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['streetAddress']."</td>"; 
        
                        
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