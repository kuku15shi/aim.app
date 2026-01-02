<?php
session_start();
require_once '../includes/db_config.php';

// Check if user is logged in AND is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: ../index.php");
    exit;
}

// Handle Post Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $post_id = intval($_GET['id']);

    if ($action === 'approve') {
        $sql = "UPDATE posts SET approval_status = 'approved' WHERE id = ?";
    } elseif ($action === 'reject') {
        $sql = "UPDATE posts SET approval_status = 'rejected' WHERE id = ?";
    } elseif ($action === 'delete') {
        // Permanently delete or soft delete. Here we assume hard delete for now to "remove" it.
        // Note: ON DELETE CASCADE in DB handles likes/comments removal.
        // We should also unlink the image file, but for simplicity we'll just remove DB record for now.
        $sql = "DELETE FROM posts WHERE id = ?";
    }

    if (isset($sql)) {
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $post_id);
            if ($stmt->execute()) {
                $_SESSION['admin_message'] = "Post has been successfully processed (" . strtoupper($action) . ").";
            } else {
                $_SESSION['admin_error'] = "Error updating post status.";
            }
            $stmt->close();
        }
    }
    header("location: index.php");
    exit;
}

// Fetch Pending Posts
$pending_sql = "SELECT p.id, p.image_url, p.caption, p.created_at, u.username, u.email FROM posts p JOIN users u ON p.user_id = u.id WHERE p.approval_status = 'pending' ORDER BY p.created_at ASC";
$pending_result = $conn->query($pending_sql);

// Fetch Approved Posts (For Deletion/Management)
$approved_sql = "SELECT p.id, p.image_url, p.caption, p.created_at, u.username, u.email FROM posts p JOIN users u ON p.user_id = u.id WHERE p.approval_status = 'approved' ORDER BY p.created_at DESC";
$approved_result = $conn->query($approved_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">updates Admin</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <?php if (isset($_SESSION['admin_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending Approvals</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">All Approved Posts</button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabsContent">
            
            <!-- Pending Posts Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <h4 class="mb-3">Pending Posts</h4>
                <div class="row">
                    <?php if ($pending_result && $pending_result->num_rows > 0): ?>
                        <?php while($row = $pending_result->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <?php 
                                        $ext = pathinfo($row['image_url'], PATHINFO_EXTENSION);
                                        if(in_array(strtolower($ext), ['mp4', 'mov', 'avi'])) {
                                            echo '<video src="../'.htmlspecialchars($row['image_url']).'" class="card-img-top" controls style="height: 200px; object-fit: cover; background:black;"></video>';
                                        } else {
                                            echo '<img src="../'.htmlspecialchars($row['image_url']).'" class="card-img-top" style="height: 200px; object-fit: cover;">';
                                        }
                                    ?>
                                    <div class="card-body">
                                        <h5 class="card-title">@<?php echo htmlspecialchars($row['username']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($row['caption']); ?></p>
                                        <p class="text-muted small">Email: <?php echo htmlspecialchars($row['email']); ?></p>
                                        <p class="text-muted small">Submitted: <?php echo $row['created_at']; ?></p>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="index.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Reject this post?');">Reject</a>
                                        <a href="index.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Permanently DELETE this post?');">Delete</a>
                                        <a href="index.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this post?');">Approve</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12"><div class="alert alert-info">No pending posts to review.</div></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Approved Posts Tab -->
            <div class="tab-pane fade" id="approved" role="tabpanel">
                <h4 class="mb-3">Live Approved Posts</h4>
                 <div class="row">
                    <?php if ($approved_result && $approved_result->num_rows > 0): ?>
                        <?php while($row = $approved_result->fetch_assoc()): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                     <?php 
                                        $ext = pathinfo($row['image_url'], PATHINFO_EXTENSION);
                                        if(in_array(strtolower($ext), ['mp4', 'mov', 'avi'])) {
                                            echo '<video src="../'.htmlspecialchars($row['image_url']).'" class="card-img-top" controls style="height: 150px; object-fit: cover; background:black;"></video>';
                                        } else {
                                            echo '<img src="../'.htmlspecialchars($row['image_url']).'" class="card-img-top" style="height: 150px; object-fit: cover;">';
                                        }
                                    ?>
                                    <div class="card-body p-2">
                                        <small class="fw-bold">@<?php echo htmlspecialchars($row['username']); ?></small>
                                        <p class="card-text small text-truncate"><?php echo htmlspecialchars($row['caption']); ?></p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="index.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this live post?');">Remove Post</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                         <div class="col-12"><div class="alert alert-info">No approved posts found.</div></div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
