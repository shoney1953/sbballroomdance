<?php

  if ($updateOption) {

    echo '<form method="POST" action="updateOption.php">';
    echo "<h4 class='form-title'>Updating Selected Options</h4>";
    foreach ($allOptions as $option) {
      $upChk = "up".$option['id'];
      if (isset($_POST["$upChk"])) {
       $opSelectChk = "opselect".$option['id'];
       $opdismoID = "opdismo".$option['id'];
       $oprenmoID = "oprenmo".$option['id'];
       $opidID = "opid".$option['id'];
       echo '<div class="form-container">';
       echo '<div class="form-grid">';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Update?</h4>";
       echo "<input type='checkbox' name='".$opSelectChk."' checked title='Check this box to update'>";
       echo '</div>';

       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Renewal Month</h4>";
       echo "<input type='number' name='".$oprenmoID."' value='".$option['renewalmonth']."' 
             title='Enter the month that renewal reminders begin' min='1' max='12'>";
       echo '</div>';
       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Discount Month</h4>";
       echo "<input type='number' name='".$opdismoID."' value='".$option['discountmonth']."' 
             title='Enter the month that discount membership begins' min='1' max='12'>";
       echo '</div>';
      

      echo "<input type='hidden' name='".$opidID."' value='".$option['id']."'>"; 
 
       echo '</div>'; // end of form grid for each event
       echo '</div>'; // end of form container
      } // end of if isset upchk

    
   
  } // end of for each
  echo '<button type="submit" name="submitUpdate">Update Options</button><br>';
    echo '</form>';
   
  }


  if ($duplicateOption) {

    echo '<div class="form-container">';
    echo '<form method="POST" action="addOption.php">';
    echo "<h4 class='form-title'>Duplicate this option</h4>";
    echo '<div class="form-grid">';
    foreach ($allOptions as $option) {
      $dpChk = "dp".$option['id'];
      if (isset($_POST["$dpChk"])) {
          echo "<div class='form-item'>";
          echo "<h4 class='form-item-title'>Option Year</h4>";
          echo "<input type='year' name='optionyear' value='".$option['year']."' 
                title='Enter the Year for these Options'>";
          echo '</div>';
       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Renewal Month</h4>";
       echo "<input type='numbre' name='renewalmonth' value='".$option['renewalmonth']."' 
             title='Enter the month that renewal reminders begin' min='1' max='12'>";
       echo '</div>';
       echo "<div class='form-item'>";
       echo "<h4 class='form-item-title'>Discount Month</h4>";
       echo "<input type='number' name='discountmonth' value='".$option['discountmonth']."' 
             title='Enter the month that discount membership begins' min='1' max='12'>";
       echo '</div>';
       

          break; // break out of for each loop after 1 found
      }
      
    
}
      echo '</div>'; // end form grid
      echo '<button type="submit" name="submitAdd">Add a New Option Year</button><br>';
      echo '</form>';
      echo '</div>'; // end form container  
  }
    // 
  // $redirect = "Location: ".$_SESSION['adminurl']."#events";
  //   header($redirect);
  //   exit;