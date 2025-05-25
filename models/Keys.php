<?php
class Keys {
    // DB stuff
    private $conn;
    private $table = 'sbdcstripe';
    public $id;
    public $year;
    public $testkey;
    public $prodkey;
  

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Options
    public function read() {
      // Create query
      $query = 'SELECT id,  year, testkey, prodkey

      FROM ' . $this->table . ' 
      ORDER BY
         year DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    public function read_byYear($year) {
      // Create query

      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY userid';
      $query = 'SELECT id, year, testkey, prodkey

      FROM ' . $this->table . ' 

      WHERE year = :year '

     ;

      // Prepare statement
          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(':year', $year);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);


          // Set properties
          $this->testkey = $row['testkey'];
          $this->year = $row['year'];
          $this->prodkey = $row['prodkey'];
          $this->id = $row['id'];
         

      // Execute query
      if($stmt->execute()) {
            return $stmt;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
      $stmt->execute();

      
    }
   



    // Create record

    public function create() {

          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET  year = :year, testkey = :testkey,
          prodkey = :prodkey';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
    
          $stmt->bindParam(':year', $this->year);
          $stmt->bindParam(':testkey', $this->testkey);
          $stmt->bindParam(':prodkey', $this->prodkey);
          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }


    // Update Member Paid

    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET  testkey = :testkey,
                 prodkey = :prodkey,
                 year = :year
            WHERE id = :id ';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);
  
            // Bind data
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':testkey', $this->testkey);
        $stmt->bindParam(':prodkey', $this->prodkey);
        $stmt->bindParam(':id', $this->id);
          
          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }


    // Delete Member Paid
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