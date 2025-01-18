<?php
class PaymentProduct {
    // DB stuff
    private $conn;
    private $table = 'paymentproducts';

    public $productid;
    public $description;
    public $price;
    public $name;
    public $priceid;




    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY productid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceclass
    public function read_single($productid) {
         $this->productid = $productid;
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE paymentid = ? LIMIT 0,1'; 
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->productid);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->description = $row['description'];
          $this->name = $row['name'];
          $this->price = $row['price'];
          $this->priceid = $row['priceid'];
 


    }

    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET description = :description,  name = :name,
          productid = :productid, price = :price, priceid = :priceid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->description = htmlspecialchars(strip_tags($this->description));
          $this->productid = htmlspecialchars(strip_tags($this->productid));
          $this->price = htmlspecialchars(strip_tags($this->price));
          $this->priceid = htmlspecialchars(strip_tags($this->priceid));
          $this->name = htmlspecialchars(strip_tags($this->name));


          // Bind data
          $stmt->bindParam(':description', $this->description);
          $stmt->bindParam(':name', $this->name);
          $stmt->bindParam(':price', $this->price);
          $stmt->bindParam(':priceid', $this->priceid);
          $stmt->bindParam(':productid', $this->productid);


          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Upcontactdate Danceclass


    // Delete Danceclass
    public function delete() {
        
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :productid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->productid = htmlspecialchars(strip_tags($this->productid));

          // Bind data
          $stmt->bindParam(':productid', $this->productid);

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