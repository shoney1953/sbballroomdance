<?php
class ClassRegistrationArch {
    // DB stuff
    private $conn;
    private $table = 'classregistrationarch';

    public $id;   
    public $firstname;
    public $lastname;
    public $classid;
    public $archclassid;
    public $email;
    public $dateregistered;
    public $classname;
    public $classdate;
    public $classtime;
    public $userid;
    public $registeredby;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY dateregistered DESC';
      $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime, 
      r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
      r.userid, r.archclassid
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclassesarch c ON r.classid = c.id
      ORDER BY
        r.classid, r.lastname, r.firstname, r.dateregistered';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    public function read_ByClassid($classid) {


      $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
      r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
      r.userid
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclassesarch c ON r.classid = c.id
      WHERE
        r.classid = :classid 
      ORDER BY 
        r.classid, r.lastname, r.firstname';
      
    
      // Prepare statement
      $stmt = $this->conn->prepare($query);
    
      // Bind ID
      $stmt->bindParam('classid', $classid);
    
      // Execute query
      $stmt->execute();
    
      return $stmt;
    
    
    } public function read_ByEmail($email) {
      
      // Create query
      // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
      $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
      r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
      r.userid
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclassesarch c ON r.classid = c.id
      WHERE
        r.email = :email ';
    
    
      // Prepare statement
      $stmt = $this->conn->prepare($query);
    
      // Bind ID
      $stmt->bindParam('email', $email);
    
      // Execute query
      $stmt->execute();
    
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
      return $stmt;
    
    }
    public function read_ByUserid($userid) {


      $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
      r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
      r.userid
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclassesarch c ON r.classid = c.id
      WHERE
        r.userid = :userid ';
      
    
      // Prepare statement
      $stmt = $this->conn->prepare($query);
    
      // Bind ID
      $stmt->bindParam('userid', $userid);
    
      // Execute query
      $stmt->execute();
    
      return $stmt;
    
    
    }

    // Create registration archive
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid, archclassid = :archclassid, registeredby = :registeredby,
          classid = :classid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->classid = htmlspecialchars(strip_tags($this->classid));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->registeredby = htmlspecialchars(strip_tags($this->registeredby));

  
          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':classid', $this->classid);
          $stmt->bindParam(':archclassid', $this->archclassid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':registeredby', $this->registeredby);
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

  

   
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