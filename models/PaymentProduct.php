<?php
class PaymentProduct {
    // DB stuff
    private $conn;
    private $table = 'paymentproducts';
    private $id;
    public $productid;
    public $description;
    public $price;
    public $name;
    public $priceid;
    public $type;




    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY type DESC, name';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceclass
    public function read_byProductId ($productid) {
         $this->productid = $productid;
   
          // Create query
          $query = 'SELECT * FROM ' . $this->table . ' WHERE productid = :productid LIMIT 0,1'; 
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(':productid', $productid);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
          // Set properties
          if ($row) {
          // Set properties
          $this->id = $row['id'];
          $this->description = $row['description'];
          $this->name = $row['name'];
          $this->price = $row['price'];
          $this->priceid = $row['priceid'];
          $this->priceid = $row['type'];
          return true;
          }
          return false;


    }

    
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET description = :description,  name = :name,
          type = :type,
          productid = :productid, price = :price, priceid = :priceid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->description = htmlspecialchars(strip_tags($this->description));
          $this->productid = htmlspecialchars(strip_tags($this->productid));
          $this->price = htmlspecialchars(strip_tags($this->price));
          $this->priceid = htmlspecialchars(strip_tags($this->priceid));
          $this->name = htmlspecialchars(strip_tags($this->name));
          $this->type = htmlspecialchars(strip_tags($this->type));


          // Bind data
          $stmt->bindParam(':description', $this->description);
          $stmt->bindParam(':name', $this->name);
          $stmt->bindParam(':price', $this->price);
          $stmt->bindParam(':priceid', $this->priceid);
          $stmt->bindParam(':productid', $this->productid);
          $stmt->bindParam(':type', $this->type);


          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

public function update() {
  
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET description = :description,  
            name = :name,
            type = :type,
            price = :price, priceid = :priceid
            WHERE productid = :productid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->description = htmlspecialchars(strip_tags($this->description));
          $this->productid = htmlspecialchars(strip_tags($this->productid));
          $this->price = htmlspecialchars(strip_tags($this->price));
          $this->priceid = htmlspecialchars(strip_tags($this->priceid));
          $this->name = htmlspecialchars(strip_tags($this->name));
          $this->type = htmlspecialchars(strip_tags($this->type));


          // Bind data
          $stmt->bindParam(':description', $this->description);
          $stmt->bindParam(':name', $this->name);
          $stmt->bindParam(':price', $this->price);
          $stmt->bindParam(':priceid', $this->priceid);
          $stmt->bindParam(':productid', $this->productid);
          $stmt->bindParam(':type', $this->type);


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