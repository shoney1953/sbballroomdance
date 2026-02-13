<?php
class DanceClass {
   
    private $conn;
    private $table = 'danceclasses';

    public $id;
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
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY date';

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
          if ($row) {
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
          $this->date2 = $row['date2'];
          $this->date3 = $row['date3'];
          $this->date4 = $row['date4'];
          $this->date5 = $row['date5'];
          $this->date6 = $row['date6'];
          $this->date7 = $row['date7'];
          $this->date8 = $row['date8'];
          $this->date9 = $row['date9'];
          return true;
          }
          return false;
    }
    
    public function searchName($name) {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' WHERE classname like :name 
       ORDER BY name';
   
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':name', $name);
      // Execute query
      if($stmt->execute()) {
        return $stmt;
       }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
          
    }
    public function searchNameLevel($name, $level) {
      // Create query
    
      $query = 'SELECT * FROM ' . $this->table . ' WHERE classname like :name AND classlevel like :level
       ORDER BY date';
   
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':level', $level);
      // Execute query
      if($stmt->execute()) {
        return $stmt;
       }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
          
    }
    public function searchLevel($level) {
      // Create query

      $query = 'SELECT * FROM ' . $this->table . ' WHERE classlevel like :level 
       ORDER BY date';
   
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':level', $level);
      // Execute query
      if($stmt->execute()) {
        return $stmt;
       }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
          
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
        if ($this->date5 == '') {
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
          $query = 'INSERT INTO ' . $this->table . 
          ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
          time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
          classnotes = :classnotes, 
          date2 = :date2, 
          date3 = :date3, 
          date4 = :date4, 
          date5 = :date5,
          date6 = :date6, 
          date7 = :date7, 
          date8 = :date8,
          date9 = :date9,
          room = :room, date = :date';

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
          $stmt->bindParam(':date2', $this->date2);
          $stmt->bindParam(':date3', $this->date3);
          $stmt->bindParam(':date4', $this->date4);
          $stmt->bindParam(':date5', $this->date5);
          $stmt->bindParam(':date6', $this->date6);
          $stmt->bindParam(':date7', $this->date7);
          $stmt->bindParam(':date8', $this->date8);
          $stmt->bindParam(':date9', $this->date9);

     
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong


      return false;
    }
       // Create Danceclass
       public function update() {
        // Create query
        if ($this->date2 == '') {
          $this->date2 = NULL;
        }
        if ($this->date3 == '') {
          $this->date3 = NULL;
        }
        if ($this->date4 == '') {
          $this->date4 = NULL;
        }
        if ($this->date5 == '') {
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
        $query = 'UPDATE ' . $this->table . 
        ' SET classname = :classname, classlevel = :classlevel, registrationemail = :registrationemail,
        time = :time, instructors = :instructors, classlimit = :classlimit, numregistered = :numregistered,
        classnotes = :classnotes,
        date2 = :date2, date3 = :date3, date4 = :date4, date5 = :date5, date6 = :date6, date7 = :date7, date8 = :date8,
        date9 = :date9,
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

 
    public function addCount($id) {
      
                // Create query
                $query = 'SELECT numregistered FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
  
                // Prepare statement
                $stmt = $this->conn->prepare($query);
      
                // Bind ID
                $stmt->bindParam(1, $id);
      
                // Execute query
                $stmt->execute();
      
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
           
                $this->numregistered = $row['numregistered'];
                $this->numregistered++;
                $this->id = $id;
          // do the update
          $query = 'UPDATE ' . $this->table . 
          ' SET  numregistered = :numregistered WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data

          $stmt->bindParam(':numregistered', $this->numregistered);
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
      $query = 'SELECT numregistered FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

 
      $this->numregistered = $row['numregistered'];
      $this->numregistered--;
      $this->id = $id;
        // do the update
        $query = 'UPDATE ' . $this->table . 
        ' SET  numregistered = :numregistered WHERE id = :id';


        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind data

        $stmt->bindParam(':numregistered', $this->numregistered);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if($stmt->execute()) {
          return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
        }  

    // Delete Danceclass
    public function delete() {
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
   

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