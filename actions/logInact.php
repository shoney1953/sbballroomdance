<?php
session_start();
  $_SESSION = array();
date_default_timezone_set("America/Phoenix");
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/Options.php';
require_once '../models/MemberPaid.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);
$partner = new User($db);
$pass2 = '';
$isValid = false;
$current_year = date('Y');
$nextYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 
$current_month = date('m');
$current_year = date('Y');
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$yearsPaid = [];
$nextYearNotThere = 0;
$thisYearNotThere = 0;
$rowCount = 0;
$allOptions = [];

if (!isset($_SESSION['allOptions'])) {
$options = new Options($db);
$result = $options->read();
$rowCount = $result->rowCount();
$num_options = $rowCount;
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
foreach($allOptions as $option) {
    if ($current_year === $option['year']) {
        $_SESSION['renewalmonth'] = $option['renewalmonth'];
        $_SESSION['discountmonth'] = $option['discountmonth'];

        break;
    }
}
}

$_SESSION['renewThisYear'] = 0;
$_SESSION['renewNextYear'] = 0;
if (isset($_SESSION['allOptions'])) {

$allOptions = $_SESSION['allOptions'];
foreach($allOptions as $option) {
    if ($current_year === $option['year']) {
        $_SESSION['renewalmonth'] = $option['renewalmonth'];
        $_SESSION['discountmonth'] = $option['discountmonth'];

        break;
    }
}
}
if (isset($_SESSION['renewalmonth'])) {
   $renewalMonth = $_SESSION['renewalmonth'];

} else {
   $renewalMonth = 11;
}

   if(isset($_POST['SubmitLogIN'])) {
   
    $user->username = htmlentities($_POST['username']);
    $passEntered = htmlentities($_POST['password']);
 
    $user->email = filter_var($user->email, FILTER_SANITIZE_EMAIL);   

    if($user->getUserName($user->username)) {

        if(password_verify($passEntered, $user->password )) {
           
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['userid'] = $user->id;
            $_SESSION['userfirstname'] = $user->firstname;
            $_SESSION['userlastname'] = $user->lastname;
            $_SESSION['useremail'] = $user->email;
            $_SESSION['partnerid'] = $user->partnerId;
    
            if ($user->partnerId !== '0') {
                $partner->id = $user->partnerId;
                $partner->read_single();
                $_SESSION['partnerdietaryrestriction'] = $partner->dietaryrestriction;
                $_SESSION['partnername'] = $partner->username;
                $_SESSION['partnerrole'] = $partner->role;
                $_SESSION['partnerfirstname'] = $partner->firstname;
                $_SESSION['partnerlastname'] = $partner->lastname;
                $_SESSION['partneremail'] = $partner->email;
            } else {
                unset($_SESSION['partnerdietaryrestriction']);
                unset($_SESSION['partnerfirstname']);
                unset($_SESSION['partnerlastname']);
                 unset($_SESSION['partnerrole']);
                 unset($_SESSION['partnername']);
                 unset($_SESSION['partneremail']);
             
            }
            $_SESSION['dietaryrestriction'] = $user->dietaryrestriction;
            $user->numlogins++;
    
            $user->updateLogin();
            $eventReg = new MemberPaid($db);
            $yearsPaid = [];
            
            // get the payment records for this member
            $result = $eventReg->read_byUserid($_SESSION['userid']);
            
            $rowCount = $result->rowCount();    
            if ($rowCount === 0) {
                $_SESSION['renewThisYear'] = 1;
            }
         if ($rowCount > 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $paid_item = array(
                'id' => $id,
                'paid' => $paid,
                'year' => $year

            );
            array_push($yearsPaid, $paid_item);

             }

            foreach($yearsPaid as $paid_item) {
                if ($paid_item['year'] === $nextYear)  {
                    $nextYearNotThere = 1;
                }
                if ($paid_item['year'] === $current_year) {
                     $thisYearNotThere = 1;
                    if ($paid_item['paid'] != 1) {
                   
                         $_SESSION['renewThisYear'] = 1;
                    }
                }
                if ($paid_item['year'] === $nextYear) {

              
                 
                    if ((int)$current_month >= $_SESSION['renewalmonth']) {
           
                        if ($paid_item['paid'] != 1) {
                   
                            $_SESSION['renewNextYear'] = 1;
                       }  
                    }

                }
            }
            if ($thisYearNotThere === 0) {
                 $_SESSION['renewNextYear'] = 0;
                 $_SESSION['renewThisYear'] = 1;
            } else {
              if ($nextYearNotThere === 0) {
                if ((int)$current_month >= $_SESSION['renewalmonth']) {
                       
                            $_SESSION['renewNextYear'] = 1;
                    }

              }
            }

        } 

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
        else {

            if(isset($_SESSION['username'])) {
                unset($_SESSION['username']);
            }
            if(isset($_SESSION['role'])) {
                unset($_SESSION['role']);
            }
            if(isset($_SESSION['userid'])) {
                unset($_SESSION['userid']);
            }
            if(isset($_SESSION['userfirstname'])) {
                unset($_SESSION['userfirstname']);
            }
            if(isset($_SESSION['userlastname'])) {
                unset($_SESSION['userlastname']);
            }
            if(isset($_SESSION['useremail'])) {
                unset($_SESSION['useremail']);
            }
            if(isset($_SESSION['partnerid'])) {
                unset($_SESSION['partnerid']);
            }
            if(isset($_SESSION['dietaryrestriction'])) {
                unset($_SESSION['dietaryrestriction']);
            }
      
             if (isset($_SESSION['loginurl'])) {
             $redirect = "Location: ".$_SESSION['loginurl']."?error='InvalidPassword'";
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/login.php?error=InvalidPassword';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/login.php?error=InvalidPassword';  
            }
           } 
             header($redirect);
            exit;
        

        } 
    } else {
        if(isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
        if(isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        if(isset($_SESSION['userid'])) {
            unset($_SESSION['userid']);
        }
        if(isset($_SESSION['userfirstname'])) {
            unset($_SESSION['userfirstname']);
        }
        if(isset($_SESSION['userlastname'])) {
            unset($_SESSION['userlastname']);
        }
        if(isset($_SESSION['useremail'])) {
            unset($_SESSION['useremail']);
        }
        if(isset($_SESSION['partnerid'])) {
            unset($_SESSION['partnerid']);
        }
        if (isset($_SESSION['loginurl'])) {
             $redirect = "Location: ".$_SESSION['loginurl']."?error='No User Name Found'";
 
           }  else {
            if ($_SERVER['SERVER_NAME'] === 'localhost') {  
                $redirect = 'Location: http://localhost/sbdcballroomdance/login.php?error=No User Name Found';
            }
            else {
                 $redirect = 'Location: https://www.sbballroomdance.com/login.php?error=No User Name found';  
            }
           } 
             header($redirect);
            exit;

    } 
}
    
 


