<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION["id"];
    
    // Quick check: Does this user still exist? (In case of DB reset)
    $check_user = "SELECT id FROM users WHERE id = $user_id";
    $res_user = $conn->query($check_user);
    if ($res_user->num_rows == 0) {
        // User ID in session invalid (deleted from DB), force logout
        session_destroy();
        header("location: index.php");
        exit;
    }

    $caption = trim($_POST["caption"]);
    
    // --- Image Upload Handling ---
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        
        $target_dir = "uploads/"; // Make sure this directory exists and is writable
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        
        // Generate a unique filename to prevent overwrites
        $unique_filename = uniqid('post_') . '.' . $image_file_type;
        $target_file = $target_dir . $unique_filename;
        
        // Check file type (Allow images and videos)
        if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif" 
           && $image_file_type != "mp4" && $image_file_type != "mov" && $image_file_type != "avi") {
            $_SESSION['post_error'] = "Sorry, only JPG, JPEG, PNG, GIF, MP4, MOV & AVI files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                
                // File uploaded successfully, now insert data into the database
                $image_url = $target_file; 
                
                // Post status is set to 'pending'
                $sql = "INSERT INTO posts (user_id, image_url, caption, approval_status) VALUES (?, ?, ?, 'pending')";
                
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iss", $param_user_id, $param_image_url, $param_caption);
                    $param_user_id = $user_id;
                    $param_image_url = $image_url;
                    $param_caption = $caption;

                    if ($stmt->execute()) {
                        $_SESSION['post_message'] = "Post submitted successfully! It is pending admin approval.";
                    } else {
                        $_SESSION['post_error'] = "Database error: " . $conn->error;
                    }
                    $stmt->close();
                }
            } else {
                $_SESSION['post_error'] = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $_SESSION['post_error'] = "Please select an image file to upload.";
    }

    $conn->close();
    header("location: feed.php");
    exit;
}
?>