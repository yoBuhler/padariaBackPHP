<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'id16471266_root');
// define('DB_PASSWORD', 'C0M1p4op53+0');
// define('DB_NAME', 'id16471266_padaria');

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'padariaCripto');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$saltCripto = 'wNYtCnelXf0a6uiJ';
$criptoKey = 'CriptoDaPadoca';
?>