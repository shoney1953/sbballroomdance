<?php
class TempOnlineRegPay  {
    // DB stuff
    private $conn;
    private $table = 'temponlineregpay';
    public $id;
    public $eventid;
    public $regid1;
    public $regid2;
    public $firstname1;
    public $lastname1;
    public $email1;
    public $firstname2;
    public $lastname2;
    public $email2;
    public $eventname;
    public $eventdate;
    public $eventtype;
    public $ddattenddinner;
    public $mealchoice1;
    public $mealdesc1;
    public $productid1;
    public $priceid1;
    public $mealchoice2;
    public $mealdesc2;
    public $productid2;
    public $priceid2;
    public $totalcost;
    public $cost1;
    public $cost2;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceevents
    public function read() {

      $query = 'SELECT eventname, eventdate, eventtype,
      id, eventid, regid1, regid2,
      firstname1, lastname1, email1,  mealchoice1, 
      productid1, priceid1, mealdesc1, mealdesc2,
      firstname2, lastname2, email2,   mealchoice2, 
      productid2, priceid2, cost1, cost2,
       totalcost,
       ddattenddinner

      FROM ' . $this->table . ' 
      ORDER BY
        eventid, lastname, firstname';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceevent
    public function read_single() {
      
          $query = 'SELECT eventname, eventdate,
           id, eventid,  eventtype,  totalcost,
           firstname1, lastname1, email1, mealchoice1,
           productid1, priceid1, mealdesc1, mealdesc2,
           firstname2, lastname2, email2, mealchoice2, 
           productid2, priceid2, regid1, regid2,
           ddattenddinner, cost1, cost2
          FROM ' . $this->table . ' 

          WHERE
            id = ?
          LIMIT 0,1';
  
          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          if ($row) {
          $this->eventid = $row['eventid'];
          $this->regid1 = $row['regid1'];
          $this->regid2 = $row['regid2'];
          $this->firstname1 = $row['firstname1'];
          $this->lastname1 = $row['lastname1'];
    
          $this->mealchoice1 = $row['mealchoice1'];
          $this->mealdesc1 = $row['mealdesc1'];
 
          $this->productid1 = $row['productid1'];
          $this->priceid1 = $row['priceid1'];
          $this->email1 = $row['email1'];

          $this->firstname2 = $row['firstname2'];
          $this->lastname2 = $row['lastname2'];
          $this->email2 = $row['email2'];

          $this->mealchoice2 = $row['mealchoice2'];
           $this->mealdesc2 = $row['mealdesc2'];
     
          $this->productid1 = $row['productid2'];
          $this->priceid1 = $row['priceid2'];

          $this->eventname = $row['eventname'];
          $this->eventdate = $row['eventdate'];
          $this->eventtype = $row['eventtype'];
 
          $this->ddattenddinner = $row['ddattenddinner'];

          $this->totalcost = $row['totalcost'];
          $this->cost1 = $row['cost1'];
          $this->cost2 = $row['cost2'];
          return true;
          }
          return false;

    }


    // Create Danceevent
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET eventname = :eventname, eventdate = :eventdate, eventtype = :eventtype, regid1 = :regid1, regid2 = :regid2,
          firstname1 = :firstname1, lastname1 = :lastname1, email1 = :email1,   mealchoice1 = :mealchoice1,
          firstname2 = :firstname2, lastname2 = :lastname2, email2 = :email2,   mealchoice2 = :mealchoice2,
         totalcost = :totalcost, productid1 = :productid1, productid2 = :productid2, priceid1 = :priceid1, priceid2 = :priceid2,
          ddattenddinner = :ddattenddinner, mealdesc1 = :mealdesc1, mealdesc2 = :mealdesc2, cost1 = :cost1, cost2 = :cost2,
          eventid = :eventid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);
          
          // Clean data
           $this->eventid = htmlspecialchars(strip_tags($this->eventid));
          $this->regid1 = htmlspecialchars(strip_tags($this->regid1));
          $this->regid2 = htmlspecialchars(strip_tags($this->regid2));
          $this->firstname1 = htmlspecialchars(strip_tags($this->firstname1));
          $this->lastname1 = htmlspecialchars(strip_tags($this->lastname1));
          $this->email1 = htmlspecialchars(strip_tags($this->email1));
          $this->firstname2 = htmlspecialchars(strip_tags($this->firstname2));
          $this->lastname2 = htmlspecialchars(strip_tags($this->lastname2));
          $this->email2 = htmlspecialchars(strip_tags($this->email2));

          // Bind data
          $stmt->bindParam(':eventid', $this->eventid);
          $stmt->bindParam(':regid1', $this->regid1);
          $stmt->bindParam(':regid2', $this->regid2);
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':firstname1', $this->firstname1);
          $stmt->bindParam(':lastname1', $this->lastname1);
          $stmt->bindParam(':email1', $this->email1);
          $stmt->bindParam(':mealchoice1', $this->mealchoice1);
          $stmt->bindParam(':mealdesc1', $this->mealdesc1);
          $stmt->bindParam(':firstname2', $this->firstname2);
          $stmt->bindParam(':lastname2', $this->lastname2);
          $stmt->bindParam(':email2', $this->email2);
          $stmt->bindParam(':mealchoice2', $this->mealchoice2);
          $stmt->bindParam(':mealdesc2', $this->mealdesc2);
          $stmt->bindParam(':ddattenddinner', $this->ddattenddinner);
          $stmt->bindParam(':totalcost', $this->totalcost);
          $stmt->bindParam(':cost1', $this->cost1);
          $stmt->bindParam(':cost2', $this->cost2);
          $stmt->bindParam(':productid1', $this->productid1);
          $stmt->bindParam(':productid2', $this->productid2);
          $stmt->bindParam(':priceid1', $this->priceid1);
          $stmt->bindParam(':priceid2', $this->priceid2);

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      return false;
    }

  

    // Delete Danceevent
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