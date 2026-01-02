<?php
session_start();
require_once '../includes/db_config.php';

// Check if user is logged in AND is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: ../index.php");
    exit;
}

// Handle Approval Action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = intval($_GET['id']);

    if ($action === 'approve') {
        $sql = "UPDATE users SET status = 'active' WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                // Here is where you would call the SMTP function to send the "Account Approved" email
                $_SESSION['admin_message'] = "User ID $user_id has been **APPROVED** and activated.";
            } else {
                $_SESSION['admin_error'] = "Error updating user status.";
            }
            $stmt->close();
        }
    }
    header("location: users.php");
    exit;
}

// Fetch Pending Users
$pending_users_sql = "SELECT id, username, email, created_at FROM users WHERE status = 'pending'";
$pending_users_result = $conn->query($pending_users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - User Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">🔒 Admin Panel - Pending User Approval</h2>

        <?php if (isset($_SESSION['admin_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
        <?php endif; ?>

        <?php if ($pending_users_result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Registered On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $pending_users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="users.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this user?');">Approve</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No users currently pending approval.</div>
        <?php endif; ?>
        
        <a href="../feed.php" class="btn btn-secondary mt-3">Back to Feed</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>