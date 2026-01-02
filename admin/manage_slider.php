<?php
include 'auth_check.php';
include '../includes/db.php';

if (isset($_POST['upload'])) {
    $target_dir = "../assets/images/slider/";
    $file_name = basename($_FILES["slider_image"]["name"]);
    // Unique filename to prevent overwrite issues
    $target_file_name = time() . "_" . $file_name; 
    $target_file = $target_dir . $target_file_name;
    $db_path = "assets/images/slider/" . $target_file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["slider_image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["slider_image"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "webp" ) {
        echo "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["slider_image"]["tmp_name"], $target_file)) {
            // Insert into DB
            $link = isset($_POST['slider_link']) ? $conn->real_escape_string($_POST['slider_link']) : '';
            $sql = "INSERT INTO slider_images (image_path, link) VALUES ('$db_path', '$link')";
            if ($conn->query($sql) === TRUE) {
                header("Location: sliders.php?success=uploaded");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $image_path = "../" . $_POST['image_path'];

    // Delete from DB
    $sql = "DELETE FROM slider_images WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        // Delete file from folder
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        header("Location: sliders.php?success=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
