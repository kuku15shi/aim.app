<?php
session_start();
require_once 'includes/db_config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION['id'];
$message = "";
$error = "";

// Handle Profile Pic Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $target_dir = "uploads/profiles/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_ext = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    $unique_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $unique_name;
    
    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    $message = "Profile picture updated successfully!";
                    $_SESSION['profile_pic'] = $target_file; // Update session immediately
                } else {
                    $error = "Database update failed.";
                }
                $stmt->close();
            }
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $error = "Only JPG, JPEG, PNG, and GIF allowed.";
    }
}

// Fetch current user info
$sql = "SELECT username, email, profile_pic FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Default pic if null or empty
$profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://via.placeholder.com/150';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg border-bottom sticky-top">
        <div class="container col-lg-8">
            <a class="navbar-brand" href="feed.php" style="font-family: 'Grand Hotel', cursive; font-size: 1.8rem;">Updates</a>
             <div class="d-flex align-items-center">
                <div id="theme-toggle" class="theme-toggle-pill me-3">
                    <i class="fas fa-sun" id="theme-icon"></i>
                </div>
                 <a href="feed.php" class="btn btn-outline-secondary btn-sm me-2">Back to Feed</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 card p-5">
                
                <h4 class="mb-4 text-center">Edit Profile</h4>
                
                <?php if($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <img src="<?php echo htmlspecialchars($profile_pic); ?>" class="rounded-circle mb-3 border" width="150" height="150" style="object-fit: cover;">
                    <br>
                    <strong>@<?php echo htmlspecialchars($user['username']); ?></strong>
                    <p class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Change Profile Photo</label>
                        <input type="file" name="profile_pic" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
