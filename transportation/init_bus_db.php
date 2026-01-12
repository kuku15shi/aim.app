<?php
include '../includes/db.php';

$queries = [
    "CREATE TABLE IF NOT EXISTS bus_stops (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        lat DECIMAL(10, 8) NOT NULL,
        lng DECIMAL(11, 8) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS bus_routes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        route_name VARCHAR(255) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS bus_route_stops (
        id INT AUTO_INCREMENT PRIMARY KEY,
        route_id INT,
        stop_id INT,
        stop_order INT,
        FOREIGN KEY (route_id) REFERENCES bus_routes(id) ON DELETE CASCADE,
        FOREIGN KEY (stop_id) REFERENCES bus_stops(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS bus_trips (
        id INT AUTO_INCREMENT PRIMARY KEY,
        route_id INT,
        trip_name VARCHAR(255), 
        FOREIGN KEY (route_id) REFERENCES bus_routes(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS bus_timings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        trip_id INT,
        stop_id INT,
        arrival_time TIME,
        FOREIGN KEY (trip_id) REFERENCES bus_trips(id) ON DELETE CASCADE,
        FOREIGN KEY (stop_id) REFERENCES bus_stops(id) ON DELETE CASCADE
    )"
];

foreach ($queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        // Table created or already exists
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

// Add 'type' column to bus_routes if it doesn't exist
$col_check = $conn->query("SHOW COLUMNS FROM bus_routes LIKE 'type'");
if ($col_check->num_rows == 0) {
    $conn->query("ALTER TABLE bus_routes ADD COLUMN type ENUM('local', 'ksrtc') DEFAULT 'local' AFTER route_name");
    echo "Added 'type' column to bus_routes.\n";
}
?>
