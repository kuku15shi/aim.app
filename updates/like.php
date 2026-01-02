<?php
session_start();
require_once 'includes/db_config.php';

// Ensure this script only runs for logged-in users and AJAX requests
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403); // Forbidden
    exit;
}

header('Content-Type: application/json');

$user_id = $_SESSION["id"];
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid Post ID.']);
    exit;
}

// 1. Check if the user already liked the post
$check_sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$stmt->store_result();
$is_liked = $stmt->num_rows > 0;
$stmt->close();

$liked = false;

if ($is_liked) {
    // 2. If liked, UNLIKE (Delete the record)
    $action_sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    $liked = false;
} else {
    // 3. If not liked, LIKE (Insert a new record)
    $action_sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $liked = true;
}

$stmt = $conn->prepare($action_sql);
$stmt->bind_param("ii", $user_id, $post_id);

if ($stmt->execute()) {
    // 4. Get the new total like count
    $count_sql = "SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("i", $post_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result()->fetch_assoc();
    $total_likes = $count_result['total_likes'];
    $count_stmt->close();

    // 5. Send success response back to JavaScript
    echo json_encode([
        'success' => true, 
        'liked' => $liked,
        'total_likes' => $total_likes
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error during action.']);
}

$conn->close();
?>