<?php
if ($addReg) {
    echo '<h2>Add Either Member <em>OR</em> Visitor Registrations to the following Class.</h2>';
 
  
        echo '<div class="form-grid-div">';
        echo '<form method="POST" action="addClassReg.php">';
       
        foreach ($allClasses as $class) {
          
            $classNum = (int)substr($arChk,2);
            if ($class['id'] == $classNum) {
                break;
            }
        }
        echo '<input type=hidden name="classid" value="'.$classNum.'">';

        echo '<table>';
        echo '<thead>';
       
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Class Name</th>';
        echo '<th>Class Level</th>';
        echo '<th>Start Date</th>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';
        echo '<tr>';
        echo "<td>".$class['id']."</td>";
        echo "<td>".$class['classname']."</td>";
        echo "<td>".$class['classlevel']."</td>";
        echo "<td>".$class['date']."</td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '';
        echo '<div class="form-grid2">';
        echo '<div class="form-grid-div">'; 
    echo '<br><br><table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan=6>Add Member Registrations</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th colspan=6>Select One or all of the following members</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Add</th>';
    
    echo '<th>First Name</th>';
    echo '<th>Last Name</th>';
    echo '<th>Email</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($users as $usr) {
  
        $usrID = "us".$usr['id'];
     
        echo '<tr>';
        echo "<td><input  title='Select to Add Registrations' type='checkbox'name='".$usrID."'></td>";
       
        echo "<td >".$usr['firstname']."</td>";
        echo "<td>".$usr['lastname']."</td>";
        echo "<td>".$usr['email']."</td>";
        echo '</tr>';

     }
     echo '</tbody>';
     echo '</table>';
 
        echo '<button type="submit" name="submitAddReg">
           Add the Class Registration</button><br>';
        echo '</form>';
        echo '</div>';

        echo '<div class="form-grid-div">';
        echo '<br><br>';
        echo '<form method="POST" action="addVisitorClassReg.php">';
        echo "<input type='hidden' name='classid' value='".$class['id']."'>";
        echo '<table>';   
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=5>Add Visitor Registration</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>First Name</td>';
        echo '<th>Last Name</td>';
        echo '<th>Email</td>';
    
        echo '<th>Notes</td>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname1'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname1'></td>";
        echo "<td><input type='email' name='email1' required></td>";
     
        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes1' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
        echo '<tr>';
        echo "<td><input title='Enter Visitor First Name' type='text' name='firstname2'></td>";
        echo "<td><input title='Enter Visitor Last Name' type='text' name='lastname2'></td>";
        echo "<td><input type='email' name='email2' ></td>";
       
        echo "<td> <textarea  title='Enter any notes about the visitor registration' name='notes2' rows='5' cols='50'></textarea></td>";
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    
        echo '<button type="submit" name="submitAddVisitorReg">Add Visitor Registration</button> ';
        echo '</form>'; 
        echo '</div>';
        echo '</div>';

     }
    
     if ($deleteReg) {
   
        echo '<div class="form-grid-div">';
        
            echo '<form method="POST" action="deleteClassReg.php">';
            foreach ($allClasses as $class) {
                $classNum = (int)substr($drChk,2);
                if ($class['id'] == $classNum) {
                    break;
                }
            }
            echo '<h2>Delete registrations to the following event</h2>';
            echo '<input type=hidden name="classid" value="'.$classNum.'">';
    
            echo '<table>';
            echo '<thead>';
           
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Class Name</th>';
            echo '<th>Class Level</th>';
            echo '<th>Start Date</th>';
            echo '</tr>';
            echo '</thead>';
    
            echo '<tbody>';
            echo '<tr>';
            echo "<td>".$class['id']."</td>";
            echo "<td>".$class['classname']."</td>";
            echo "<td>".$class['classlevel']."</td>";
            echo "<td>".$class['date']."</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
    
        echo '<br><br><table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=6>Select One or all of the following registrations.</th>';
        echo '</tr>';
        echo '<tr>'; 
        echo '<th>Delete</th>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Email</th>';
        echo '<th>Userid</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($regs as $reg) {
  
            $delID = "del".$reg['id'];

            echo '<tr>';
            echo "<td><input title='Select to Delete Registration' type='checkbox' name='".$delID."'></td>";
     
            echo "<td>".$reg['firstname']."</td>";
            echo "<td>".$reg['lastname']."</td>";
            echo "<td>".$reg['email']."</td>";
            echo "<td>".$reg['userid']."</td>";

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitDeleteReg">Delete the Registration(s)</button><br>';
        echo '</form>';
        echo '</div>';
     }
     if ($updateReg) {
      
        echo '<div class="form-grid-div">';
        
            echo '<form method="POST" action="updateClassReg.php">';
            foreach ($allClasses as $class) {
                $classNum = (int)substr($urChk,2);
                if ($class['id'] == $classNum) {
                    break;
                }
            }
            echo '<h2>Update registrations to the following event</h2>';
            echo '<input type=hidden name="classid" value="'.$classNum.'">';
    
            echo '<table>';
            echo '<thead>';
           
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Class Name</th>';
            echo '<th>Class Type</th>';
            echo '<th>Start Date</th>';
            echo '</tr>';
            echo '</thead>';
    
            echo '<tbody>';
            echo '<tr>';
            echo "<td>".$class['id']."</td>";
            echo "<td>".$class['classname']."</td>";
            echo "<td>".$class['classlevel']."</td>";
            echo "<td>".$class['date']."</td>";
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
    
        echo '<br><br><table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th colspan=7>Select One or all of the following registrations.</th>';
        echo '</tr>';
        echo '<tr>'; 
        echo '<th>Update</th>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Email</th>';
        echo '<th>Userid</th>';

        
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $_SESSION['classRegs'] = $regs;
        foreach ($regs as $reg) {
  
            $updID = "upd".$reg['id'];
            $fnamID = "fnam".$reg['id'];
            $lnamID = "lnam".$reg['id'];
            $emailID = "email".$reg['id'];
            $useridID = "userid".$reg['id'];
    


            echo '<tr>';
            echo "<td><input title='Select to Update Registration' type='checkbox' name='".$updID."'></td>";
     
            echo "<td>";
            echo "<input type='text' title='Registrant First Name' name='".$fnamID."' value='".$reg['firstname']."'>";
            echo "</td>";
            echo "<td>";
            echo "<input type='text' title='Registrant Last Name' name='".$lnamID."' value='".$reg['lastname']."'>";
            echo "</td>";
            echo "<td>";
            echo "<input type='email'  title='Registrant Email'name='".$emailID."' value='".$reg['email']."'>";
            echo '</td>';
          
            echo "<td>";
            echo "<input type='text'  title='Registrant User Id' name='".$useridID."' value='".$reg['userid']."'>";
            echo "</td>";
        
        
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<button type="submit" name="submitUpdateReg">Update the Registration(s)</button><br>';
        echo '</form>';
        echo '</div>';
     }
     ?>