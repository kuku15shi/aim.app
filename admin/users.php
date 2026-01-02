<?php
include 'auth_check.php';
include '../includes/db.php';

// Access Control: Only super_admin
if ($_SESSION['admin_role'] != 'super_admin') {
    echo "<h1>Access Denied</h1>";
    exit();
}

// Handle Add User
$error = '';
$success = '';

if (isset($_POST['add_user'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if user exists
    $check = $conn->query("SELECT * FROM admins WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admins (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $success = "User added successfully.";
        } else {
            $error = "Error adding user: " . $conn->error;
        }
    }
}

// Handle Delete User
if (isset($_POST['delete_user'])) {
    $id = $_POST['user_id'];
    
    // Prevent self-deletion
    if ($id == $_SESSION['admin_id']) {
        $error = "You cannot delete yourself.";
    } else {
        $conn->query("DELETE FROM admins WHERE id=$id");
        $success = "User deleted successfully.";
    }
}

// Fetch Users
$result = $conn->query("SELECT * FROM admins ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sliders.php">Sliders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="news.php">News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="auto_news.php">Auto News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="notifications.php">Notifications</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="users.php">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2>Manage Admin Users</h2>
    
    <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <div class="card mb-4 mt-3">
        <div class="card-header bg-warning text-dark">
            Add New Admin
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-4">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option value="editor">Editor</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_user" class="btn btn-success w-100">Add User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Current Users
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <span class="badge <?php echo $row['role'] == 'super_admin' ? 'bg-danger' : 'bg-info'; ?>">
                                <?php echo $row['role']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <?php if($row['id'] != $_SESSION['admin_id']): ?>
                            <form method="POST" onsubmit="return confirm('Delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <?php else: ?>
                                <span class="text-muted">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
