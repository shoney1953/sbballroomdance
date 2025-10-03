<?php
class PaymentCustomer {
    // DB stuff
    private $conn;
    private $table = 'paymentcustomer';

    public $customerid;
    public $firstname;
    public $lastname;
    public $userid;
    public $email;



    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY customerid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
 public function readByEmail($email) {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' 
      WHERE email LIKE :email
      ORDER BY customerid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      $stmt->bindParam('email', $email);
      // Execute query
      $stmt->execute();

      return $stmt;
    }
    // Get Single Danceclass
    public function read_single($customerid) {
         $this->id = $id;
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->userid = $row['message'];
          $this->email = $row['email'];


    }

    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          customerid = :customerid, userid = :userid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->customerid = htmlspecialchars(strip_tags($this->customerid));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
     

          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':customerid', $this->customerid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':userid', $this->userid);
  
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update the userid in the payment customer file after user is created

        public function updateUserid() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET userid = :userid, 
            WHERE customerid = :customerid ';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

            // Bind data
        $stmt->bindParam('userid', $this->userid);
   
        $stmt->bindParam(':customerid', $this->customerid);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }


    // Delete Danceclass
    public function delete() {
        
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE customerid = :customerid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->customerid = htmlspecialchars(strip_tags($this->customerid));

          // Bind data
          $stmt->bindParam(':customerid', $this->customerid);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
   
    
}
?>