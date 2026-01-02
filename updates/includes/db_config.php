<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // <--- CHANGE THIS
define('DB_PASSWORD', '');     // <--- CHANGE THIS
define('DB_NAME', 'insta_clone_db');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>