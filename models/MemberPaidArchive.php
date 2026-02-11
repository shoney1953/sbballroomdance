<?php
class MemberPaidArch {
    // DB stuff
    private $conn;
    private $table = 'memberpaidarch';
    public $id;
    public $userid;
    public $year;
    public $paid;
    public $paidonline;
    public $datearchived;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Events
    public function read() {
      // Create query

      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY userid';
      $query = 'SELECT r.id, r.userid, r.year, r.paid, r.paidonline,
      u.firstname, u.lastname, u.id, u.email, 
      FROM ' . $this->table . ' r
      LEFT JOIN
        usersarchived u ON r.userid = u.previd
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
      $query = 'SELECT r.id, r.userid, r.year, r.paid, r.paidonline,
      u.firstname, u.lastname, u.email
      FROM ' . $this->table . ' r
      LEFT JOIN
        usersarchived u ON r.userid = u.previd
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
     $query = 'SELECT r.id, r.userid, r.year, r.paid, r.paidonline,
           u.firstname, u.lastname, u.id, u.email
          FROM ' . $this->table . ' r
          LEFT JOIN
           usersarchived u ON r.userid = u.previd
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
          $this->paidonline = $row['paidonline'];
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
    public function read_byUseridYear($userid, $year) {
            // Create query

          $query = 'SELECT * FROM ' . $this->table . ' 
            WHERE userid = :userid AND year = :year LIMIT 0,1'
           ; 

            // Prepare statement
            $stmt = $this->conn->prepare($query);
  
            // Bind ID
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':year', $year);
  
 

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties

   
       if ($row) {
           $this->userid = $row['userid'];
          $this->year = $row['year'];
          $this->paid = $row['paid'];
          $this->paidonline = $row['paidonline'];
          $this->id = $row['id'];

          return true;
      }


      return false;
      }
    // Create record

    public function create() {

          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET userid = :userid, year = :year, 
          paid = :paid, paidonline = :paidonline';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind data
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':year', $this->year);
          $stmt->bindParam(':paid', $this->paid);
          $stmt->bindParam(':paidonline', $this->paidonline);
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