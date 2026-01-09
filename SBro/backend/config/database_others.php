<?php
// TEMPLATE FOR TEAMMATES

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');      // Teammates fill this
define('DB_NAME', 'spotbro_db');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>