<?php
class EventArch {
    // DB stuff
    private $conn;
    private $table = 'eventsarch';

    public $id;
    public $previd;
    public $eventname;
    public $eventtype;
    public $eventroom;
    public $eventdesc;
    public $eventdj;
    public $eventdate;
    public $eventcost;
    public $eventform;
    public $eventnumregistered;
    public $eventregopen;
    public $eventregend;
    public $orgemail;
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

      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY eventdate DESC, eventtype';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readByYear($year) {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' 
      WHERE YEAR(eventdate) = :year
      ORDER BY eventdate DESC, eventtype';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':year', $year);
      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readByYearType($year, $type) {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' 
      WHERE YEAR(eventdate) = :year AND eventtype = :type
      ORDER BY eventdate DESC, eventtype';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':year', $year);
      $stmt->bindParam(':type', $type);
      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function distinctYear() {
      // Create query

      $query = 'SELECT DISTINCT YEAR(eventdate) FROM ' . $this->table . ' ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function distinctType($year) {
      // Create query

      $query = 'SELECT DISTINCT eventtype FROM ' . $this->table . ' 
       WHERE YEAR(eventdate) = :year';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':year', $year);


      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
          $this->eventregend = $row['eventregend'];
          $this->eventregopen = $row['eventregopen'];
          $this->orgemail = $row['orgemail'];
          $this->eventdj = $row['eventdj'];
          $this->eventnumregistered = $row['eventnumregistered'];
          $this->eventproductid = $row['eventproductid'];
          $this->eventmempriceid = $row['eventmempriceid'];
          $this->eventguestpriceid = $row['eventguestpriceid'];
          $this->eventguestcost = $row['eventguestcost'];
          $this->eventdwopcount = $row['eventdwopcount'];
       
    }

    // Create Event
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET eventname = :eventname, eventtype = :eventtype, 
          eventdesc = :eventdesc, eventcost = :eventcost, eventform = :eventform,
          eventroom = :eventroom, eventdate = :eventdate, eventdj = :eventdj,
          previd = :previd, eventregend = :eventregend,   eventregopen = :eventregopen,
          orgemail= :orgemail, eventdwopcount = :eventdwopcount,
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
          $this->orgemail = htmlspecialchars(strip_tags($this->orgemail));
          $this->eventdj = htmlspecialchars(strip_tags($this->eventdj));
          
          $this->eventregend = htmlspecialchars(strip_tags($this->eventregend));
          $this->eventregopen = htmlspecialchars(strip_tags($this->eventregopen));
          $this->eventnumregistered = htmlspecialchars(strip_tags($this->eventnumregistered));

          // Bind data
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventroom', $this->eventroom);
          $stmt->bindParam(':eventdesc', $this->eventdesc);
          $stmt->bindParam(':eventcost', $this->eventcost);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':orgemail', $this->orgemail);
          $stmt->bindParam(':eventregend', $this->eventregend);

          $stmt->bindParam(':eventregopen', $this->eventregopen);
          $stmt->bindParam(':eventform', $this->eventform);
          $stmt->bindParam(':eventdj', $this->eventdj);
          $stmt->bindParam(':previd', $this->previd);
          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':eventproductid', $this->eventproductid);
          $stmt->bindParam(':eventmempriceid', $this->eventmempriceid);
          $stmt->bindParam(':eventguestpriceid', $this->eventguestpriceid);
          $stmt->bindParam(':eventguestcost', $this->eventguestcost);
          $stmt->bindParam(':eventdwopcount', $this->eventdwopcount);
         

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
          previd = :previd, eventregend = :eventregend, eventregopen = :eventregopen,
 
          orgemail = :orgemail, eventdwopcount = :eventdwopcount,
          eventproductid = :eventproductid,
          eventmempriceid = :eventmempriceid,
          eventguestpriceid = :eventguestpriceid,
          eventguestcost = :eventguestcost,
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
          $stmt->bindParam(':orgemail', $this->orgemail);
          $stmt->bindParam(':eventregend', $this->eventregend);
   
          $stmt->bindParam(':eventregopen', $this->eventregopen);
          $stmt->bindParam(':previd', $this->previd);
          $stmt->bindParam(':eventnumregistered', $this->eventnumregistered);
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':eventproductid', $this->eventproductid);
          $stmt->bindParam(':eventmempriceid', $this->eventmempriceid);
          $stmt->bindParam(':eventguestpriceid', $this->eventguestpriceid);
          $stmt->bindParam(':eventguestcost', $this->eventguestcost);
          $stmt->bindParam(':eventdwopcount', $this->eventdwopcount);
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