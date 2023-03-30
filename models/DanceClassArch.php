<?php
class DanceClassArch {
   
    private $conn;
    private $table = 'danceclassesarch';

    public $id;
    public $previd;
    public $classname;
    public $classlevel;
    public $room;
    public $registrationemail;
    public $date;
    public $time;
    public $instructors;
    public $classlimit;
    public $numregistered;
    public $classnotes;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY date, classlevel';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
   

    // Get Single Danceclass
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
          $this->classname = $row['classname'];
          $this->classlevel = $row['classlevel'];
          $this->room = $row['room'];
          $this->instructors = $row['instructors'];
          $this->registrationemail = $row['registrationemail'];
          $this->date = $row['date'];
          $this->time = $row['time'];
          $this->classlimit = $row['classlimit'];
          $this->numregistered = $row['numregistered'];
          $this->classnotes = $row['classnotes'];
          $this->previd = $row['previd'];
    }

    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
          time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
          classnotes = :classnotes, previd = :previd,
          room = :room, date = :date';

          // Prepare statement
          $stmt = $this->conn->prepare($query);


          // Bind data
          $stmt->bindParam(':classname', $this->classname);
          $stmt->bindParam(':classlevel', $this->classlevel);
          $stmt->bindParam(':room', $this->room);
          $stmt->bindParam(':time', $this->time);
          $stmt->bindParam(':instructors', $this->instructors);
          $stmt->bindParam(':registrationemail', $this->registrationemail);
          $stmt->bindParam(':date', $this->date);
          $stmt->bindParam(':classlimit', $this->classlimit);
          $stmt->bindParam(':numregistered', $this->numregistered);
          $stmt->bindParam(':classnotes', $this->classnotes);
          $stmt->bindParam(':previd', $this->previd);
       
          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
       // Create Danceclass
       public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . 
        ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
        time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
        classnotes = :classnotes, previd = :previd,
        room = :room, date = :date  where id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->classname = htmlspecialchars(strip_tags($this->classname));
        $this->classlevel = htmlspecialchars(strip_tags($this->classlevel));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->room = htmlspecialchars(strip_tags($this->room));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->instructors = htmlspecialchars(strip_tags($this->instructors));
        $this->registrationemail = htmlspecialchars(strip_tags($this->registrationemail));
        $this->classlimit = htmlspecialchars(strip_tags($this->classlimit));
        $this->previd = htmlspecialchars(strip_tags($this->previd));
        $this->numregistered = htmlspecialchars(strip_tags($this->numregistered));
        $this->classnotes = htmlspecialchars(strip_tags($this->classnotes));


        // Bind data
        $stmt->bindParam(':classname', $this->classname);
        $stmt->bindParam(':classlevel', $this->classlevel);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':instructors', $this->instructors);
        $stmt->bindParam(':registrationemail', $this->registrationemail);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':classlimit', $this->classlimit);
        $stmt->bindParam(':numregistered', $this->numregistered);
        $stmt->bindParam(':classnotes', $this->classnotes);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':previd', $this->previd);

        // Execute query
        if($stmt->execute()) {
          return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }



    // Delete DanceclassArch
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