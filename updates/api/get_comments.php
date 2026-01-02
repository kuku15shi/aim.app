<?php
session_start();
require_once '../includes/db_config.php';

header('Content-Type: application/json');

$current_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    
    $sql = "SELECT c.id, c.user_id, c.comment, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while($row = $result->fetch_assoc()) {
            $comments[] = [
                'id' => $row['id'],
                'username' => htmlspecialchars($row['username']),
                'comment' => htmlspecialchars($row['comment']),
                'is_mine' => ($row['user_id'] == $current_user_id)
            ];
        }
        
        echo json_encode(['success' => true, 'comments' => $comments]);
        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing post_id']);
}
$conn->close();
?>
