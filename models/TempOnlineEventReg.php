<?php
class TempOnlineEventReg  {
    // DB stuff
    private $conn;
    private $table = 'temponlineeventreg';
    public $id;
    public $eventid;
    public $visitor;
    public $firstname1;
    public $lastname1;
    public $email1;
    public $firstname2;
    public $lastname2;
    public $email2;
    public $dateregistered;
    public $eventname;
    public $eventdate;
    public $eventtype;
    public $orgemail;
    public $message;
    public $ddattenddinner;
    public $ddattenddance;
    public $mealchoice1;
    public $dietaryrestriction1;
    public $mealdesc1;
    public $productid1;
    public $priceid1;
    public $mealchoice2;
    public $dietaryrestriction2;
    public $mealdesc2;
    public $productid2;
    public $priceid2;
    public $registeredby;
    public $totalcost;
    public $guest1attenddinner;
    public $guest1firstname;
    public $guest1lastname;
    public $guest1mealchoice;
    public $guest1email;
      public $guest1dr;
    public $guest2attenddinner;
    public $guest2firstname;
    public $guest2lastname;
    public $guest2mealchoice;
    public $guest2email;
  public $guest2dr;
    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceevents
    public function read() {

      $query = 'SELECT eventname, eventdate,
      orgemail,
      id, eventid, 
      firstname1, lastname1, email1,  mealchoice1, dietaryrestriction1,
      productid1, priceid1, mealdesc1, mealdesc2,
      firstname2, lastname2, email2,   mealchoice2, dietaryrestriction2,
      guest1firstname, guest1lastname, guest1email,  guest1attenddinner, guest1mealchoice, guest1dr,
      guest2firstname, guest2lastname, guest2email,  guest2attenddinner, guest2mealchoice, guest2dr,
      productid2, priceid2,
        visitor, totalcost,
      registeredby,  mealchoice,
       message, ddattenddinner, ddattenddance,
       eventtype
      FROM ' . $this->table . ' 
      ORDER BY
        eventid, lastname, firstname, dateregistered';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Danceevent
    public function read_single() {
      
          $query = 'SELECT eventname, eventdate,
           id, eventid, visitor, eventtype, registeredby, totalcost,
           firstname1, lastname1, email1, mealchoice1, dietaryrestriction1,
           productid1, priceid1, mealdesc1, mealdesc2,
           firstname2, lastname2, email2, mealchoice2, dietaryrestriction2,
          guest1firstname, guest1lastname, guest1email,  guest1attenddinner, guest1mealchoice, guest1dr,
          guest2firstname, guest2lastname, guest2email,  guest2attenddinner, guest2mealchoice, guest2dr,
     
           productid2, priceid2, orgemail, dateregistered, 
          message, ddattenddinner, ddattenddance
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

          $this->firstname1 = $row['firstname1'];
          $this->lastname1 = $row['lastname1'];
    
          $this->mealchoice1 = $row['mealchoice1'];
          $this->mealdesc1 = $row['mealdesc1'];
          $this->dietaryrestriction1 = $row['dietaryrestriction1'];
          $this->productid1 = $row['productid1'];
          $this->priceid1 = $row['priceid1'];
          $this->email1 = $row['email1'];

          $this->firstname2 = $row['firstname2'];
          $this->lastname2 = $row['lastname2'];
    
          $this->mealchoice2 = $row['mealchoice2'];
           $this->mealdesc2 = $row['mealdesc2'];
          $this->dietaryrestriction2 = $row['dietaryrestriction2'];
          $this->productid1 = $row['productid2'];
          $this->priceid1 = $row['priceid2'];
          $this->email2 = $row['email2'];

          $this->eventname = $row['eventname'];
          $this->eventdate = $row['eventdate'];
          $this->eventtype = $row['eventtype'];
          $this->orgemail = $row['orgemail'];

          $this->dateregistered = $row['dateregistered'];

          $this->visitor = $row['visitor'];
          $this->message = $row['message'];
          $this->ddattenddinner = $row['ddattenddinner'];
          $this->ddattenddance = $row['ddattenddance'];
       
          $this->registeredby = $row['registeredby'];
          $this->eventname = $row['eventname'];
          $this->eventtype = $row['eventtype'];
          $this->eventdate = $row['eventdate'];
          $this->totalcost = $row['totalcost'];

          $this->guest1firstname = $row['guest1firstname'];
          $this->guest1lastname = $row['guest1lastname'];
          $this->guest1email = $row['guest1email'];
          $this->guest1attenddinner = $row['guest1attenddinner'];
          $this->guest1mealchoice = $row['guest1mealchoice'];
          $this->guest1dr = $row['guest1dr'];

          $this->guest2firstname = $row['guest2firstname'];
          $this->guest2lastname = $row['guest2lastname'];
          $this->guest2email = $row['guest2email'];
          $this->guest2attenddinner = $row['guest2attenddinner'];
          $this->guest2mealchoice = $row['guest2mealchoice'];
          $this->guest2dr = $row['guest2dr'];
          return true;
          }
          return false;

    }


  
  
