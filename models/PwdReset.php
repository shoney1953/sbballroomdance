<?php

class PwdReset {
    // DB stuff
    private $conn;
    private $table = 'pwdreset';

    public $id;
    public $pwdResetEmail;
    public $pwdResetToken;
    public $pwdResetSelector;
    public $pwdResetExpiration;

// Constructor with DB
  public function __construct($db) {
    $this->conn = $db;
     }
  public function readBy_email($email) {
 
  // Create query
  $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1'; 

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('email', $email);

  // Execute query
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $this->id = $row['id'];
      $this->pwdResetEmail = $row['pwdResetEmail'];
      $this->pwdResetToken = $row['pwdResetToken'];
      $this->pwdResetSelector = $row['pwdResetSelector'];
      $this->pwdResetExpiration = $row['pwdResetExpiration'];
    return true;
  }
 return false;
}
public function readBy_selector($selector, $expiration) {
 
  // Create query
  $query = 'SELECT * FROM ' . $this->table . ' WHERE pwdResetSelector = :selector
  AND pwdResetExpiration >= :expiration
  LIMIT 0,1'; 

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('selector', $selector);
  $stmt->bindParam('expiration', $expiration);
 
  // Execute query
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $this->id = $row['id'];
      $this->pwdResetEmail = $row['pwdResetEmail'];
      $this->pwdResetToken = $row['pwdResetToken'];
      $this->pwdResetSelector = $row['pwdResetSelector'];
      $this->pwdResetExpiration = $row['pwdResetExpiration'];
    return true;
  }
 return false;
}
    // Create User
 public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . 
      ' SET pwdResetEmail = :pwdResetEmail, 
            pwdResetToken = :pwdResetToken, 
            pwdResetSelector = :pwdResetSelector,
            pwdResetExpiration = :pwdResetExpiration' ;

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->pwdResetEmail = htmlspecialchars(strip_tags($this->pwdResetEmail));
      $this->pwdResetToken = $this->pwdResetToken;
      $this->pwdResetSelector = $this->pwdResetSelector;
      $this->pwdResetExpiration = $this->pwdResetExpiration;
    

      // Bind data
      $stmt->bindParam(':pwdResetEmail', $this->pwdResetEmail);
      $stmt->bindParam(':pwdResetToken', $this->pwdResetToken);
      $stmt->bindParam(':pwdResetSelector', $this->pwdResetSelector);
      $stmt->bindParam(':pwdResetExpiration', $this->pwdResetExpiration);
    
      // Execute query
      if ($stmt->execute()) {

        return true;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
  } 
    // Delete Token Record
  public function deleteByEmail() {

      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE pwdResetEmail = :pwdResetEmail';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->pwdResetEmail = htmlspecialchars(strip_tags($this->pwdResetEmail));

      // Bind data
      $stmt->bindParam(':pwdResetEmail', $this->pwdResetEmail);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      return true; // ok if none existed
}

    // Delete Token Record
  public function delete() {
        
      // Create query
      $query = 'DELETE * FROM ' . $this->table . ' WHERE id = :id ';

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