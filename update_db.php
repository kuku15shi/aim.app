<?php
include 'includes/db.php';

// Add link column if it doesn't exist
$sql = "ALTER TABLE slider_images ADD COLUMN link VARCHAR(255) DEFAULT ''";

if ($conn->query($sql) === TRUE) {
    echo "Table updated successfully: link column added.\n";
} else {
    // Check if duplicate column error (meaning it already exists)
    if (strpos($conn->error, "Duplicate column name") !== false) {
         echo "Column 'link' already exists.\n";
    } else {
        echo "Error updating table: " . $conn->error . "\n";
    }
}

$conn->close();
?>
