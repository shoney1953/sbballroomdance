<?php

class TempOnlineRenewal {
    // DB stuff
    private $conn;
    private $table = 'temponlinerenewal';
    public $id;
    public $userid;
    public $partnerid;
    public $renewboth;
    public $renewthisyear;
    public $renewnextyear;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Users
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER id ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    // Create User
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET userid = :userid, 
                partnerid = :partnerid, 
                renewboth = :renewboth,
                renewthisyear = :renewthisyear,
                renewnextyear = :renewnextyear';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
          $stmt->bindParam(':userid', $this->userid);
           $stmt->bindParam(':partnerid', $this->partnerid);
          $stmt->bindParam(':renewboth', $this->renewboth);
          $stmt->bindParam(':renewthisyear', $this->renewthisyear);
          $stmt->bindParam(':renewnextyear', $this->renewnextyear);


          // Execute query
          if ($stmt->execute()) {

            return true;
      }
    }
      
    public function read_single() {
        
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 0,1'; 
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(':id', $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          if ($row) {
          $this->id = $row['id'];
          $this->userid = $row['userid'];
          $this->partnerid = $row['partnerid'];
     
          $this->renewboth = $row['renewboth'];
          $this->renewthisyear = $row['renewthisyear'];
          $this->renewnextyear = $row['renewnextyear'];
 

          return true;
          }
        
       return false;
    }
    

    public function delete() {
        
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


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