<?php
$host = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "home_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
