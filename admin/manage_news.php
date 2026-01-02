<?php
include 'auth_check.php';
include '../includes/db.php';

// Handle Add News
if (isset($_POST['add_news'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $db_path = NULL;

    // Handle Image Upload
    if (isset($_FILES['news_image']) && $_FILES['news_image']['error'] == 0) {
        $target_dir = "../assets/images/news/";
        // Create directory if not exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["news_image"]["name"]);
        $target_file_name = time() . "_" . $file_name;
        $target_file = $target_dir . $target_file_name;
        $db_path = "assets/images/news/" . $target_file_name;
        
        $check = getimagesize($_FILES["news_image"]["tmp_name"]);
        if($check !== false) {
             if (move_uploaded_file($_FILES["news_image"]["tmp_name"], $target_file)) {
                 // Upload success
             } else {
                 $db_path = NULL; // Failed
             }
        }
    }

    $sql = "INSERT INTO news (title, content, image_path) VALUES ('$title', '$content', " . ($db_path ? "'$db_path'" : "NULL") . ")";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: news.php?success=added");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Delete News
if (isset($_POST['delete_news'])) {
    $id = $_POST['id'];
    
    // Get image path to delete file
    $sql_get = "SELECT image_path FROM news WHERE id=$id";
    $result = $conn->query($sql_get);
    if ($row = $result->fetch_assoc()) {
        if ($row['image_path']) {
            $file_path = "../" . $row['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    $sql = "DELETE FROM news WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: news.php?success=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle Edit News (Simple implementation: just update text, maybe image later if complex)
if (isset($_POST['edit_news'])) {
    $id = $_POST['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Check if new image uploaded
    $image_update_sql = "";
    if (isset($_FILES['news_image']) && $_FILES['news_image']['error'] == 0) {
        $target_dir = "../assets/images/news/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = basename($_FILES["news_image"]["name"]);
        $target_file_name = time() . "_" . $file_name;
        $target_file = $target_dir . $target_file_name;
        $db_path = "assets/images/news/" . $target_file_name;
        
        if (move_uploaded_file($_FILES["news_image"]["tmp_name"], $target_file)) {
             $image_update_sql = ", image_path='$db_path'";
        }
    }

    $sql = "UPDATE news SET title='$title', content='$content' $image_update_sql WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: news.php?success=updated");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
