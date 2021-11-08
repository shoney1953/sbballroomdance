<?php
class Contact {
    // DB stuff
    private $conn;
    private $table = 'contacts';

    public $id;
    public $firstname;
    public $lastname;
    public $message;
    public $email;
    public $contactdate;
    public $danceFavorite;
    public $danceExperience;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY contactdate DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceclass
    public function read_single($id) {
         $this->id = $id;
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
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->message = $row['message'];
          $this->email = $row['email'];
          $this->contactdate = $row['contactdate'];
          $this->contactdate = $row['danceFavorite'];
          $this->contactdate = $row['danceExperience'];

    }

    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          danceFavorite = :danceFavorite, danceExperience = :danceExperience,
          message = :message';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->message = htmlspecialchars(strip_tags($this->message));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->danceFavorite = htmlspecialchars(strip_tags($this->danceFavorite));
          $this->danceExperience = htmlspecialchars(strip_tags($this->danceExperience));

          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':message', $this->message);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':danceFavorite', $this->danceFavorite);
          $stmt->bindParam(':danceExperience', $this->danceExperience);
     

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Upcontactdate Danceclass
    public function upcontactdate() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          danceFavorite = :danceFavorite, danceExperience = :danceExperience,
          message = :message, contactdate = :contactdate WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->contactdate = htmlspecialchars(strip_tags($this->contactdate));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->danceFavorite = htmlspecialchars(strip_tags($this->danceFavorite));
          $this->danceExperience = htmlspecialchars(strip_tags($this->danceExperience));


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':message', $this->message);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':contactdate', $this->contactdate);
          $stmt->bindParam(':danceFavorite', $this->danceFavorite);
          $stmt->bindParam(':danceExperience', $this->danceExperience);

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
    public function delete_beforeDate($date) {
            // Clean data

      // Create query
      $query = 'DELETE FROM ' . $this->table . 
          ' WHERE CAST(contactdate as date) < :date';
         
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':date', $date);

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