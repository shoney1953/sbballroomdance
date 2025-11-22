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
    public $eventdj;
    public $eventdate;
    public $eventcost;
    public $eventform;
    public $eventnumregistered;
    public $eventregend;
    public $orgemail;
    public $eventregopen;
    public $eventproductid;
    public $eventmempriceid;
    public $eventguestcost;
    public $eventguestpriceid;
    public $eventdwopcount;

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
    public function read_ByArchDate($archdate) {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' WHERE eventdate < :archdate 
      ORDER BY eventdate';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':archdate', $archdate);

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
          if ($row) {
 
          $this->eventname = $row['eventname'];
          $this->eventtype = $row['eventtype'];
          $this->eventroom = $row['eventroom'];
          $this->eventcost = $row['eventcost'];
          $this->eventdate = $row['eventdate'];
          $this->eventdesc = $row['eventdesc'];
          $this->eventform = $row['eventform'];
          $this->eventregopen = $row['eventregopen'];
          $this->eventregend = $row['eventregend'];
          $this->eventregopen = $row['eventregopen'];
          $this->eventdj = $row['eventdj'];
          $this->orgemail = $row['orgemail'];
          $this->eventnumregistered = $row['eventnumregistered'];
          $this->eventproductid = $row['eventproductid'];
          $this->eventmempriceid = $row['eventmempriceid'];
          $this->eventguestpriceid = $row['eventguestpriceid'];
          $this->eventguestcost = $row['eventguestcost'];
          $this->eventdwopcount = $row['eventdwopcount'];
          return true;
          }
          return false;
    }
    


    // Create Event
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET eventname = :eventname, eventtype = :eventtype, 
          eventdesc = :eventdesc, eventcost = :eventcost, eventform = :eventform,
          eventroom = :eventroom, eventdate = :eventdate, eventdj = :eventdj,
          eventregend = :eventregend, orgemail= :orgemail,
          eventregopen = :eventregopen,
          eventproductid = :eventproductid,
          eventmempriceid = :eventmempriceid,
          eventguestpriceid = :eventguestpriceid,
          eventguestcost = :eventguestcost,
          eventnumregistered = :eventnumregistered';

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
          $this->eventregopen = htmlspecialchars(strip_tags($this->eventregopen));
          $this->eventregend = htmlspecialchars(strip_tags($this->eventregend));
          $this->eventdj = htmlspecialchars(strip_tags($this->eventdj));
          $this->orgemail = htmlspecialchars(strip_tags($this->orgemail));
          $this->eventnumregistered = htmlspecialchars(strip_tags($this->eventnumregistered));

          // Bind data
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventroom', $this->eventroom);
          $stmt->bindParam(':eventdesc', $this->eventdesc);
          $stmt->bindParam(':eventcost', $this->eventcost);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':eventform', $this->eventform);
          $stmt->bindParam(':eventregend', $this->eventregend);
          $stmt->bindParam(':eventregopen', $this->eventregopen);
          $stmt->bindParam(':eventdj', $this->eventdj);
          $stmt->bindParam(':orgemail', $this->orgemail);
          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':eventproductid', $this->eventproductid);
          $stmt->bindParam(':eventmempriceid', $this->eventmempriceid);
          $stmt->bindParam(':eventguestpriceid', $this->eventguestpriceid);
          $stmt->bindParam(':eventguestcost', $this->eventguestcost);
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
          eventroom = :eventroom, eventdate = :eventdate, eventdj = :eventdj,
          eventregend = :eventregend, orgemail = :orgemail,
          eventregopen = :eventregopen,
          eventproductid = :eventproductid,
          eventmempriceid = :eventmempriceid,
          eventguestpriceid = :eventguestpriceid,
          eventguestcost = :eventguestcost,
          eventdwopcount = :eventdwopcount,
          eventnumregistered = :eventnumregistered
            WHERE id = :id ';
   

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
          $this->orgemail = htmlspecialchars(strip_tags($this->orgemail));
          $this->eventregend = htmlspecialchars(strip_tags($this->eventregend));
          $this->eventregopen = htmlspecialchars(strip_tags($this->eventregopen));
          $this->eventnumregistered = htmlspecialchars(strip_tags($this->eventnumregistered));
          $this->eventdj = htmlspecialchars(strip_tags($this->eventdj));

          // Bind data
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventroom', $this->eventroom);
          $stmt->bindParam(':eventdesc', $this->eventdesc);
          $stmt->bindParam(':eventdj', $this->eventdj);
          $stmt->bindParam(':eventcost', $this->eventcost);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':eventform', $this->eventform);
          $stmt->bindParam(':eventregend', $this->eventregend);
          $stmt->bindParam(':eventdwopcount', $this->eventdwopcount);
          $stmt->bindParam(':eventregopen', $this->eventregopen);    
          $stmt->bindParam(':orgemail', $this->orgemail);
          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':eventproductid', $this->eventproductid);
          $stmt->bindParam(':eventmempriceid', $this->eventmempriceid);
          $stmt->bindParam(':eventguestpriceid', $this->eventguestpriceid);
          $stmt->bindParam(':eventguestcost', $this->eventguestcost);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
     public function update_form() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET  eventform = :eventform
            WHERE id = :id ';


          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data


          // Bind data

          $stmt->bindParam(':eventform', $this->eventform);
 
          $stmt->bindParam(':id', $this->id);
  

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
  public function addCount($id) {
      
      // Create query
      $query = 'SELECT eventnumregistered FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

 
      $this->eventnumregistered = $row['eventnumregistered'];
      $this->eventnumregistered++;
      $this->id = $id;
          // do the update
          $query = 'UPDATE ' . $this->table . 
          ' SET  eventnumregistered = :eventnumregistered WHERE id = :id';


          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data

          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
          }

    public function decrementCount($id) {

          // Create query
          $query = 'SELECT eventnumregistered FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);


          $this->eventnumregistered = $row['eventnumregistered'];
          $this->eventnumregistered--;

          $this->id = $id;
          // do the update
          $query = 'UPDATE ' . $this->table . 
          ' SET  eventnumregistered = :eventnumregistered WHERE id = :id';


          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data

          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':id', $this->id);
      
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