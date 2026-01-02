<?php
require_once 'includes/db_config.php';

// Add profile_pic column if not exists
$sql = "ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Column 'profile_pic' added successfully";
} else {
    // Check if it failed because it exists
    if ($conn->errno == 1060) {
         echo "Column 'profile_pic' already exists.";
    } else {
         echo "Error adding column: " . $conn->error;
    }
}

$conn->close();
?>
