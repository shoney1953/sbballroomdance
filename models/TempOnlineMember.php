<?php

class TempOnlineMember {
    // DB stuff
    private $conn;
    private $table = 'temponlinemember';

    public $id;
    public $firstname;
    public $lastname;

    public $email;
    public $directorylist;
    public $fulltime;
    public $created;

    public $streetAddress;
    public $city;
    public $state;
    public $zip;
    public $hoa;
    public $phone;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Users
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY lastname, firstname ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    // Create User
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, 
          lastname = :lastname, 
          email = :email,
          streetaddress = :streetaddress,
          city = :city, 
          state = :state, 
          zip = :zip, 
          hoa = :hoa,
          directorylist = :directorylist, 
          fulltime = :fulltime,
          phone = :phone' ;

          // Prepare statement
          $stmt = $this->conn->prepare($query);
  
          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));

          $this->email = htmlspecialchars(strip_tags($this->email));
   
          $this->hoa = $this->hoa;
      
          $this->streetAddress = htmlspecialchars(strip_tags($this->streetAddress));
          $this->city = htmlspecialchars(strip_tags($this->city));
          $this->state = htmlspecialchars(strip_tags($this->state));
        
          $this->phone = htmlspecialchars(strip_tags($this->phone));
       
          $this->zip = htmlspecialchars(strip_tags($this->zip));
          $this->directorylist = $this->directorylist;
          $this->fulltime = $this->fulltime;


          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);

          $stmt->bindParam(':email', $this->email);
 
          $stmt->bindParam(':streetaddress', $this->streetAddress);
          $stmt->bindParam(':city', $this->city);
          $stmt->bindParam(':state', $this->state);
          $stmt->bindParam(':hoa', $this->hoa);
          $stmt->bindParam(':zip', $this->zip);
          $stmt->bindParam(':phone', $this->phone);
          $stmt->bindParam(':directorylist', $this->directorylist);
           $stmt->bindParam(':fulltime', $this->fulltime);


          // Execute query
          if ($stmt->execute()) {

            return true;
      }
    }
       public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          streetaddress = :streetaddress,
          city = :city, state = :state, zip = :zip, hoa = :hoa,
          directorylist = :directorylist, fulltime = :fulltime,
          phone = :phone 
          WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);
  
          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));

          $this->email = htmlspecialchars(strip_tags($this->email));
   
          $this->hoa = $this->hoa;
      
          $this->streetAddress = htmlspecialchars(strip_tags($this->streetAddress));
          $this->city = htmlspecialchars(strip_tags($this->city));
          $this->state = htmlspecialchars(strip_tags($this->state));
        
          $this->phone = htmlspecialchars(strip_tags($this->phone));
       
          $this->zip = htmlspecialchars(strip_tags($this->zip));
          $this->directorylist = $this->directorylist;
          $this->fulltime = $this->fulltime;


          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);

          $stmt->bindParam(':email', $this->email);
 
          $stmt->bindParam(':streetaddress', $this->streetAddress);
          $stmt->bindParam(':city', $this->city);
          $stmt->bindParam(':state', $this->state);
          $stmt->bindParam(':hoa', $this->hoa);
          $stmt->bindParam(':zip', $this->zip);
          $stmt->bindParam(':phone', $this->phone);
          $stmt->bindParam(':directorylist', $this->directorylist);
           $stmt->bindParam(':fulltime', $this->fulltime);


          // Execute query
          if ($stmt->execute()) {

            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
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
          $this->id = $row['id'];
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->email = $row['email'];
          $this->created = $row['created'];
          $this->streetAddress = $row['streetaddress'];
          $this->city = $row['city'];
          $this->state = $row['state'];
          $this->zip = $row['zip'];
          $this->hoa = $row['hoa'];
          $this->phone = $row['phone'];
          $this->directorylist = $row['directorylist'];
          $this->fulltime = $row['fulltime'];

          return true;
          }
        
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