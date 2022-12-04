<?php
class EventRegistration {
    // DB stuff
    private $conn;
    private $table = 'eventregistration';

    public $id;
    public $firstname;
    public $lastname;
    public $eventid;
    public $email;
    public $dateregistered;
    public $eventname;
    public $eventdate;
    public $userid;
    public $message;
    public $paid;
    public $ddattenddinner;
    public $ddattenddance;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceevents
    public function read() {
    
      $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
      r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
      r.userid, r.paid, r.message, r.ddattenddinner, r.ddattenddance
      FROM ' . $this->table . ' r
      LEFT JOIN
        events c ON r.eventid = c.id
      ORDER BY
        c.eventdate, r.eventid, r.lastname, r.firstname, r.dateregistered';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceevent
    public function read_single() {
      

          $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
          r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
          r.userid, r.paid, r.message, r.ddattenddinner, r.ddattenddance
          FROM ' . $this->table . ' r
          LEFT JOIN
            events c ON r.eventid = c.id
          WHERE
            r.id = ?
          LIMIT 0,1';
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          if ($row) {
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->eventid = $row['eventid'];
          $this->userid = $row['userid'];
          $this->eventname = $row['eventname'];
          $this->eventdate = $row['eventdate'];
          $this->email = $row['email'];
          $this->dateregistered = $row['dateregistered'];
          $this->paid = $row['paid'];
          $this->message = $row['message'];
          $this->ddattenddinner = $row['ddattenddinner'];
          $this->ddattenddance = $row['ddattenddance'];
          return true;
          }
          return false;

    }
// Get reg by userid
public function read_ByUserid($userid) {
      
    // Create query
    // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
    $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.userid, r.paid, r.message, r.ddattenddinner, r.ddattenddance
    FROM ' . $this->table . ' r
    LEFT JOIN
      events c ON r.eventid = c.id
    WHERE
      r.userid = :userid 
    ORDER BY 
      c.eventdate, r.eventid, r.lastname, r.firstname';
 

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam('userid', $userid);

    // Execute query
    $stmt->execute();

    return $stmt;

}
public function read_ByEmail($email) {
      
    // Create query
    // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
    $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
    c.eventtype as eventtype,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.userid, r.paid, r.message, r.ddattenddinner, r.ddattenddance
    FROM ' . $this->table . ' r
    LEFT JOIN
      events c ON r.eventid = c.id
    WHERE
      r.email = :email 
    ORDER BY 
      c.eventdate, r.eventid, r.lastname, r.firstname';
 
  
  
    // Prepare statement
    $stmt = $this->conn->prepare($query);
  
    // Bind ID
    $stmt->bindParam('email', $email);
  
    // Execute query
    $stmt->execute();
  
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $stmt;
  }
  public function read_ByEventId($eventid) {
      

    $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
    c.eventtype as eventtype,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.userid, r.paid, r.message, r.ddattenddinner, r.ddattenddance
    FROM ' . $this->table . ' r
    LEFT JOIN
      events c ON r.eventid = c.id
    WHERE
      r.eventid = :eventid 
    ORDER BY 
      r.eventid, r.lastname, r.firstname';
 
 

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam('eventid', $eventid);

    // Execute query
    $stmt->execute();

    return $stmt;

}
  
    // Create Danceevent
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid, paid = :paid, message = :message,
          ddattenddinner = :ddattenddinner,
          eventid = :eventid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->eventid = htmlspecialchars(strip_tags($this->eventid));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->message = htmlspecialchars(strip_tags($this->message));


          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':eventid', $this->eventid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':paid', $this->paid);
          $stmt->bindParam(':ddattenddinner', $this->ddattenddinner);
          $stmt->bindParam(':message', $this->message);
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Updateregistered Danceevent
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid, paid = :paid, message = :message,
          ddattenddinner = :ddattenddinner, ddattenddance = :ddattenddance, 
          eventid = :eventid  WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->eventid = htmlspecialchars(strip_tags($this->eventid));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->message = htmlspecialchars(strip_tags($this->message));


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':eventid', $this->eventid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':paid', $this->paid);
          $stmt->bindParam(':ddattenddinner', $this->ddattenddinner);
          $stmt->bindParam(':ddattenddance', $this->ddattenddance);
          $stmt->bindParam(':message', $this->message);


          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    public function deleteUserid($userid) {
        
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE userid = :userid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':userid', $userid);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
}
public function deleteEventid($eventid) {
        
  // Create query
  $query = 'DELETE FROM ' . $this->table . ' WHERE eventid = :eventid';

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt->bindParam(':eventid', $eventid);

  // Execute query
  if($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
}
    // Delete Danceevent
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