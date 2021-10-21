<?php
 echo '<h1>Testing SBDC Ballroom Dance on Heroku</h1>';
    /*Get Heroku ClearDB connection information */
$cleardb_url      = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server   = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db       = substr($cleardb_url["path"],1);

echo "$cleardb_url:".$cleardb_url."<br>" ;
echo "$cleardb_server:".$cleardb_server."<br>" ;
echo "$cleardb_username:".$cleardb_username."<br>" ;
echo "$cleardb_password:".$cleardb_password."<br>" ;
echo "$cleardb_db:".$cleardb_db."<br>" ;
$active_group = 'default';
$query_builder = TRUE;
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
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
);

?>