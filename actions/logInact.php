<?php
session_start();

require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/MemberPaid.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);
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
$allOptions = $_SESSION['allOptions'];
foreach($allOptions as $option) {
    if ($current_year === $option['year']) {
        $_SESSION['renewalmonth'] = $option['renewalmonth'];
        $_SESSION['discountmonth'] = $option['discountmonth'];

        break;
    }
}
$_SESSION['renewThisYear'] = 0;
$_SESSION['renewNextYear'] = 0;
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

            $redirect = "Location: ".$_SESSION['homeurl'];
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
            $redirect = "Location: ".$_SESSION['loginurl'].'?error=InvalidPassword';
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
      
     $redirect = "Location: ".$_SESSION['loginurl'].'?error=NoUser';
     header($redirect);
     exit;  
    } 
}
    
 


