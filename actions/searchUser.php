<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';
date_default_timezone_set("America/Phoenix");

if (!isset($_SESSION['username'])) {

    $redirect = "Location: ".$_SESSION['homeurl'];
    header($redirect);
} else {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] != 'SUPERADMIN') {
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
                    'phone2' => $phone2,
                    'password' => $password,
                    'partnerId' => $partnerid,
                    'hoa' => $hoa,
                    'passwordChanged' => $passwordChanged,
                    'streetAddress' => $streetaddress,
                    'city' => $city,
                    'state' => $state,
                    'zip' => $zip,
                    'notes' => $notes,
                    'lastLogin' => date('m d Y h:i:s A', strtotime($lastLogin)),
                    'numlogins' => $numlogins,
                    'directorylist' => $directorylist,
                    'fulltime' => $fulltime
                );
                array_push( $users, $user_item);
          
            }
            $_SESSION['process_users'] = $users;
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
<nav class="nav">
    <div class="container">
        
     <ul> 
    <li><a href="../administration.php">Back to Administration</a></li>
     </ul>
    </div>
</nav>
    <div class="section-back">
    <section id="selectedMembers" class="container content">
        <h2>Selected Users</h2>
      <br>
      <h4> Please check only 1 action per event.</h4>
  
      <?php

      if ($num_users > 0 ) {
      echo '<form method="POST" action="processUsers.php">';
      echo '<table>';
      echo '<thead>';
        echo '<tr>';
                echo '<th>Update</th>';
                echo '<th>Archive</th>';
                echo '<th>ID</th>';  
                echo '<th>First Name</th>';  
                echo '<th>Last Name</th>';
                echo '<th>User Name    </th>';
                echo '<th>Role</th>'; 
                echo '<th>Part ID</th>';
                echo '<th>Email</th>';  
                echo '<th>Phone</th>';
                echo '<th>HOA</th>';
                echo '<th>Fulltime</th>';
                echo '<th>Address</th>';
                echo '<th>Directory</th>';

                echo '</tr>';
           echo '</thead>'   ;
           echo '<tbody>' ; 
        
                foreach($users as $user) {
                    $hr = '../member.php?id=';
                    $hr .= $user["id"];
                    $upChk = "up".$user['id'];
                    $arChk = "ar".$user['id'];
                    echo "<td><input type='checkbox' name='".$upChk."'>";
                    echo "<td><input type='checkbox' name='".$arChk."'>";
                    echo '<td> <a href="'.$hr.'">'.$user["id"].'</a></td>';
             
              
                        echo "<td>".$user['firstname']."</td>";               
                        echo "<td>".$user['lastname']."</td>";
                        echo "<td>".$user['username']."</td>";
                        echo "<td>".$user['role']."</td>"; 
                        echo "<td>".$user['partnerId']."</td>"; 
                        echo "<td>".$user['email']."</td>";
                        echo "<td>".$user['phone1']."</td>";
                        echo "<td>".$user['hoa']."</td>";
                        if ($user['fulltime']) {
                            echo "<td>Yes</td>"; 
                        } else {
                            echo "<td>No</td>"; 
                        }
                        echo "<td>".$user['streetAddress']."</td>"; 
                        if ($user['directorylist']) {
                            echo "<td>Yes</td>"; 
                        } else {
                            echo "<td>No</td>"; 
                        }

                       
                        
                      echo "</tr>";
                  }
             
            echo '</tbody>' ;     
            echo '</table><br>'; 
            echo '<button type="submit" name="submitUserProcess">Process Users</button>';  
            echo '</form>';
                } else {
                    echo '<h4>No matching members found.</h4>';
                }  
            ?>   
    </section>
    </div>
</body>