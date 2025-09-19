<?php
$classsArch = [];

if ($csvClass) {
  foreach ($allClasses as $class) {
    $cvChk = 'cv'.$class['id'];
    if (isset($_POST["$cvChk"])) {
      echo "<h4>Generated CSV for  ".$class['classname']."  ".$class['date']."</h4>";
      echo "<form  name='csvClassForm'   method='POST' action='csvClass.php'> ";
      echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
      echo '<script language="JavaScript">document.csvClassForm.submit();</script></form>';
      break;
     }
    }
}
if ($reportClass) {

  foreach ($allClasses as $class) {

    $rpChk = 'rp'.$class['id'];

    if (isset($_POST["$rpChk"])) {
      echo "<h4>Generated Report for  ".$class['classname']."  ".$class['date']."</h4>";
      echo "<form  name='reportClassForm'   method='POST' action='reportClass.php'> ";
      echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
      echo '<script language="JavaScript">document.reportClassForm.submit();</script></form>';
      break;
    }
     
  }
}
  if ($emailClass) {
 

    foreach ($allClasses as $class) {
  
      $classNum = (int)substr($emChk,2);

      if ($class['id'] == $classNum) {
        echo "<h4>Emailing registrants for  ".$class['classname']."  ".$class['date']."</h4>";
        echo '<form method="POST" action="emailClass.php"> ';
        echo '<div class="form-grid-div">';
        echo "<input type='hidden' name='classId' value='".$class['id']."'>"; 
       
        echo '<label for="replyEmail">Email to reply to: </label>';
        echo '<input type="email" name="replyEmail" value="'.$_SESSION['useremail'].'"><br>';  

        echo '<label for="emailBody">Email Text</label><br>';
        echo '<textarea  name="emailBody" rows="30" cols="100"></textarea><br>';
      
        echo '<br>';
        echo '<button type="submit" name="submitClassEmail">Send Email</button> ';  
        echo '</div> ';  

        echo '</form>';
      
        break;
      }
      
      
    }
  }
  if ($updateClass) {
   
    echo '<div class="form-container">';
    echo '<form method="POST" action="updateClass.php">';
    echo "<h4 class='form-title'>Updating Selected Classes</h4>";
   
    foreach ($allClasses as $class) {
   
      $upChk = "up".$class['id'];
      if (isset($_POST["$upChk"])) {
       $clSelectChk = "clselect".$class['id'];
       $clnamID = "clnam".$class['id'];
       $cllevelID = "cllevel".$class['id'];
       $clnotesID = "clnotes".$class['id'];
       $clroomID = "clroom".$class['id'];
       $cldateID = "cldate".$class['id'];
       $cldate2ID = "cldate2".$class['id'];
       $cldate3ID = "cldate3".$class['id'];
       $cldate4ID = "cldate4".$class['id'];
       $cldate5ID = "cldate5".$class['id'];
       $cldate6ID = "cldate6".$class['id'];
       $cldate7ID = "cldate7".$class['id'];
       $cldate8ID = "cldate8".$class['id'];
       $cldate9ID = "cldate9".$class['id'];
       $cltimeID = "cltime".$class['id'];
       $clinstructorsID = "clinstructors".$class['id'];
       $clregemailID = "clregemail".$class['id'];
       $clnumregID = "clnumreg".$class['id'];
       $cllimitID = "cllimit".$class['id'];
       $clidID = "clid".$class['id'];
       echo '<div class="form-grid">';
       echo '<div class="form-item">';
       echo "<h4 class='form-item-title'>Update?</h4>";
          echo "<input type='checkbox' name='".$clSelectChk."' title='Check this box to update'>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Name</h4>";
          echo "<input type='text' name='".$clnamID."' value='".$class['classname']."' 
                title='Enter the Name of the Class'>";
          echo '</div>';
          echo '<div class="form-item">';
           echo "<h4 class='form-item-title'>Class Level</h4>";
           echo "<select  title='Select the Level of Class' name = '".$cllevelID."'> ";
   
             if ($class['classlevel'] == 'Novice') {
              echo '<option value = "Novice" selected>Novice </option>';
          } else {
              echo '<option value = "Novice">Novice </option>';
          }

           if ($class['classlevel'] == 'Level 2') {
              echo '<option value = "Level 2" selected>Level 2 </option>';
          } else {
              echo '<option value = "Level 2">Level 2 </option>';
          }
         if ($class['classlevel'] == 'Level 3') {
              echo '<option value = "Level 3" selected>Level 3 </option>';
          } else {
              echo '<option value = "Level 3">Level 3 </option>';
          }
          if ($class['classlevel'] == 'Beginner') {
              echo '<option value = "Beginner" selected>Beginner </option>';
          } else {
              echo '<option value = "Beginner">Beginner </option>';
          }

          if ($class['classlevel'] == 'Beginner Plus') {
              echo '<option value = "Beginner Plus" selected>Beginner Plus </option>';
          } else {
              echo '<option value = "Beginner Plus">Beginner Plus </option>';
          }
          if ($class['classlevel'] == "Intermediate") {
              echo '<option value = "Intermediate" selected>Intermediate</option>';
          } else {
              echo '<option value = "Intermediate">Intermediate</option>';  
          }
          if ($class['classlevel'] == "Advanced") {
              echo '<option value = "Advanced" selected>Advanced</option>';
          } else {
              echo '<option value = "Advanced">Advanced</option>';
          }
  
          echo " </select>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Instructors</h4>";
          echo "<input type='text' name='".$clinstructorsID."' value='".$class['instructors']."' 
          title='Enter the Instructor(s) Name(s)' >";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Registration Email</h4>";
          echo "<input type='email' name='".$clregemailID."' value='".$class['registrationemail']."' 
          title='Enter the Registration Email' >";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Time</h4>";   
          echo "<input type='time' name='".$cltimeID."' value='".$class['time2']."' 
              title='Select the Start Time of the Class' >";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Room</h4>";
          echo "<input type='text' name='".$clroomID."' value='".$class['room']."' 
              title='Enter the Room Where the Class will Occur'>";
          echo '</div>';

          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Limit</h4>";
          echo "<input type='number' class='number-small' name='".$cllimitID."' 
                value='".$class['classlimit']."' title='Enter the Class Limit'>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class # Reg</h4>";
          echo "<input type='number' class='number-small' name='".$clnumregID."' 
                value='".$class['numregistered']."' title='Update the Number Registered'>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Notes</h4>";
          echo "<textarea title='Enter Notes About the Class' name='".$clnotesID."' cols='100' rows='3' >".$class['classnotes']."</textarea>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Start Date</h4>";
          echo "<input type='date' name='".$cldateID."' value='".$class['date']."' 
              title='Select the Start Date of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 2</h4>";
          echo "<input type='date' name='".$cldate2ID."' value='".$class['date2']."' 
              title='Select the Date 2 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 3</h4>";
          echo "<input type='date' name='".$cldate3ID."' value='".$class['date3']."' 
              title='Select the Date 3 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 4</h4>";
          echo "<input type='date' name='".$cldate4ID."' value='".$class['date4']."' 
              title='Select the Date 4 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 5</h4>";
          echo "<input type='date' name='".$cldate5ID."' value='".$class['date5']."' 
              title='Select the Date 5 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 6</h4>";
          echo "<input type='date' name='".$cldate6ID."' value='".$class['date6']."' 
              title='Select the Date 6 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 7</h4>";
          echo "<input type='date' name='".$cldate7ID."' value='".$class['date7']."' 
              title='Select the Date 7 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 8</h4>";
          echo "<input type='date' name='".$cldate8ID."' value='".$class['date8']."' 
              title='Select the Date 8 of the Class' >";  
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 9</h4>";
          echo "<input type='date' name='".$cldate9ID."' value='".$class['date9']."' 
              title='Select the Date 9 of the Class' >";  
          echo '</div>';
          echo "<input type='hidden' name='".$clidID."' value='".$class['id']."'>";
          echo '</div>';
      }
  
}
 
echo '<button type="submit" name="submitUpdate">Update the Class(s)</button><br>';
echo '</form>';
echo '</div>';
  }
