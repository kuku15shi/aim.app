<?php
require_once 'includes/db_config.php';

// The password we want to use
$password = 'admin123';
// Hash it using the server's current PHP configuration
$hash = password_hash($password, PASSWORD_DEFAULT);

$username = 'admin';

// Update the user
$sql = "UPDATE users SET password_hash = ? WHERE username = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $hash, $username);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<h1>Success!</h1>";
            echo "<p>Admin password has been reset to: <strong>admin123</strong></p>";
            echo "<p>Hash used: " . $hash . "</p>";
            echo '<p><a href="index.php">Go to Login</a></p>';
        } else {
             echo "<h1>No changes made.</h1>";
             echo "<p>Maybe the admin user doesn't exist? (Run setup.sql first)</p>";
             echo "<p>Or the password was already correct.</p>";
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
