<?php
  class Database {
    // DB Params
    private $host;
    private $db_name;
    private $username;
    private $password ;
    private $conn;
    private $url;

    public function __construct() {
   

      if ($_SERVER['SERVER_NAME'] === 'localhost') {
          /* if in local testing mode */
          $this->host = "localhost";
          $this->username = "root";
          $this->password = "2021Idiot";
          $this->db_name = "mywebsite"; 
          }
      if ($_SERVER['SERVER_NAME'] !== 'localhost') {
          /*Get Heroku ClearDB connection information */
          $this->url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      
          $this->host = $this.url["host"];
          $this->username = $this.url["user"];
          $this->password = $this.url["pass"];
          $this->db_name = substr($this.url["path"], 1);

        }
    } 
    // DB Connect
    public function connect() {
      echo $this=>url.<br>;
      echo $this=>host.<br>;
      echo $this=>db_name.<br>;
      echo $this=>username.<br>;
      echo $this=>password.<br>;
 

      $this->conn = null;
       
        try { 
            $this->conn = new PDO('mysql:host=' . $this->host .
            ';dbname=' . $this->db_name, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
        }
  }