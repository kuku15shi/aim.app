<?php
include 'includes/db.php';

// Check if news table exists and has data
$result = $conn->query('SELECT COUNT(*) as count FROM news');
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total news items in database: " . $row['count'] . "\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

// Show sample news
$news = $conn->query('SELECT id, title, created_at FROM news LIMIT 3');
if ($news && $news->num_rows > 0) {
    echo "\nSample news:\n";
    while($n = $news->fetch_assoc()) {
        echo "- ID: " . $n['id'] . " | " . $n['title'] . "\n";
    }
} else {
    echo "\nNo news found. Let's fetch some!\n";
}

$conn->close();
?>
