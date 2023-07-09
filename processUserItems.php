<?php

$users = $_SESSION['process_users'];

  if ($updateUser) {
    echo "<form method='POST' action='updateUser.php'>";
  


     
        foreach ($users as $usr) {

            $upChk = "up".$usr['id'];
            $usrSelChk ="userSel".$usr['id'];
            $fnamID = "fnam".$usr['id'];
            $lnamID = "lnam".$usr['id'];
            $nemailID = "nemail".$usr['id'];
            $nuserID = "nuser".$usr['id'];
            $dlistID = "dlist".$usr['id'];
            $hoaID = "hoa".$usr['id'];
            $phone1ID = "phone1".$usr['id'];
            $phone2ID = "phone2".$usr['id'];
            $staddID = "stadd".$usr['id'];
            $cityID = "city".$usr['id'];
            $stateID = "state".$usr['id'];
            $zipID = "zip".$usr['id'];
            $notesID = "notes".$usr['id'];
            $partID = "part".$usr['id'];
            $emailID = "email".$usr['id'];
            $userID = "user".$usr['id'];
            $idID = "id".$usr['id'];
            $pwdID  = "pwd".$usr['id'];
            $roleID = "role".$usr['id'];
            $rpwdID = "rpwd".$usr['id'];
            $rpwd2ID = "rpwd2".$usr['id'];
            $fullID = "full".$usr['id'];
            if (isset($_POST["$upChk"])) {
                echo '<div class="form-container">';
                echo "<h4 class='form-title'>".$usr['firstname']." ".$usr['lastname']." --   Member ID: ".$usr['id']."</h4>";
                echo '<div class="form-grid">';


            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Update?</h4>';
            echo "<input type='checkbox'  name='".$usrSelChk."'>";
            echo '</div>';
  
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">First Name</h4>';
            echo "<input type='text' name='".$fnamID."' value='".$usr['firstname']."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Last Name</h4>';
            echo "<input type='text' name='".$lnamID."' value='".$usr['lastname']."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Email</h4>';
            echo "<input type='email' name='".$nemailID."' value='".$usr['email']."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">User Name</h4>';
            echo "<input type='text' name='".$nuserID."' value='".$usr['username']."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Directory List?</h4>'; 
            echo "<input type='number' name='".$dlistID."' min='0' max='1' value='".$usr['directorylist']."'>
                <br><small><em> 1 to list or 0 to Remove from Directory</em> </small>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">HOA</h4>'; 
           echo "<select name = '".$hoaID."' value'".$usr['hoa']."'>";
           if ($usr['hoa'] == '1') {
              echo "<option value = '1' selected>HOA 1</option>";
          } else {
              echo "<option value = '1' >HOA 1</option>";
          }
          if ($usr['hoa'] == 2) {
              echo "<option value = '2' selected>HOA 2</option>";
          } else {
              echo "<option value = '2' >HOA 2</option>";
          }
          echo "</select>";
          echo '</div>';
          echo '<div class="form-item">';
          echo '<h4 class="form-item-title">Fulltime?</h4>'; 
         echo "<select name = '".$fullID."' value'".$usr['fulltime']."'>";
         if ($usr['fulltime'] == '1') {
            echo "<option value = '1' selected>Fulltime Resident</option>";
        } else {
            echo "<option value = '1' >Fulltime Resident</option>";
        }
        if ($usr['fulltime'] == 0) {
            echo "<option value = '0' selected>Gone for the Summer</option>";
        } else {
            echo "<option value = '0' >Gone for the Summer</option>";
        }
        echo "</select>";
        echo '</div>';
          echo '<div class="form-item">';
          echo '<h4 class="form-item-title">Primary Phone</h4>'; 
          echo "<input type='tel'  name='".$phone1ID."'
          pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}'
          required value='".$usr['phone1']."'><br>
             <small> Format: 123-456-7890</small>";
          echo '</div>';
          echo '<div class="form-item">';
          echo '<h4 class="form-item-title">Secondary Phone</h4>'; 
          echo "<input type='tel'  name='".$phone2ID."'
             pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}'
             value='".$usr['phone2']."'><br>
                <small> Format: 123-456-7890</small>";
           echo '</div>';
    
           echo '<div class="form-item">';
           echo '<h4 class="form-item-title">Street Address</h4>'; 
           echo "<input type='text' name='".$staddID."' value='".$usr['streetAddress']."'>";
           echo '</div>';
           echo '<div class="form-item">';
           echo '<h4 class="form-item-title">City</h4>'; 
           echo "<input type='text' name='".$cityID."' value='".$usr['city']."'>";
           echo '</div>';
           echo '<div class="form-item">';
           echo '<h4 class="form-item-title">State</h4>'; 
           echo "<input type='text' name='".$stateID."' maxsize='2' value='".$usr['state']."'>";
           echo '</div>';
           echo '<div class="form-item">';
           echo '<h4 class="form-item-title">Zip</h4>'; 
           echo "<input type='text' name='".$zipID."' maxsize='10' value='".$usr['zip']."'>";
           echo '</div>';
           echo '<div class="form-item">';
           echo '<h4 class="form-item-title">Role</h4>'; 
           echo "<select name='".$roleID."'  >";
              if ($usr['role'] === "MEMBER") {
                  echo '<option value = "MEMBER" selected>Normal Member Functions</option>';
              } else {
                  echo '<option value = "MEMBER">Normal Member Functions</option>';
              }
              if ($usr['role'] === "ADMIN") {
                  echo '<option value = "ADMIN" selected>Can Update all but Members</option>';
              } else {
                  echo '<option value = "ADMIN">Can Update all but Members</option>';
              }
              if ($usr['role'] === "SUPERADMIN") {
                  echo '<option value = "SUPERADMIN" selected>Can Update All Tables</option>';
              } else {
                  echo '<option value = "SUPERADMIN">Can Update All Tables</option>';
              }
              if ($usr['role'] === "INSTRUCTOR") {
                  echo '<option value = "INSTRUCTOR" selected>Can Maintain Classes</option>';
              } else {
                  echo '<option value = "INSTRUCTOR">Can Maintain Classes</option>';
              }
              echo '</select>';
            echo '</div>';
           
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Partner ID</h4>'; 
            echo "<input type='number' name='".$partID."' value='".$usr['partnerId']."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Notes</h4>';
            echo "<textarea  name='".$notesID."' rows='2' cols='100'>".$usr['notes']."</textarea>"; 
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">New Password</h4>'; 
            echo "<input type='password' minlength='8' name='".$rpwdID."'>";
            echo '</div>';
            echo '<div class="form-item">';
            echo '<h4 class="form-item-title">Repeat New Password</h4>'; 
            echo "<input type='password' minlength='8' name='".$rpwd2ID."'>";
            echo '</div>';
            echo "<input type='hidden' name='".$idID."' value='".$usr['id']."'>";
            echo "<input type='hidden' name='".$userID."' value='".$usr['username']."'>";
            echo "<input type='hidden' name='".$emailID."' value='".$usr['email']."'>";
            echo "<input type='hidden' name='".$pwdID."' value='".$usr['password']."'>"; 
            echo '</div>'; 
            echo '</div>';
            }
    
        }

        echo '<button type="submit" name="submitUpdateUser">Update the Member(s)</button><br>';
        echo '</form>';
        echo '</div>';
  }

 
  
  

 if ($archiveUser) {
  echo "<h4>Archiving Members</h4>";

 
  echo '<form method="POST" action="deleteUser.php">';
  
  foreach ($users as $usr) {

  $arChk = "ar".$usr['id'];
  $idID = "id".$usr['id'];
  $usrSelChk ="userSel".$usr['id'];
  if (isset($_POST["$arChk"])) {
   
        echo '<div class="form-container">';
        echo "<h4 class='form-title'>".$usr['firstname']." ".$usr['lastname']." --   Member ID: ".$usr['id']."</h4>";
        echo '<div class="form-grid">';


    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Archive?</h4>';
    echo "<input type='checkbox'  name='".$usrSelChk."'>";
    echo '</div>';

    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">First Name</h4>';

    echo "<h4 class='form-item-title'>".$usr['firstname']."</h4>";
    echo '</div>';
    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Last Name</h4>';
    echo "<h4 class='form-item-title'>".$usr['lastname']."</h4>";
    echo '</div>';
    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">Email</h4>';
    echo "<h4 class='form-item-title'>".$usr['email']."</h4>";
    echo '</div>';
    echo '<div class="form-item">';
    echo '<h4 class="form-item-title">User Name</h4>';
    echo "<h4 class='form-item-title'>".$usr['username']."</h4>";
    echo '</div>';
    echo "<input type='hidden' name='".$idID."' value='".$usr['id']."'>";
    echo '</div>';
    echo '</div>';
    }
    
}
echo '<button type="submit" name="submitArchiveUser">Archive the Member(s)</button><br>';
echo '</form>';

echo '</div>';
}
 


?>