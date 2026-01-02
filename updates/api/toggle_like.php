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

    if ($post_id > 0) {
        // Check if already liked
        $check_sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
        if ($stmt = $conn->prepare($check_sql)) {
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Unlike
                $delete_sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
                if ($del_stmt = $conn->prepare($delete_sql)) {
                    $del_stmt->bind_param("ii", $user_id, $post_id);
                    $del_stmt->execute();
                    $action = 'unliked';
                }
            } else {
                // Like
                $insert_sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
                if ($ins_stmt = $conn->prepare($insert_sql)) {
                    $ins_stmt->bind_param("ii", $user_id, $post_id);
                    $ins_stmt->execute();
                    $action = 'liked';
                }
            }
            $stmt->close();
        }

        // Get new like count
        $count_sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
        if ($count_stmt = $conn->prepare($count_sql)) {
            $count_stmt->bind_param("i", $post_id);
            $count_stmt->execute();
            $count_res = $count_stmt->get_result();
            $count_row = $count_res->fetch_assoc();
            $new_count = $count_row['count'];
            
            echo json_encode(['success' => true, 'action' => $action, 'new_count' => $new_count]);
        } else {
             echo json_encode(['success' => false, 'message' => 'Error counting likes']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    }
}
$conn->close();
?>
