<?php
echo '<h1>Testing SBDC Ballroom Dance on Heroku</h1>';
    /*Get Heroku ClearDB connection information */
$url      = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

/* $active_group = 'default';
$query_builder = TRUE;
$active_group = 'default';
$query_builder = TRUE; */

/*$db['default'] = array(
    'dsn'    => '',
    'hostname' => $cleardb_server,
    'username' => $cleardb_username,
    'password' => $cleardb_password,
    'database' => $cleardb_db,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
); */
// Create connection



// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error."</p>");
} 

echo "<p>Connected successfully to: ".$db."</p><br>";
?>
$sql = "SELECT id, classname, registrationemail, instructors, classlimit, room, date FROM danceclasses";
$num_classes = 0;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo " <h1>Rows from the Users Table </h1> <br><hr>";
    while ($row = $result->fetch_assoc()) {
        echo 
        "ID: ".$row["id"]
        ."  Class:  ".$row["classname"]
        ."  Registration Email:  ".$row["registrationemail"]
        ."  Instructors:  ".$row["instructors"]
        ."  Class Limit:  ".$row["classlimit"]
        ."  Room:  ".$row["room"]
        ."  Date: ".$row["date"];
        echo "<br>";
        $num_classes++;
    }
} else {
    echo 'Dance Class TABLE EMPTY';

    
}
echo "<h2> Total Number of Classes: ".$num_classes."</h2><br>";
$conn->close();
?>