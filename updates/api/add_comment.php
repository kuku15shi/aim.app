<?php
session_start();
require_once '../includes/db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if ($post_id > 0 && !empty($comment)) {
        $sql = "INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iis", $user_id, $post_id, $comment);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true, 
                    'comment' => htmlspecialchars($comment), 
                    'username' => htmlspecialchars($_SESSION['username'])
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
            $stmt->close();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}
$conn->close();
?>
