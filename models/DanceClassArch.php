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
    public $date2;
    public $date3;
    public $date4;
    public $date5;
    public $date6;
    public $date7;
    public $date8;
    public $date9;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY date DESC, classlevel';

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
          $this->date2 = $row['date2'];
          $this->date3 = $row['date3'];
          $this->date4 = $row['date4'];
          $this->date5 = $row['date5'];
          $this->date6 = $row['date6'];
          $this->date7 = $row['date7'];
          $this->date8 = $row['date8'];
          $this->date8 = $row['date9'];
    }

    // Create Danceclass
    public function create() {
      if ($this->date2 == '') {
        $this->date2 = NULL;
      }
      if ($this->date3 == '') {
        $this->date3 = NULL;
      }
      if ($this->date4 == '') {
        $this->date4 = NULL;
      }
      if ($this->date25 == '') {
        $this->date5 = NULL;
      }
      if ($this->date6 == '') {
        $this->date6 = NULL;
      }
      if ($this->date7 == '') {
        $this->date7 = NULL;
      }
      if ($this->date8 == '') {
        $this->date8 = NULL;
      }
      if ($this->date9 == '') {
        $this->date9 = NULL;
      }
      
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
          time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
          classnotes = :classnotes, previd = :previd,
          date2 = :date2, date3 = :date3, date4 = :date4, date5 = :date5,
          date6 = :date6, date7 = :date7, date8 = :date8, date9 = :date9,
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
          $stmt->bindParam(':date2', $this->date2);
          $stmt->bindParam(':date3', $this->date3);
          $stmt->bindParam(':date4', $this->date4);
          $stmt->bindParam(':date5', $this->date5);
          $stmt->bindParam(':date6', $this->date6);
          $stmt->bindParam(':date7', $this->date7);
          $stmt->bindParam(':date8', $this->date8);
          $stmt->bindParam(':date9', $this->date9);
      
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
        if ($this->date2 == '') {
          $this->date2 = NULL;
        }
        if ($this->date3 == '') {
          $this->date3 = NULL;
        }
        if ($this->date4 == '') {
          $this->date4 = NULL;
        }
        if ($this->date25 == '') {
          $this->date5 = NULL;
        }
        if ($this->date6 == '') {
          $this->date6 = NULL;
        }
        if ($this->date7 == '') {
          $this->date7 = NULL;
        }
        if ($this->date8 == '') {
          $this->date8 = NULL;
        }
        if ($this->date9 == '') {
          $this->date9 = NULL;
        }
      
        // Create query
        $query = 'UPDATE ' . $this->table . 
        ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
        time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
        classnotes = :classnotes, previd = :previd,
        date2 = :date2, date3 = :date3, date4 = :date4, date5 = :date5,
        date6 = :date6, date7 = :date7, date8 = :date8, date9 = :date9,
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
        $this->date2 = htmlspecialchars(strip_tags($this->date2));
        $this->date3 = htmlspecialchars(strip_tags($this->date3));
        $this->date4 = htmlspecialchars(strip_tags($this->date4));
        $this->date5 = htmlspecialchars(strip_tags($this->date5));
        $this->date6 = htmlspecialchars(strip_tags($this->date6));
        $this->date7 = htmlspecialchars(strip_tags($this->date7));
        $this->date8 = htmlspecialchars(strip_tags($this->date8));
        $this->date9 = htmlspecialchars(strip_tags($this->date9));

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
        $stmt->bindParam(':date2', $this->date2);
        $stmt->bindParam(':date3', $this->date3);
        $stmt->bindParam(':date4', $this->date4);
        $stmt->bindParam(':date5', $this->date5);
        $stmt->bindParam(':date6', $this->date6);
        $stmt->bindParam(':date7', $this->date7);
        $stmt->bindParam(':date8', $this->date8);
        $stmt->bindParam(':date9', $this->date9);


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