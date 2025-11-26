<?php
session_start();

date_default_timezone_set("America/Phoenix");
require_once 'vendor/autoload.php';
require_once 'config/Database.php';
require_once 'models/PaymentCustomer.php';
require_once 'models/PaymentProduct.php';
if (isset($_SESSION['role'])) {

} else {
      header("Location: https://www.sbballroomdance.com/");
     exit;
}
$_SESSION['paymenthisturl'] = $_SERVER['REQUEST_URI'];
$_SESSION['returnurl'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['username']))
{

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
 
             header($redirect);
            exit;
 
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
 
             header($redirect);
            exit;
 
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
if ($_SERVER['SERVER_NAME'] !== 'localhost') {    
  $YOUR_DOMAIN = 'https://www.sbballroomdance.com';   
   $stripeSecretKey = $_SESSION['prodkey'] ;

}
if ($_SERVER['SERVER_NAME'] === 'localhost') {    
  $YOUR_DOMAIN = 'http://localhost/sbdcballroomdance';  
  $stripeSecretKey = $_SESSION['testkey'] ;
}
\Stripe\Stripe::setApiKey($stripeSecretKey);
// header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);
$lastYear = date('Y', strtotime('+1 year'));
$thisYear = date("Y"); 
$current_month = date('m');
$current_year = date('Y');
$currentDate = new DateTime();
$compareDate = $currentDate->format('Y-m-d');
$compareDateTS = strtotime($compareDate);
$searchCustomers = [];
$_SESSION['searchCustomers'] = [];

if (isset($_POST['searchemail']))  {

  $search = trim($_POST['searchemail']);



 $qstring = 'email~ "'.$search.'"';

       $customers = $stripe->customers->search([
       'query' => $qstring,
      ]);
    
        foreach($customers['data'] as $cust) {
          array_push($searchCustomers, $cust);
        }
   
      if ($customers['has_more']) {
  
      do {
        $customers = $stripe->charges->search([
         'query' => $qstring,
         'page' => $customers['next_page']
          ]);
            foreach($customers['data'] as $cust) {
              array_push($searchCustomers, $cust);
               
            }
         } while ($customers['has_more']);
        }
      $_SESSION['searchCustomers'] = $searchCustomers;

unset($_POST['searchemail']);
      }
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://js.stripe.com/v3/"></script>
    <title>SBDC Stripe Payment History</title>
</head>
<body>

<nav class="nav">
    <div class="container">
     
     <ul>
        <?php
           echo "<li><a href='index.php'>Back to Home</a></li> ";
      
            if ($_SESSION['role'] === 'SUPERADMIN') {  
                echo "<li><a href='administration.php'>Back to Administration</a></li> ";
               echo "<li><a href='payments.php'>Back to Payments</a></li> ";
            }

?>

    </ul>
     </div>
</nav>  
    <br>
   <br><br><br> 
   <div class="container-section ">
   <section class="content">
    <h1>Stripe Payment History</h1>
    <h4><em>Please Note Retrieving Data from Stripe can be quite slow.. please be patient!</em></h4>
      <div class="form-grid2">

       <div class="form-grid-div">
            <form method="POST" action="#">
              <div class='form-grid'>
                <div class="form-item">
                      <h4 class='form-item-title'>Search By Email?</h4>
                    <input type='text'  placeholder="full or partial email"
                          title='Enter Partial or Full Email to Search Transactions' name='searchemail'>     
                 
                </div>
            
                    <button type="submit" name="submitSearchTrans">Search </button>   
                  
              </div>
            </form>
                    <form method="POST" action="actions/processStripeTrans.php">
       
           <table>
            <thead>
              <tr>
                <th>Select?</th>
                <th>Email</th>
                <th>Customer ID </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              foreach ($searchCustomers as $cust) {
                $custCHK = "CCHK".$i;
                $custID = "CCID".$i;
                  $custEmail = "CEMAIL".$i;
                echo '<tr>';
                echo '<td><input type="checkbox" name="'.$custCHK.'"</td>';
                echo '<td>'.$cust['email'].'</td>';
                echo '<td>'.$cust['id'].'</td>';
                echo '<td><input type="hidden" name="'.$custID.'" value="'.$cust['id'].'"><td>';
                echo '<input type="hidden"  name="'.$custEmail.'" value="'.$cust['email'].'">';
                echo '</tr>';
                $i++;
              }

              ?>
              <tr > 
                  <td colspan="5"><button type="submit" name="submitGetTrans">Retrieve Transactions by Email</button>
            </tr>
            </tbody>
           </table>
            </form>
       </div>
    <div class="form-grid-div">
       <form method="POST" action="actions/processStripeTrans.php">

            <div class="form-item">
                      <h4 class='form-item-title'>Search by Date?</h4>
                      <label for="startdate">Beginning Date Range</label>
                    <input type='date'  placeholder="start date"
                          title='starting date for transactions' name='startdate'>     
            
                </div>
                 <div class="form-item">
                    <label for="enddate">Ending Date Range</label>
                    <input type='date'  placeholder="end date"
                          title='ending date for transactions' name='enddate'>     
                    <button type="submit" name="submitGetTransDate">Retrieve Transactions by Date </button>   
                </div>
            </form>
    </div>
 


     
   
   
    
    
   
   </section>
    
   </div>
    <footer >
    <?php
    require 'footer.php';
   ?>
    </footer>
</body>
</html>