<?php 

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    /* if in local testing mode */
    $server = "localhost";
    $username = "root";
    $password = "2021Idiot";
    $db = "mywebsite"; 
}
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
      /*Get Heroku ClearDB connection information */
$url      = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
}
$conn = new mysqli($server, $username, $password, $db);


// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error."</p>");
} 
?>