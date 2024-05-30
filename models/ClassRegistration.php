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
    public $classdate;
    public $classdate2;
    public $classdate3;
    public $classdate4;
    public $classdate5;
    public $classdate6;
    public $classdate7;
    public $classdate8;
    public $classdate9;
    public $classtime;
    public $userid;
    public $registeredby;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Danceclasss
    public function read() {
      // Create query
      // $query = 'SELECT * FROM ' . $this->table . ' ORDER BY dateregistered DESC';
      $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime, 
      r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
      r.userid
      FROM ' . $this->table . ' r
      LEFT JOIN
        danceclasses c ON r.classid = c.id
      ORDER BY
        classdate, classtime, r.classid, r.lastname, r.firstname, r.dateregistered';


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
          $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
            c.date2 as classdate2, c.date3 as classdate3, c.date4 as classdate4,
           c.date5 as classdate5, c.date6 as classdate6, c.date7 as classdate7, 
           c.date8 as classdate8, c.date9 as classdate9, 
          r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
          r.userid
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
          if ($row) {
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->classid = $row['classid'];
          $this->userid = $row['userid'];
          $this->classname = $row['classname'];
          $this->classdate = $row['classdate'];
          $this->email = $row['email'];
          $this->dateregistered = $row['dateregistered'];
          $this->registeredby = $row['registeredby'];
          $this->classdate2 = $row['classdate2'];
          $this->classdate3 = $row['classdate3'];
          $this->classdate4 = $row['classdate4'];
          $this->classdate5 = $row['classdate5'];
          $this->classdate6 = $row['classdate6'];
          $this->classdate7 = $row['classdate7'];
          $this->classdate8 = $row['classdate8'];
          $this->classdate9 = $row['classdate9'];
          return true;
          }
          return false;

    }
    // Get reg by userid
public function read_ByUserid($userid) {


  $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
    c.date2 as classdate2, c.date3 as classdate3, c.date4 as classdate4,
  c.date5 as classdate5, c.date6 as classdate6, c.date7 as classdate7, 
  c.date8 as classdate8, c.date9 as classdate9, 
  r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
  r.userid
  FROM ' . $this->table . ' r
  LEFT JOIN
    danceclasses c ON r.classid = c.id
  WHERE
    r.userid = :userid ';
  

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('userid', $userid);

  // Execute query
  $stmt->execute();

  return $stmt;


}
public function read_ByEmail($email) {
      
  // Create query
  // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 
  $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
    c.date2 as classdate2, c.date3 as classdate3, c.date4 as classdate4,
  c.date5 as classdate5, c.date6 as classdate6, c.date7 as classdate7, 
  c.date8 as classdate8, c.date9 as classdate9, 
  r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
  r.userid
  FROM ' . $this->table . ' r
  LEFT JOIN
    danceclasses c ON r.classid = c.id
  WHERE
    r.email = :email ';


  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('email', $email);

  // Execute query
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  return $stmt;

}
public function checkDuplicate($email,$classid) {
      
  // Create query
  // $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1'; 

  $query = 'SELECT  * FROM ' . $this->table . 
    ' WHERE email = :email  AND classid = :classid LIMIT 0,1';

  // Prepare statement
  $stmt = $this->conn->prepare($query);


  // Bind ID
  $stmt->bindParam('email', $email);
  $stmt->bindParam('classid', $classid);
    // Execute query
    $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    return true;
  }

return false;

}
public function read_ByClassid($classid) {


  $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
  c.date2 as classdate2, c.date3 as classdate3, c.date4 as classdate4,
  c.date5 as classdate5, c.date6 as classdate6, c.date7 as classdate7, 
  c.date8 as classdate8, c.date9 as classdate9, 
  r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
  r.userid
  FROM ' . $this->table . ' r
  LEFT JOIN
    danceclasses c ON r.classid = c.id
  WHERE
    r.classid = :classid 
  ORDER BY 
    r.classid, r.lastname, r.firstname';
  

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('classid', $classid);

  // Execute query
  $stmt->execute();

  return $stmt;


}
public function readLike($classid, $search) {


  $query = 'SELECT c.classname as classname, c.date as classdate, c.time as classtime,
  r.id, r.classid, r.firstname, r.lastname, r.email, r.dateregistered, r.registeredby,
  r.userid
  FROM ' . $this->table . ' r
  LEFT JOIN
    danceclasses c ON r.classid = c.id
  WHERE
    r.classid = :classid AND 
    (r.firstname LIKE :search1 OR 
     r.lastname LIKE :search2 OR 
     r.email LIKE :search3)

  ORDER BY 
     r.lastname, r.firstname';
  

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Bind ID
  $stmt->bindParam('classid', $classid);
  $stmt->bindParam('search1', $search);
  $stmt->bindParam('search2', $search);
  $stmt->bindParam('search3', $search);

  // Execute query
  $stmt->execute();

  return $stmt;


}
    // Create Danceclass
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid, registeredby = :registeredby,
          classid = :classid';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->classid = htmlspecialchars(strip_tags($this->classid));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->registeredby = htmlspecialchars(strip_tags($this->registeredby));
  
          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':classid', $this->classid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':registeredby', $this->registeredby);
     

          // Execute query
          if ($stmt->execute()) {
 
            return true;
           
      } 

      return false;
    }

    // Updateregistered Danceclass
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          userid = :userid,
          classid = :classid  WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->classid = htmlspecialchars(strip_tags($this->classid));
          $this->userid = htmlspecialchars(strip_tags($this->userid));
          $this->email = htmlspecialchars(strip_tags($this->email));


          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':classid', $this->classid);
          $stmt->bindParam(':userid', $this->userid);
          $stmt->bindParam(':email', $this->email);
 

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

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

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
    public function deleteClassid($classid) {
        
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE classid = :classid';

      // Prepare statement
      $stmt = $this->conn->prepare($query);


      // Bind data
      $stmt->bindParam(':classid', $classid);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

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