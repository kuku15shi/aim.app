<?php
include '../includes/db.php';

// Create Taxi Drivers Table
$sql = "CREATE TABLE IF NOT EXISTS taxi_drivers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    type ENUM('car', 'auto') NOT NULL DEFAULT 'auto',
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    lat DECIMAL(10, 8) DEFAULT NULL,
    lng DECIMAL(11, 8) DEFAULT NULL,
    timing_start TIME DEFAULT NULL,
    timing_end TIME DEFAULT NULL,
    available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'taxi_drivers' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Add some dummy data if empty
$check = $conn->query("SELECT * FROM taxi_drivers LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO taxi_drivers (type, name, phone, lat, lng, timing_start, timing_end) VALUES 
    ('auto', 'Ramesh Kumar', '9876543210', 12.9716, 77.5946, '08:00:00', '20:00:00'),
    ('car', 'Suresh Travels', '9988776655', 12.9780, 77.5900, '06:00:00', '23:00:00')");
    echo "Dummy data inserted.<br>";
}

echo "Database initialization complete.";
?>
