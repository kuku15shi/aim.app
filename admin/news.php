<?php
include 'auth_check.php';
include '../includes/db.php';

// Fetch all news
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 1000px; margin-top: 50px; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; border-radius: 12px; }
        .card-header { background-color: #00b894; color: white; border-radius: 12px 12px 0 0 !important; font-weight: bold; }
        .btn-primary { background-color: #0984e3; border: none; }
        .news-img { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; }
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
          <a class="nav-link" href="sliders.php">Sliders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="news.php">News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="auto_news.php">Auto News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="notifications.php">Notifications</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operation successful!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage News</span>
            <button class="btn btn-light btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                + Add News
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if($row['image_path']): ?>
                                            <img src="../<?php echo $row['image_path']; ?>" class="news-img" alt="News">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($row['title'], 0, 50)) . '...'; ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['content'], 0, 80)) . '...'; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info text-white me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editNewsModal"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                            data-content="<?php echo htmlspecialchars($row['content']); ?>"
                                        >Edit</button>
                                        <form action="manage_news.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_news" class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No news items found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="manage_news.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Add News</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                 <label class="form-label">Title</label>
                 <input type="text" name="title" class="form-control" required>
             </div>
             <div class="mb-3">
                 <label class="form-label">Content</label>
                 <textarea name="content" class="form-control" rows="4" required></textarea>
             </div>
             <div class="mb-3">
                 <label class="form-label">Image (Optional)</label>
                 <input type="file" name="news_image" class="form-control" accept="image/*">
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="add_news" class="btn btn-primary">Publish News</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="manage_news.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" id="edit-id">
          <div class="modal-header">
            <h5 class="modal-title">Edit News</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                 <label class="form-label">Title</label>
                 <input type="text" name="title" id="edit-title" class="form-control" required>
             </div>
             <div class="mb-3">
                 <label class="form-label">Content</label>
                 <textarea name="content" id="edit-content" class="form-control" rows="4" required></textarea>
             </div>
             <div class="mb-3">
                 <label class="form-label">New Image (Optional - leaves current if empty)</label>
                 <input type="file" name="news_image" class="form-control" accept="image/*">
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="edit_news" class="btn btn-primary">Update News</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var editModal = document.getElementById('editNewsModal')
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var id = button.getAttribute('data-id')
        var title = button.getAttribute('data-title')
        var content = button.getAttribute('data-content')
        
        var modalId = editModal.querySelector('#edit-id')
        var modalTitle = editModal.querySelector('#edit-title')
        var modalContent = editModal.querySelector('#edit-content')
        
        modalId.value = id
        modalTitle.value = title
        modalContent.value = content
    })
</script>
</body>
</html>
