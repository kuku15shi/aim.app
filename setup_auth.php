<?php
include 'includes/db.php';

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'editor', -- 'super_admin' or 'editor'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'admins' created successfully.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Check if default admin exists
$result = $conn->query("SELECT * FROM admins WHERE username = 'admin'");
if ($result->num_rows == 0) {
    // Create default super admin (password: admin123)
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $sql = "INSERT INTO admins (username, password, role) VALUES ('admin', '$password', 'super_admin')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Default admin user created (User: admin, Pass: admin123).\n";
    } else {
        echo "Error creating admin user: " . $conn->error . "\n";
    }
} else {
    echo "Default admin user already exists.\n";
}

$conn->close();
?>
