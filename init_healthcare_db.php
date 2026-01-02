<?php
include 'includes/db.php';

// Create healthcare_centers table
$sql1 = "CREATE TABLE IF NOT EXISTS healthcare_centers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('government', 'private', 'laboratory', 'clinic', 'medical_shop') NOT NULL,
    address TEXT,
    phone VARCHAR(50),
    location_url TEXT,
    image_path VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql1) === TRUE) {
    echo "Table 'healthcare_centers' created successfully.<br>";
} else {
    echo "Error creating table 'healthcare_centers': " . $conn->error . "<br>";
}

// Create doctors table
$sql2 = "CREATE TABLE IF NOT EXISTS doctors (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    center_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    qualification VARCHAR(255),
    specialty VARCHAR(255),
    designation VARCHAR(255),
    department VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (center_id) REFERENCES healthcare_centers(id) ON DELETE CASCADE
)";

if ($conn->query($sql2) === TRUE) {
    echo "Table 'doctors' created successfully.<br>";
} else {
    echo "Error creating table 'doctors': " . $conn->error . "<br>";
}

$conn->close();
?>
