<?php
class EventRegistrationArch {
    // DB stuff
    private $conn;
    private $table = 'eventregistrationarch';

    public $id;
    public $firstname;
    public $lastname;
    public $eventid;
    public $preveventid;
    public $email;
    public $dateregistered;
    public $eventname;
    public $eventdate;
    public $eventtype;
    public $orgemail;
    public $userid;
    public $message;
    public $paid;
    public $ddattenddinner;
    public $ddattenddance;
    public $mealchoice;
    public $dietaryrestriction;
    public $registeredby;
    public $cornhole;
    public $softball;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceevents
    public function read() {
    
      $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate, c.eventtype as eventtype,
      c.orgemail as orgemail,
      r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
      r.registeredby, r.cornhole, r.softball,
      r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
      r.mealchoice, r.dietaryrestriction
      FROM ' . $this->table . ' r
      LEFT JOIN
        eventsarch c ON r.preveventid = c.previd
      ORDER BY
        c.eventdate DESC, r.preveventid, r.lastname, r.firstname, r.dateregistered';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readByType() {
    
      $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate, c.eventtype as eventtype,
      c.orgemail as orgemail,
      r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
      r.registeredby,  r.cornhole, r.softball,
      r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
      r.mealchoice, r.dietaryrestriction
      FROM ' . $this->table . ' r
      LEFT JOIN
        eventsarch c ON r.preveventid = c.previd
      ORDER BY
        c.eventdate, c.eventtype';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceevent
    public function read_single() {
      
          $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
          c.orgemail as orgemail,
          r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
          r.registeredby,  r.cornhole, r.softball,
          r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
          r.mealchoice, r.dietaryrestriction
          FROM ' . $this->table . ' r
          LEFT JOIN
            eventsarch c ON r.preveventid = c.previd
          WHERE
            r.preveventid = ?
          LIMIT 0,1';
  
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
          $this->eventid = $row['previd'];
          $this->userid = $row['userid'];
          $this->eventname = $row['eventname'];
          $this->eventdate = $row['eventdate'];
          $this->orgemail = $row['orgemail'];
          $this->email = $row['email'];
          $this->dateregistered = $row['dateregistered'];
          $this->paid = $row['paid'];
          $this->ddattenddance = $row['ddattenddance'];
          $this->ddattenddinner = $row['ddattenddinner'];
          $this->ddattenddinner = $row['mealchoice'];
          $this->ddattenddinner = $row['dietaryrestriction'];
          $this->preveventid = $row['preveventid'];
          $this->message = $row['message'];
          $this->registeredby = $row['registeredby'];
          $this->cornhole = $row['cornhole'];
          $this->softball = $row['softball'];

    }
// Get reg by userid
public function read_ByUserid($userid) {
      
    // Create query
    // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
    $query = 'SELECT c.eventname as eventname, c.eventdate as eventdate,
    c.orgemail as orgemail,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.registeredby,  r.cornhole, r.softball,
    r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
    r.mealchoice, r.dietaryrestriction
    FROM ' . $this->table . ' r
    LEFT JOIN
      eventsarch c ON r.preveventid = c.previd
    WHERE
      r.userid = :userid 
    ORDER BY 
      c.eventdate DESC, r.eventid, r.lastname, r.firstname';
 

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
    c.orgemail as orgemail,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.registeredby,  r.cornhole, r.softball,
    r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
    r.mealchoice, r.dietaryrestriction
    FROM ' . $this->table . ' r
    LEFT JOIN
      eventsarch c ON r.preveventid = c.previd
    WHERE
      r.email = :email 
    ORDER BY 
      c.eventdate DESC, r.preveventid, r.lastname, r.firstname';
      
  
  
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
    c.orgemail as orgemail,
    r.id, r.eventid, r.firstname, r.lastname, r.email, r.dateregistered,
    r.registeredby,  r.cornhole, r.softball,
    r.userid, r.paid, r.message, r.preveventid, r.ddattenddinner, r.ddattenddance,
    r.mealchoice, r.dietaryrestriction
    FROM ' . $this->table . ' r
    LEFT JOIN
      eventsarch c ON r.preveventid = c.previd
    WHERE
      r.preveventid = :eventid ';
 

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
          userid = :userid, paid = :paid, message = :message, preveventid = :preveventid,
          ddattenddinner = :ddattenddinner, ddattenddance = :ddattenddance,
          registeredby = :registeredby, cornhole = :cornhole, softball = :softball,
          mealchoice = :mealchoice, dietaryrestriction = :dietaryrestriction,
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
          $this->mealchoice = htmlspecialchars(strip_tags($this->mealchoice));
          $this->registeredby = htmlspecialchars(strip_tags($this->registeredby));
          $this->dietaryrestriction = 
             htmlspecialchars(strip_tags($this->dietaryrestriction));


          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':eventid', $this->eventid);
          $stmt->bindParam(':preveventid', $this->preveventid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':paid', $this->paid);
          $stmt->bindParam(':cornhole', $this->cornhole);
          $stmt->bindParam(':softball', $this->softball);
          $stmt->bindParam(':ddattenddinner', $this->ddattenddinner);
          $stmt->bindParam(':ddattenddance', $this->ddattenddance);
          $stmt->bindParam(':message', $this->message);
          $stmt->bindParam(':mealchoice', $this->mealchoice);
          $stmt->bindParam(':dietaryrestriction', $this->dietaryrestriction);
          $stmt->bindParam(':registeredby', $this->registeredby);
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Updateregistered 
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid, paid = :paid, message = :message,
          cornhole = :cornhole, softball = :softball,
          ddattenddinner = :ddattenddinner, ddattenddance = :ddattenddance,
          mealchoice = :mealchoice, dietaryrestriction = :dietaryrestriction,
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
          $this->mealchoice = htmlspecialchars(strip_tags($this->mealchoice));
          $this->dietaryrestriction = 
             htmlspecialchars(strip_tags($this->dietaryrestriction));
          


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
          $stmt->bindParam(':mealchoice', $this->mealchoice);
          $stmt->bindParam(':dietaryrestriction', $this->dietaryrestriction);
          $stmt->bindParam(':cornhole', $this->cornhole);
          $stmt->bindParam(':softball', $this->softball++);


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