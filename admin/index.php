<?php
include 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
          <a class="nav-link active" href="index.php">Dashboard</a>
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
        <?php if($_SESSION['admin_role'] == 'super_admin'): ?>
        <li class="nav-item">
          <a class="nav-link" href="users.php">Users</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <p>Select an option below to manage your website.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Manage Sliders</h5>
                    <p class="card-text">Upload, view, and delete homepage slider images.</p>
                    <a href="sliders.php" class="btn btn-primary">Go to Sliders</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Manage News</h5>
                    <p class="card-text">Add, edit, and delete news updates.</p>
                    <a href="news.php" class="btn btn-success">Go to News</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Notifications</h5>
                    <p class="card-text">Send alerts and updates to users.</p>
                    <a href="notifications.php" class="btn btn-info text-white">Go to Notifications</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Healthcare</h5>
                    <p class="card-text">Manage hospitals, clinics & doctors.</p>
                    <a href="manage_healthcare.php" class="btn btn-danger text-white">Manage Healthcare</a>
                </div>
            </div>
        </div>

        <?php if($_SESSION['admin_role'] == 'super_admin'): ?>
        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">Add or remove admin users and assign roles.</p>
                    <a href="users.php" class="btn btn-warning">Go to Users</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
