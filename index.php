<?php
echo '<h1>Testing SBDC Ballroom Dance on Heroku</h1>';
    /*Get Heroku ClearDB connection information */
/*$cleardb_url      = parse_url(getenv("CLEARDB_DATABASE_URL"));
echo "<p> url: ".$cleardb_url."</p>"; */
$cleardb_server   = $cleardb_url[" us-cdbr-east-04.cleardb.com "];
$cleardb_username = $cleardb_url["bc2ed85efe7af4"];
$cleardb_password = $cleardb_url["072a2294"];
$cleardb_db       =  "heroku_05fb9938c429557" // substr($cleardb_url["path"],1);

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

$conn = new mysqli(
    $cleardb_server, 
    $cleardb_username, 
    $cleardb_password, 
    $cleardb_db
);

// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error."</p>");
} 

echo "<p>Connected successfully to: ".$cleardb_db."</p><br>";
?>

?>