    // Create Danceevent
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET eventname = :eventname, eventdate = :eventdate, eventtype = :eventtype,
          firstname1 = :firstname1, lastname1 = :lastname1, email1 = :email1,  dietaryrestriction1 = :dietaryrestriction1, mealchoice1 = :mealchoice1,
          firstname2 = :firstname2, lastname2 = :lastname2, email2 = :email2,  dietaryrestriction2 = :dietaryrestriction2, mealchoice2 = :mealchoice2,
          message = :message, totalcost = :totalcost, productid1 = :productid1, productid2 = :productid2, priceid1 = :priceid1, priceid2 = :priceid2,
          guest1firstname = :guest1firstname, guest1lastname = :guest1lastname, guest1email = :guest1email,  
          guest1attenddinner = :guest1attenddinner, guest1mealchoice = :guest1mealchoice, guest1dr = :guest1dr,
             guest2firstname = :guest2firstname, guest2lastname = :guest2lastname, guest2email = :guest2email,  
          guest2attenddinner = :guest2attenddinner, guest2mealchoice = :guest2mealchoice, guest2dr = :guest2dr,

          ddattenddinner = :ddattenddinner, mealdesc1 = :mealdesc1, mealdesc2 = :mealdesc2,
          registeredby = :registeredby, orgemail = :orgemail,
          visitor = :visitor,
          eventid = :eventid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);
          
          // Clean data
           $this->eventid = htmlspecialchars(strip_tags($this->eventid));

          $this->firstname1 = htmlspecialchars(strip_tags($this->firstname1));
          $this->lastname1 = htmlspecialchars(strip_tags($this->lastname1));
  
          $this->email1 = htmlspecialchars(strip_tags($this->email1));
          $this->dietaryrestriction1 = 
             htmlspecialchars(strip_tags($this->dietaryrestriction1));

          $this->firstname2 = htmlspecialchars(strip_tags($this->firstname2));
          $this->lastname2 = htmlspecialchars(strip_tags($this->lastname2));

          $this->email2 = htmlspecialchars(strip_tags($this->email2));
          $this->dietaryrestriction2 = 
             htmlspecialchars(strip_tags($this->dietaryrestriction2));
          $this->orgemail = htmlspecialchars(strip_tags($this->orgemail));
          $this->orgemail = 
             htmlspecialchars(strip_tags($this->orgemail));
          $this->message = htmlspecialchars(strip_tags($this->message));
          $this->registeredby = htmlspecialchars(strip_tags($this->registeredby));

          // Bind data
          $stmt->bindParam(':eventid', $this->eventid);
          $stmt->bindParam(':eventname', $this->eventname);
          $stmt->bindParam(':eventtype', $this->eventtype);
          $stmt->bindParam(':eventdate', $this->eventdate);
          $stmt->bindParam(':firstname1', $this->firstname1);
          $stmt->bindParam(':lastname1', $this->lastname1);
          $stmt->bindParam(':email1', $this->email1);
          $stmt->bindParam(':dietaryrestriction1', $this->dietaryrestriction1);
          $stmt->bindParam(':mealchoice1', $this->mealchoice1);
          $stmt->bindParam(':mealdesc1', $this->mealdesc1);
          $stmt->bindParam(':firstname2', $this->firstname2);
          $stmt->bindParam(':lastname2', $this->lastname2);
          $stmt->bindParam(':email2', $this->email2);
          $stmt->bindParam(':dietaryrestriction2', $this->dietaryrestriction2);
          $stmt->bindParam(':mealchoice2', $this->mealchoice2);
          $stmt->bindParam(':mealdesc2', $this->mealdesc2);
          $stmt->bindParam(':visitor', $this->visitor);
          $stmt->bindParam(':ddattenddinner', $this->ddattenddinner);
          $stmt->bindParam(':registeredby', $this->registeredby);
          $stmt->bindParam(':message', $this->message);
          $stmt->bindParam(':totalcost', $this->totalcost);
          $stmt->bindParam(':productid1', $this->productid1);
          $stmt->bindParam(':productid2', $this->productid2);
          $stmt->bindParam(':priceid1', $this->priceid1);
          $stmt->bindParam(':priceid2', $this->priceid2);
          $stmt->bindParam(':orgemail', $this->orgemail);

            $stmt->bindParam(':guest1firstname', $this->guest1firstname);
            $stmt->bindParam(':guest1lastname', $this->guest1lastname);
            $stmt->bindParam(':guest1email', $this->guest1email);
            $stmt->bindParam(':guest1attenddinner', $this->guest1attenddinner);
            $stmt->bindParam(':guest1mealchoice', $this->guest1mealchoice);
            $stmt->bindParam(':guest1dr', $this->guest1dr);
            
            $stmt->bindParam(':guest2firstname', $this->guest2firstname);
            $stmt->bindParam(':guest2lastname', $this->guest2lastname);
            $stmt->bindParam(':guest2email', $this->guest2email);
            $stmt->bindParam(':guest2attenddinner', $this->guest2attenddinner);
            $stmt->bindParam(':guest2mealchoice', $this->guest2mealchoice);
            $stmt->bindParam(':guest2dr', $this->guest2dr);
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