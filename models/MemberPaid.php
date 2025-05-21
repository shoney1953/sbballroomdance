<?php
class MemberPaid {
    // DB stuff
    private $conn;
    private $table = 'memberpaid';
    public $id;
    public $userid;
    public $year;
    public $paid;
  

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Events
    public function read() {
      // Create query

      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY userid';
      $query = 'SELECT r.id, r.userid, r.year, r.paid,
      u.firstname, u.lastname, u.id, u.email
      FROM ' . $this->table . ' r
      LEFT JOIN
        users u ON r.userid = u.id
      ORDER BY
         r.year DESC, u.lastname, u.firstname';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function read_byYear($year) {
      // Create query

      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY userid';
      $query = 'SELECT r.id, r.userid, r.year, r.paid,
      u.firstname, u.lastname, u.email
      FROM ' . $this->table . ' r
      LEFT JOIN
        users u ON r.userid = u.id
      WHERE r.year = :year
      ORDER BY
         r.year DESC, u.lastname, u.firstname'
     ;

      // Prepare statement
      $stmt = $this->conn->prepare($query);
   // Bind ID
      $stmt->bindParam(':year', $year);
      // Execute query
      $stmt->execute();

      return $stmt;
    }
   

    // Get Single record
    public function read_single() {
          // Create query
     $query = 'SELECT r.id, r.userid, r.year, r.paid
           u.firstname, u.lastname, u.id, u.email
          FROM ' . $this->table . ' r
          LEFT JOIN
           users u ON r.userid = u.id
         ORDER BY
           r.year, u.lastname, u.firstname
          WHERE id = ? LIMIT 0,1'; 
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);


          // Set properties
          $this->userid = $row['userid'];
          $this->year = $row['year'];
          $this->paid = $row['paid'];
          $this->id = $row['id'];
         

    }
        // Get Single Event
    public function read_byUserid($userid) {
            // Create query
            $query = 'SELECT * FROM ' . $this->table . ' 
            WHERE userid = :userid
            ORDER BY year DESC'
           ; 

            // Prepare statement
            $stmt = $this->conn->prepare($query);
  
            // Bind ID
            $stmt->bindParam(':userid', $userid);
  
            // Execute query
            $stmt->execute();
            return $stmt;
      }

    // Create record

    public function create() {

          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET userid = :userid, year = :year, 
          paid = :paid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':year', $this->year);
          $stmt->bindParam(':paid', $this->paid);
         
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
          ' SET userid = :userid, year = :year, 
            paid = :paid
            WHERE id = :id ';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);
  
            // Bind data
        $stmt->bindParam('userid', $this->userid);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':paid', $this->paid);
        $stmt->bindParam(':id', $this->id);
          
          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
     // Update Member Paid
    
     public function update_paid($id) {
 
      // Create query
      $query = 'UPDATE ' . $this->table . 
        ' SET  paid = 1 WHERE id = :id ';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

        // Bind data

    $stmt->bindParam(':id', $id);

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
    

public function deleteUserid($userid) {
  // Create query
  $query = 'DELETE FROM ' . $this->table . ' WHERE userid = :userid';

  // Prepare statement
  $stmt = $this->conn->prepare($query);


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

}
?>