if ($deleteClass) {

  echo "<h4>Deleting Selected Classes and Their Associated Registrations</h4>";
  echo '<form method="POST" action="deleteClass.php">';
  echo '<table>' ;
  echo '<thead>';
  echo '<tr>';
  echo '<th>Delete</th>';
  echo '<th>Name</th>';  
  echo '<th>Level</th>';
  echo '<th>Instructors</th>';
  echo '<th>Date</th>';
  echo '<th>Time</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  foreach ($allClasses as $class) {
    $dlChk = "dl".$class['id'];
    if (isset($_POST["$dlChk"])) {
     $clSelectChk = "clselect".$class['id'];
     $clidID = "clid".$class['id'];
     
        echo '<tr>';
        echo "<td><input type='checkbox' name='".$clSelectChk."' title='Check this box to delete'></td>";
        echo "<td>".$class['classname']."</td>";
        echo "<td>".$class['classlevel']."</td>";
        echo "<td>".$class['instructors']."</td>";
        echo "<td>".$class['date']."</td>";
        echo "<td>".$class['time']."</td>";


        echo "<input type='hidden' name='".$clidID."' value='".$class['id']."'>";
        echo '</tr>';
    }

}
echo '</tbody>';
echo '</table>';  
echo '<button type="submit" name="submitDelete">Delete the Class(s)</button><br>';
echo '</form>';

  }


  if ($archiveClass) {
  
    echo "<h4>Archiving Selected Classes and Their Associated Registrations</h4>";
    echo '<form method="POST" action="archiveClass.php">';
    echo '<table>' ;
    echo '<thead>';
    echo '<tr>';
    echo '<th>Archive</th>';
    echo '<th>Name</th>';  
    echo '<th>Level</th>';
    echo '<th>Instructors</th>';
    echo '<th>Date</th>';
;   echo '<th>Time</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
  
    foreach ($allClasses as $class) {
      $aeChk = "ae".$class['id'];
      if (isset($_POST["$aeChk"])) {
       $clSelectChk = "clselect".$class['id'];
       $clidID = "clid".$class['id'];
       
          echo '<tr>';
          echo "<td><input type='checkbox' name='".$clSelectChk."' title='Check this box to Archive'></td>";
          echo "<td>".$class['classname']."</td>";
          echo "<td>".$class['classlevel']."</td>";
          echo "<td>".$class['instructors']."</td>";
          echo "<td>".$class['date']."</td>";
          echo "<td>".$class['time']."</td>";
          echo "<input type='hidden' name='".$clidID."' value='".$class['id']."'>";
          echo '</tr>';
   
          }
        }
      
      echo '</tbody>';
      echo '</table><br>';
      echo '<button type="submit" name="submitArchive">Archive these Class(es) and their registrations</button><br>';
      echo '</form>';
      echo '<br>';
  }
  if ($duplicateClass) {
   
   
    echo '<div class="form-container">';
    echo "<h4 class='form-title'>Duplicate this Class</h4>";
    echo '<form method="POST" action="addClass.php">';
    echo '<div class="form-grid">';
    foreach ($allClasses as $class) {
   
      $dpChk = "dp".$class['id'];
      if (isset($_POST["$dpChk"])) {
  
       $clSelectChk = "clselect".$class['id'];
       $clnamID = "clnam".$class['id'];
       $cllevelID = "cllevel".$class['id'];
       $clnotesID = "clnotes".$class['id'];
       $clroomID = "clroom".$class['id'];
       $cldateID = "cldate".$class['id'];
       $cltimeID = "cltime".$class['id'];
       $clinstructorsID = "clinstructors".$class['id'];
       $clregemailID = "clregemail".$class['id'];
       $clnumregID = "clnumreg".$class['id'];
       $cllimitID = "cllimit".$class['id'];
       $clidID = "clid".$class['id'];
       $cldate2ID = "cldate2".$class['id'];
       $cldate3ID = "cldate3".$class['id'];
       $cldate4ID = "cldate4".$class['id'];
       $cldate5ID = "cldate5".$class['id'];
       $cldate6ID = "cldate6".$class['id'];
       $cldate7ID = "cldate7".$class['id'];
       $cldate8ID = "cldate8".$class['id'];
       $cldate9ID = "cldate9".$class['id'];
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Duplicate?</h4>";
          echo "<input type='checkbox' name='".$clSelectChk."'>"; 
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Name</h4>";
          echo "<input type='text' name='".$clnamID."' value='".$class['classname']."' 
                title='Enter the Name of the Class'>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Level</h4>";
          echo "<select  title='Select the Level of Class' name = '".$cllevelID."'> ";
       
          if ($class['classlevel'] == 'Novice') {
              echo '<option value = "Novice" selected>Novice </option>';
          } else {
              echo '<option value = "Novice">Novice </option>';
          }
       
           if ($class['classlevel'] == 'Level 2') {
              echo '<option value = "Level 2" selected>Level 2 </option>';
          } else {
              echo '<option value = "Level 2">Level 2 </option>';
          }
          if ($class['classlevel'] == 'Level 3') {
              echo '<option value = "Level 3" selected>Level 3 </option>';
          } else {
              echo '<option value = "Level 3">Level 3 </option>';
          }
          if ($class['classlevel'] == 'Beginner') {
              echo '<option value = "Beginner" selected>Beginner </option>';
          } else {
              echo '<option value = "Beginner">Beginner </option>';
          }
          if ($class['classlevel'] == 'Beginner Plus') {
              echo '<option value = "Beginner Plus" selected>Beginner Plus </option>';
          } else {
              echo '<option value = "Beginner Plus">Beginner Plus </option>';
          }
          if ($class['classlevel'] == "Intermediate") {
              echo '<option value = "Intermediate" selected>Intermediate</option>';
          } else {
              echo '<option value = "Intermediate">Intermediate</option>';  
          }
          if ($class['classlevel'] == "Advanced") {
              echo '<option value = "Advanced" selected>Advanced</option>';
          } else {
              echo '<option value = "Advanced">Advanced</option>';
          }
  
          echo " </select>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Instructor(s)</h4>";
 
          echo "<input type='text' name='".$clinstructorsID."' value='".$class['instructors']."' 
          title='Enter the Instructor(s) Name(s)' >";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Registration Email</h4>";
          echo "<input type='email' name='".$clregemailID."' value='".$class['registrationemail']."' 
          title='Enter the Registration Email' >";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Time</h4>"; 
          echo "<input type='time' name='".$cltimeID."' value='".$class['time2']."' 
              title='Select the Start Time of the Class' >";
        echo '</div>';

          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Room</h4>";
          echo "<input type='text' name='".$clroomID."' value='".$class['room']."' 
              title='Enter the Room Where the Class will Occur'>";
          echo '</div>';

          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class Limit</h4>";
          echo "<input type='number' class='number-small' name='".$cllimitID."' 
                value='".$class['classlimit']."' title='Enter the Class Limit'>";
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Class #Registered</h4>";
          echo "<input type='number' class='number-small' name='".$clnumregID."' 
                value='".$class['numregistered']."' title='Update the Number Registered'>";
          echo '</div>';
            echo '<div class="form-item">';
            echo "<h4 class='form-item-title'>Class Notes</h4>";
          echo "<textarea title='Enter Notes About the Class' name='".$clnotesID."' cols='100' rows='3' >".$class['classnotes']."</textarea>";
          echo '</div>';
     
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Start Date</h4>";
          echo "<input type='date' name='".$cldateID."' value='".$class['date']."' 
              title='Select the Start Date of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 2</h4>";
          echo "<input type='date' name='".$cldate2ID."' value='".$class['date2']."' 
              title='Select the Date 2 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 3</h4>";
          echo "<input type='date' name='".$cldate3ID."' value='".$class['date3']."' 
              title='Select the Date 3 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 4</h4>";
          echo "<input type='date' name='".$cldate4ID."' value='".$class['date4']."' 
              title='Select the Date 4 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 5</h4>";
          echo "<input type='date' name='".$cldate5ID."' value='".$class['date5']."' 
              title='Select the Date 5 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 6</h4>";
          echo "<input type='date' name='".$cldate6ID."' value='".$class['date6']."' 
              title='Select the Date 6 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 7</h4>";
          echo "<input type='date' name='".$cldate7ID."' value='".$class['date7']."' 
              title='Select the Date 7 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 8</h4>";
          echo "<input type='date' name='".$cldate8ID."' value='".$class['date8']."' 
              title='Select the Date 8 of the Class' >";    
          echo '</div>';
          echo '<div class="form-item">';
          echo "<h4 class='form-item-title'>Date 9</h4>";
          echo "<input type='date' name='".$cldate9ID."' value='".$class['date9']."' 
              title='Select the Date 9 of the Class' >";    
          echo '</div>';
          echo "<input type='hidden' name='".$clidID."' value='".$class['id']."'>";
          
          echo '</div>';
              break;

      }
     
      }
      echo '<button type="submit" name="submitAdd">Add a New Class</button><br>';
      echo '</form>';
      echo '</div>';
    }   

    // 
  // $redirect = "Location: ".$_SESSION['adminurl']."#events";
  //   header($redirect);
  //   exit;
 

?>