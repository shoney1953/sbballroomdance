<?php
class MailOptions {
    // DB stuff
    private $conn;
    private $table = 'mailoptions';
    public $id;
    public $pwd;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Options
    public function read() {
      // Create query
     $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $this->id);
      // Execute query
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->id = $row['id'];
      $this->pwd = $row['pwd'];  

      // Execute query
      if($stmt->execute()) {
            return $stmt;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

    }

  }  
?>