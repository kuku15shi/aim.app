<?php
include 'auth_check.php';
include '../includes/db.php';

// Fetch all images
$sql = "SELECT * FROM slider_images ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Slider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 800px; margin-top: 50px; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; border-radius: 12px; }
        .card-header { background-color: #00b894; color: white; border-radius: 12px 12px 0 0 !important; font-weight: bold; }
        .btn-primary { background-color: #0984e3; border: none; }
        .table img { width: 100px; height: 60px; object-fit: cover; border-radius: 6px; }
    </style>
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
          <a class="nav-link active" href="sliders.php">Sliders</a>
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

<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            Upload New Slider Image
        </div>
        <div class="card-body">
            <form action="manage_slider.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="linkInput" class="form-label">Optional Link (e.g., https://google.com)</label>
                    <input type="url" name="slider_link" class="form-control" id="linkInput" placeholder="Enter URL here...">
                </div>
                <div class="input-group">
                    <input type="file" name="slider_image" class="form-control" required accept="image/*">
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Existing Slider Images
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Path</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../<?php echo $row['image_path']; ?>" alt="Slider Image"></td>
                                <td><?php echo $row['link'] ? '<a href="'.$row['link'].'" target="_blank">View</a>' : '-'; ?></td>
                                <td><?php echo $row['image_path']; ?></td>
                                <td>
                                    <form action="manage_slider.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="image_path" value="<?php echo $row['image_path']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No images found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
