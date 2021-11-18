<?php
class User {
    // DB stuff
    private $conn;
    private $table = 'users';

    public $id;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $created;
    public $memberid;
    public $role;
    public $passwordChanged;
    


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY email DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceclass
    public function read_single() {
        
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
          $this->id = $row['id'];
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->username = $row['username'];
          $this->email = $row['email'];
          $this->password = $row['password'];
          $this->created = $row['created'];
          $this->memberid = $row['memberid'];
          $this->memberid = $row['role'];
          $this->passwordChanged = $row['passwordChanged'];


    }
    public function getUserName($user) {
        
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :user OR email = :user LIMIT 0,1'; 

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam('user', $user);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties
      if($row) {
      $this->id = $row['id'];
      $this->firstname = $row['firstname'];
      $this->lastname = $row['lastname'];
      $this->username = $row['username'];
      $this->email = $row['email'];
      $this->password = $row['password'];
      $this->created = $row['created'];
      $this->memberid = $row['memberid'];
      $this->role = $row['role'];
      $this->passwordChanged = $row['passwordChanged'];
  
        return true;
      }
     return false;

}
    public function validate_user($user) {
    
       // Create query
       $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :user or email = :user LIMIT 0,1'; 

       // Prepare statement
       $stmt = $this->conn->prepare($query);

       // Bind ID
       $stmt->bindParam('user', $user);

       // Execute query
       $stmt->execute();

       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row) {
         return true;
       }
      return false;
  
 }
 public function validate_email($email) {
 
   // Create query
   $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1'; 

   // Prepare statement
   $stmt = $this->conn->prepare($query);

   // Bind ID
   $stmt->bindParam('email', $email);

   // Execute query
   $stmt->execute();

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($row) {
     return true;
   }
  return false;


}



    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          username = :username, password = :password ' ;

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->username = htmlspecialchars(strip_tags($this->username));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->password = $this->password;
          
      

          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':username', $this->username);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':password', $this->password);
      
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }


    public function update() {
          // Create query
      
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          username = :username, 
          memberid = :memberid WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->memberid = htmlspecialchars(strip_tags($this->memberid));
          $this->username = htmlspecialchars(strip_tags($this->username));


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':username', $this->username);
        
          $stmt->bindParam(':memberid', $this->memberid);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    public function updatePassword() {
     
      // Create query
      $query = 'UPDATE ' . $this->table .
       ' SET password = :password, passwordChanged = NOW() WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      $this->password = $this->password;

      // Bind data
 
      $stmt->bindParam(':password', $this->password);
     
      $stmt->bindParam(':id', $this->id);
 

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
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':id', $this->id);

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