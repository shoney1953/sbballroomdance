<?php
class UserArchive {
    // DB stuff
    private $conn;
    private $table = 'usersarchive';
    public $id;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $created;
    public $role;
    public $passwordChanged;
    public $partnerId;
    public $streetAddress;
    public $city;
    public $state;
    public $zip;
    public $hoa;
    public $phone1;
    public $phone2;
    public $notes;
    public $lastLogin;
    public $dateArchived;
    public $numlogins;
    public $directorylist;
    public $fulltime;
    public $robodjnumlogins;
    public $robodjlastlogin;
    public $memberorigcreated;
    public $regformlink;
    public $joinedonline;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Users
    public function read() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . 
      ' ORDER BY dateArchived DESC, lastname, firstname ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readByLogin() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY lastLogin DESC, lastname, firstname ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readByArchived() {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' ORDER BY dateArchived DESC, memberorigcreated DESC, lastname, firstname ';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readLike($search) {
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' WHERE lastname LIKE :search1
      OR firstname LIKE :search2 OR username LIKE :search3 OR email like :search4
      ORDER BY lastname, firstname ';

      // Prepare statement
     
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam('search1', $search);
      $stmt->bindParam('search2', $search);
      $stmt->bindParam('search3', $search);
      $stmt->bindParam('search4', $search);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    // Get Single Danceclass
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
          $this->id = $row['id'];
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->username = $row['username'];
          $this->email = $row['email'];
          $this->role = $row['role'];
          $this->password = $row['password'];
          $this->created = $row['created'];
          $this->role = $row['role'];
          $this->passwordChanged = $row['passwordChanged'];
          $this->partnerId = $row['partnerid'];
          $this->streetAddress = $row['streetaddress'];
          $this->city = $row['city'];
          $this->state = $row['state'];
          $this->zip = $row['zip'];
          $this->hoa = $row['hoa'];
          $this->phone1 = $row['phone1'];
          $this->phone2 = $row['phone2'];
          $this->notes = $row['notes'];
          $this->lastLogin = $row['lastLogin'];
          $this->dateArchived = $row['dateArchived'];
         $this->numlogins = $row['numlogins'];
         $this->directorylist = $row['directorylist'];
         $this->fulltime = $row['fulltime'];
         $this->robodjnumlogins = $row['robodjnumlogins'];
         $this->robodjlastlogin = $row['robodjlastlogin'];
         $this->memberorigcreated = $row['memberorigcreated'];
         $this->regformlink = $row['regformlink'];
         $this->joinedonline = $row['joinedonline'];

    }
    public function getUserName($user, $email) {
        
      // Create query
      $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :user OR email = :email LIMIT 0,1'; 

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam('user', $user);
      $stmt->bindParam('email', $email);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties
      if($row) {
      $this->id = $row['id'];
      $this->firstname = $row['firstname'];
      $this->lastname = $row['lastname'];
      $this->username = $row['username'];
      $this->email = $row['email'];
      $this->password = $row['password'];
      $this->created = $row['created'];
      $this->role = $row['role'];
      $this->passwordChanged = $row['passwordChanged'];
      $this->partnerId = $row['partnerid'];
      $this->streetAddress = $row['streetaddress'];
      $this->city = $row['city'];
      $this->state = $row['state'];
      $this->zip = $row['zip'];
      $this->hoa = $row['hoa'];
      $this->phone1 = $row['phone1'];
      $this->phone2 = $row['phone2'];
      $this->notes = $row['notes'];
      $this->lastLogin = $row['lastLogin'];
      $this->dateArchived = $row['dateArchived'];
      $this->numlogins = $row['numlogins'];
      $this->directorylist = $row['directorylist'];
      $this->fulltime = $row['fulltime'];
      $this->robodjnumlogins = $row['robodjnumlogins'];
      $this->robodjlastlogin = $row['robodjlastlogin'];
      $this->memberorigcreated = $row['memberorigcreated'];
      $this->regformlink = $row['regformlink'];
      $this->joinedonline = $row['joinedonline'];
        return true;
      }
     return false;

}


    // Create User
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          username = :username, password = :password , numlogins = :numlogins,
          partnerid = :partnerid, streetaddress = :streetaddress,
          directorylist = :directorylist, fulltime = :fulltime,
          city = :city, state = :state, zip = :zip, hoa = :hoa,
          memberorigcreated = :memberorigcreated, regformlink = :regformlink,
          robodjnumlogins = :robodjnumlogins, robodjlastlogin = :robodjlastlogin,
          joinedonline = :joinedonline,
          phone1 = :phone1, phone2 = :phone2, notes = :notes ' ;

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->username = htmlspecialchars(strip_tags($this->username));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->password = $this->password;
          $this->hoa = $this->hoa;
          $this->partnerId = 0;
          $this->streetAddress = htmlspecialchars(strip_tags($this->streetAddress));
          $this->city = htmlspecialchars(strip_tags($this->city));
          $this->state = htmlspecialchars(strip_tags($this->state));
          $this->notes = htmlspecialchars(strip_tags($this->notes));
          $this->phone1 = htmlspecialchars(strip_tags($this->phone1));
          $this->phone2 = htmlspecialchars(strip_tags($this->phone2));
          $this->regformlink = htmlspecialchars(strip_tags($this->regformlink));
          $this->zip = htmlspecialchars(strip_tags($this->zip));
          $this->directorylist = $this->directorylist;
          $this->fulltime = $this->fulltime;
          $this->robodjlastlogin = $this->robodjlastlogin;
          $this->robodjnumlogins = $this->robodjnumlogins;
          $this->memberorigcreated = $this->memberorigcreated;

          // Bind data
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':username', $this->username);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':password', $this->password);
          $stmt->bindParam(':partnerid', $this->partnerId);
          $stmt->bindParam(':streetaddress', $this->streetAddress);
          $stmt->bindParam(':city', $this->city);
          $stmt->bindParam(':state', $this->state);
          $stmt->bindParam(':hoa', $this->hoa);
          $stmt->bindParam(':zip', $this->zip);
          $stmt->bindParam(':phone1', $this->phone1);
          $stmt->bindParam(':phone2', $this->phone2);
          $stmt->bindParam(':notes', $this->notes);
          $stmt->bindParam(':numlogins', $this->numlogins);
          $stmt->bindParam(':directorylist', $this->directorylist);
          $stmt->bindParam(':fulltime', $this->fulltime);
          $stmt->bindParam(':robodjnumlogins', $this->robodjnumlogins);
          $stmt->bindParam(':robodjlastlogin', $this->robodjlastlogin);
          $stmt->bindParam(':memberorigcreated', $this->memberorigcreated);
          $stmt->bindParam(':regformlink', $this->regformlink);
          $stmt->bindParam(':joinedonline', $this->joinedonline);

          // Execute query
          if ($stmt->execute()) {

            return true;
      }



      return false;
    }


    public function update() {
          // Create query
      
          $query = 'UPDATE ' . $this->table . 
          ' SET firstname = :firstname, lastname = :lastname, email = :email,
          username = :username, partnerid = :partnerid, 
          streetaddress = :streetaddress,
          directorylist = :directorylist,
          fulltime = :fulltime, regformlink = :regformlink,
          city = :city, state = :state, zip = :zip, hoa = :hoa,
          phone1 = :phone1, phone2 = :phone2, notes = :notes.
          joinedonline = :joinedonline,
          WHERE id = :id';
   

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->firstname = htmlspecialchars(strip_tags($this->firstname));
          $this->lastname = htmlspecialchars(strip_tags($this->lastname));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->username = htmlspecialchars(strip_tags($this->username));
        
          $this->partnerId = $this->partnerId;
          $this->streetAddress = htmlspecialchars(strip_tags($this->streetAddress));
          $this->city = htmlspecialchars(strip_tags($this->city));
          $this->state = htmlspecialchars(strip_tags($this->state));
          $this->notes = htmlspecialchars(strip_tags($this->notes));
          $this->phone1 = htmlspecialchars(strip_tags($this->phone1));
          $this->phone2 = htmlspecialchars(strip_tags($this->phone2));
          $this->zip = htmlspecialchars(strip_tags($this->zip));
          $this->regformlink = htmlspecialchars(strip_tags($this->regformlink));
          $this->directorylist = $this->directorylist;
          $this->fulltime = $this->fulltime;
      

          // Bind data
          $stmt->bindParam(':id', $this->id);
          $stmt->bindParam(':firstname', $this->firstname);
          $stmt->bindParam(':lastname', $this->lastname);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':username', $this->username);
          $stmt->bindParam(':partnerid', $this->partnerId);
          $stmt->bindParam(':streetaddress', $this->streetAddress);
          $stmt->bindParam(':city', $this->city);
          $stmt->bindParam(':state', $this->state);
          $stmt->bindParam(':hoa', $this->hoa);
          $stmt->bindParam(':zip', $this->zip);
          $stmt->bindParam(':phone1', $this->phone1);
          $stmt->bindParam(':phone2', $this->phone2);
          $stmt->bindParam(':notes', $this->notes);
          $stmt->bindParam(':directorylist', $this->directorylist);
          $stmt->bindParam(':fulltime', $this->fulltime);
          $stmt->bindParam(':regformlink', $this->regformlink);
           $stmt->bindParam(':joinedonline', $this->joinedonline);

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
    public function deleteUser($user, $email) {
        
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE username = :user or email = :email';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':user', $user);
      $stmt->bindParam(':email', $email);

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