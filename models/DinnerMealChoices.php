<?php
class DinnerMealChoices {
    // DB stuff
    private $conn;
    private $table = 'dinnermealchoices';

    public $id;
    public $mealchoice;
    public $memberprice;
    public $guestprice;
    public $eventid;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Events
    public function read() {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY eventid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function read_EventId($id) {
      // Create query
      
      $query = 'SELECT * FROM ' . $this->table . ' WHERE eventid = :id 
      ORDER BY mealchoice';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id);

      // Execute query
      if($stmt->execute()) {
        return $stmt;
       }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
     
    }

    // Get Single Event
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
          if ($row) {
          $this->mealchoice = $row['mealchoice'];
          $this->memberprice = $row['memberprice'];
          $this->guestprice = $row['guestprice'];
          $this->eventid = $row['eventid'];

          return true;
          }
          return false;
    }

    // Create Event
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET mealchoice = :mealchoice, memberprice = :memberprice, 
          guestprice = :guestprice, eventid = :eventid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->mealchoice = htmlspecialchars(strip_tags($this->mealchoice));
          $this->memberprice = htmlspecialchars(strip_tags($this->memberprice));
          $this->guestprice = htmlspecialchars(strip_tags($this->guestprice));
          $this->eventid = htmlspecialchars(strip_tags($this->eventid));
        
          // Bind data
          $stmt->bindParam(':mealchoice', $this->mealchoice);
          $stmt->bindParam(':memberprice', $this->memberprice);
          $stmt->bindParam(':eventid', $this->eventid);

          $stmt->bindParam(':guestprice', $this->guestprice);
        

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Event
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET mealchoice = :mealchoice, memberprice = :memberprice, 
           guestprice = :guestprice
            WHERE id = :id ';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->mealchoice = htmlspecialchars(strip_tags($this->mealchoice));
          $this->memberprice = htmlspecialchars(strip_tags($this->memberprice));
          $this->guestprice = htmlspecialchars(strip_tags($this->guestprice));



          // Bind data
          $stmt->bindParam(':mealchoice', $this->mealchoice);
          $stmt->bindParam(':memberprice', $this->memberprice);
          $stmt->bindParam(':guestprice', $this->guestprice);

          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
  
    // Delete Event
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