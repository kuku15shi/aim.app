<?php
session_start();
require_once '../includes/db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    $user_id = $_SESSION['id'];

    if ($comment_id > 0) {
        
        // Ensure user owns the comment
        $check_sql = "SELECT id FROM comments WHERE id = ? AND user_id = ?";
        if ($stmt = $conn->prepare($check_sql)) {
            $stmt->bind_param("ii", $comment_id, $user_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                // Belong to user, delete it
                $stmt->close();
                
                $del_sql = "DELETE FROM comments WHERE id = ?";
                if ($del_stmt = $conn->prepare($del_sql)) {
                    $del_stmt->bind_param("i", $comment_id);
                    if ($del_stmt->execute()) {
                        echo json_encode(['success' => true]);
                    } else {
                         echo json_encode(['success' => false, 'message' => 'Delete failed']);
                    }
                    $del_stmt->close();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Permission denied']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
}
$conn->close();
?>
