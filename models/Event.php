<?php
class Event {
    // DB stuff
    private $conn;
    private $table = 'events';

    public $id;
    public $eventname;
    public $eventtype;
    public $eventroom;
    public $eventdesc;
    public $eventdate;
    public $eventcost;
    public $eventform;
    public $eventnumregistered;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Events
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY eventdate';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
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
          $this->eventname = $row['eventname'];
          $this->eventtype = $row['eventtype'];
          $this->eventroom = $row['eventroom'];
          $this->eventcost = $row['eventcost'];
          $this->eventdate = $row['eventdate'];
          $this->eventdesc = $row['eventdesc'];
          $this->eventform = $row['eventform'];
          $this->eventnumregistered = $row['eventnumregistered'];
    }

    // Create Event
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET eventname = :eventname, eventtype = :eventtype, 
          eventdesc = :eventdesc, eventcost = :eventcost, eventform = :eventform,
          eventroom = :eventroom, eventdate = :eventdate';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->eventname = htmlspecialchars(strip_tags($this->eventname));
          $this->eventtype = htmlspecialchars(strip_tags($this->eventtype));
          $this->eventdate = htmlspecialchars(strip_tags($this->eventdate));
          $this->eventroom = htmlspecialchars(strip_tags($this->eventroom));
          $this->eventdesc = htmlspecialchars(strip_tags($this->eventdesc));
          $this->eventcost = htmlspecialchars(strip_tags($this->eventcost));
          $this->eventform = htmlspecialchars(strip_tags($this->eventform));

          // Bind data
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventroom', $this->eventroom);
          $stmt->bindParam(':eventdesc', $this->eventdesc);
          $stmt->bindParam(':eventcost', $this->eventcost);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':eventfirn', $this->eventform);

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
          ' SET eventname = :eventname, eventtype = :eventtype, 
          eventdesc = :eventdesc, eventcost = :eventcost, eventform = :eventform,
          eventroom = :eventroom, eventdate = :eventdate';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->eventname = htmlspecialchars(strip_tags($this->eventname));
          $this->eventtype = htmlspecialchars(strip_tags($this->eventtype));
          $this->eventdate = htmlspecialchars(strip_tags($this->eventdate));
          $this->eventroom = htmlspecialchars(strip_tags($this->eventroom));
          $this->eventdesc = htmlspecialchars(strip_tags($this->eventdesc));
          $this->eventcost = htmlspecialchars(strip_tags($this->eventcost));
          $this->eventform = htmlspecialchars(strip_tags($this->eventform));

          // Bind data
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventroom', $this->eventroom);
          $stmt->bindParam(':eventdesc', $this->eventdesc);
          $stmt->bindParam(':eventcost', $this->eventcost);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':eventform', $this->eventform);

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