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
      var_dump($_SERVER);
       if ($_SERVER['SERVER_NAME'] === 'localhost') {         
           $this->host = "localhost";
           $this->username = "root";
           $this->password = "2021Idiot";
           $this->db_name = "mywebsite"; 
           } 
      if ($_SERVER['SERVER_NAME'] !== 'localhost') {
          /*Get Heroku ClearDB connection information */
          // $db = parse_url(getenv("DATABASE_URL"));
          $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      
          $this->host = $url["host"];
          $this->username = $url["user"];
          $this->password = $url["pass"];
          $this->db_name = substr($url["path"], 1);
        }
    } 
    // DB Connect
    public function connect() {
      
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