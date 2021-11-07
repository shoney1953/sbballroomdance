<?php
class ClassRegistration {
    // DB stuff
    private $conn;
    private $table = 'classregistration';

    public $id;
    public $firstname;
    public $lastname;
    public $classid;
    public $email;
    public $dateregistered;
    public $classname;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY dateregistered DESC';
      $query = 'SELECT c.classname as classname, r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered 
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclasses c ON r.classid = c.id
      ORDER BY
        r.classid, r.lastname, r.firstname, r.dateregistered';


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceclass
    public function read_single() {
      
          // Create query
          // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
          $query = 'SELECT c.classname as classname, r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered 
          FROM ' . $this->table . ' r
          LEFT JOIN
            danceclasses c ON r.classid = c.id
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
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->classid = $row['classid'];
          $this->classname = $row['classname'];
          $this->email = $row['email'];
          $this->dateregistered = $row['dateregistered'];

    }

    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          classid = :classid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->classid = htmlspecialchars(strip_tags($this->classid));
          $this->email = htmlspecialchars(strip_tags($this->email));


          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':classid', $this->classid);
          $stmt->bindParam(':email', $this->email);
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Updateregistered Danceclass
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          classid = :classid  WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->classid = htmlspecialchars(strip_tags($this->classid));
          $this->email = htmlspecialchars(strip_tags($this->email));


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':classid', $this->classid);
          $stmt->bindParam(':email', $this->email);


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