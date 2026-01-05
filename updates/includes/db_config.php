<?php
// Use absolute path to ensure this works from 'updates/' and 'updates/api/' and anywhere else
$home_root = dirname(dirname(__DIR__)); // Go up: includes -> updates -> home
require_once $home_root . '/includes/db.php';

// Ensure $conn is available (db.php should create it)
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . (isset($conn) ? $conn->connect_error : 'Database configuration error'));
}
?>