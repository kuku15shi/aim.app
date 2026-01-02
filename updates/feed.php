<?php
session_start();
require_once 'includes/db_config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand border-bottom sticky-top">
        <div class="container-fluid col-lg-8">
            <a class="navbar-brand" href="feed.php" style="font-family: 'Grand Hotel', cursive; font-size: 1.8rem;">Updates</a>
            
            <div class="d-flex align-items-center">
                <div id="theme-toggle" class="theme-toggle-pill me-3">
                    <i class="fas fa-sun" id="theme-icon"></i>
                </div>
                <button class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#uploadModal" title="Upload Post">
                    <i class="fas fa-plus-square fa-lg"></i>
                </button>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-dark);">
                        <?php 
                        $avatar_url = isset($_SESSION['profile_pic']) && !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : '';
                        // Check if it's a local file and if it exists
                        if (!filter_var($avatar_url, FILTER_VALIDATE_URL)) {
                            if (empty($avatar_url) || !file_exists($avatar_url)) {
                                $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['username']) . "&background=random";
                            }
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($avatar_url); ?>" alt="<?php echo htmlspecialchars($_SESSION['username']); ?>" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="profile.php">Profile Settings</a></li>
                        <!-- <li><hr class="dropdown-divider"></li> -->
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <!-- Alerts -->
                <?php if (isset($_SESSION['post_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['post_message']; unset($_SESSION['post_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['post_error'])): ?>
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['post_error']; unset($_SESSION['post_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Feed Posts -->
                <?php
                $user_id = $_SESSION['id'];
                $sql = "SELECT p.id, p.image_url, p.caption, p.created_at, u.username, u.profile_pic,
                        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count,
                        (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = $user_id) as user_liked
                        FROM posts p JOIN users u ON p.user_id = u.id WHERE p.approval_status = 'approved' ORDER BY p.created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $post_id = $row['id'];
                        $is_liked = $row['user_liked'] > 0;
                        $like_icon_class = $is_liked ? "fas fa-heart text-danger" : "far fa-heart";
                        
                        echo '<div class="card mb-4 shadow-sm border-0" id="post-' . $post_id . '">';
                        
                        // Avatar
                        $avatar = !empty($row['profile_pic']) ? $row['profile_pic'] : 'https://via.placeholder.com/32';
                        
                        // Header
                        echo '  <div class="card-header border-0 py-3">';
                        echo '      <div class="d-flex align-items-center">';
                        echo '          <img src="' . htmlspecialchars($avatar) . '" class="rounded-circle me-2 border" width="32" height="32" style="object-fit: cover;">';
                        echo '          <h6 class="mb-0 fw-bold">@' . htmlspecialchars($row['username']) . '</h6>';
                        echo '      </div>';
                        echo '  </div>';
                        
                        // Image
                        // Media Rendering
                        $file_ext = strtolower(pathinfo($row['image_url'], PATHINFO_EXTENSION));
                        if (in_array($file_ext, ['mp4', 'mov', 'avi'])) {
                            echo '  <div class="video-container bg-black">';
                            echo '      <video src="' . htmlspecialchars($row['image_url']) . '" class="card-img-top video-player" autoplay muted loop playsinline controlsList="nodownload" style="object-fit: contain;"></video>';
                            echo '      <div class="video-overlay"><i class="fas fa-play fa-3x text-white opacity-75"></i></div>';
                            echo '  </div>';
                        } else {
                            echo '  <img src="' . htmlspecialchars($row['image_url']) . '" class="card-img-top rounded-0" alt="Post Image">';
                        }
                        
                        // Actions (Using FontAwesome for aesthetics)
                        echo '  <div class="card-body pb-2">';
                        echo '       <div class="d-flex mb-2">';
                        echo '          <i class="' . $like_icon_class . ' fa-lg me-3 like-btn" data-post-id="' . $post_id . '" style="cursor:pointer;"></i>';
                        echo '          <i class="far fa-comment fa-lg me-3 comment-toggle-btn" data-post-id="' . $post_id . '" style="cursor:pointer;"></i>';
                        echo '          <i class="far fa-paper-plane fa-lg share-btn" data-post-id="' . $post_id . '" style="cursor:pointer;" title="Copy Link"></i>';
                        echo '       </div>';
                        
                        // Like Count
                        echo '      <div class="mb-2 fw-bold"><span id="like-count-' . $post_id . '">' . $row['like_count'] . '</span> likes</div>';

                        // Caption
                         echo '      <p class="card-text mb-1"><span class="fw-bold me-1">' . htmlspecialchars($row['username']) . '</span>' . htmlspecialchars($row['caption']) . '</p>';
                         echo '      <small class="text-muted">' . date("F j", strtotime($row['created_at'])) . '</small>';
                         
                        // Comments Section
                        echo '      <div class="mt-2">'; // Removed hidden wrapper to make it always accessible or collapsible logic preserved but styled differently
                        // If we want to hide comments list but show input, we need to separate them.
                        // For IG style, usually "View all X comments" is a link, and input is always visible at bottom.
                        
                        echo '          <div class="comment-section" id="comments-' . $post_id . '" style="display:none;">';
                         echo '              <div class="comments-list mb-2" id="comments-list-' . $post_id . '"></div>';
                        echo '          </div>';
                         
                        // Input Area (Always Visible for better UX, or keep inside toggle if Preferred)
                        // Let's keep it simple: List is hidden, Input is here.
                        // Input Area
                        echo '          <div class="d-flex align-items-center border-top pt-2 mt-2 position-relative">';
                        echo '              <div class="me-2 text-muted" style="cursor:pointer; font-size: 1.5rem;">☺</div>'; // Simple emoji icon
                        echo '              <textarea class="form-control border-0 shadow-none insta-comment-input" placeholder="Add a comment..." id="comment-input-' . $post_id . '" rows="1" style="resize:none; overflow-y:hidden;"></textarea>';
                        echo '              <button class="btn btn-link text-decoration-none fw-bold insta-post-btn post-comment-btn" type="button" data-post-id="' . $post_id . '" disabled>Post</button>';
                        echo '          </div>';
                        
                        echo '      </div>';

                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="text-center py-5">';
                    echo '  <h5 class="text-muted">No posts to show yet.</h5>';
                    echo '  <p class="text-muted">Be the first to upload!</p>';
                    echo '</div>';
                }
                ?>
                
            </div>
            
            <!-- Pending Posts Sidebar (Desktop only) -->
            <div class="col-md-4 d-none d-md-block">
                 <div class="card border-0 shadow-sm sticky-top" style="top: 150px;">
                    <div class="card-header border-0"><h6 class="mb-0 text-muted">My Pending Posts</h6></div>
                    <ul class="list-group list-group-flush">
                        <?php
                        $my_id = $_SESSION['id'];
                        $sql_pending = "SELECT image_url, caption, created_at FROM posts WHERE user_id = ? AND approval_status = 'pending' ORDER BY created_at DESC";
                        if ($stmt = $conn->prepare($sql_pending)) {
                             $stmt->bind_param("i", $my_id);
                             $stmt->execute();
                             $res_pending = $stmt->get_result();
                             
                             if ($res_pending->num_rows > 0) {
                                 while ($rowp = $res_pending->fetch_assoc()) {
                                     echo '<li class="list-group-item d-flex align-items-center border-0">';
                                     echo '  <img src="' . htmlspecialchars($rowp['image_url']) . '" width="40" height="40" class="me-2 rounded" style="object-fit:cover;">';
                                     echo '  <small class="text-muted text-truncate" style="max-width: 150px;">' . htmlspecialchars($rowp['caption']) . '</small>';
                                     echo '</li>';
                                 }
                             } else {
                                  echo '<li class="list-group-item text-muted small border-0">No pending posts.</li>';
                             }
                             $stmt->close();
                        }
                        ?>
                    </ul>
                 </div>
            </div>
            
        </div>
    </div>


    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content m-2">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadModalLabel">Create New Post</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="submit_post.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="image" class="form-label">Select Image</label>
                    <input class="form-control" type="file" id="image" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="caption" class="form-label">Caption</label>
                    <textarea class="form-control" id="caption" name="caption" rows="3" placeholder="Write a caption..."></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Share</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Spacer for Bottom Nav -->
    <div style="height: 120px;"></div>

    <!-- Bottom Navigation -->
    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="../index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-item active">
            <i class="fas fa-bullhorn"></i>
            <span>Updates</span>
        </a>

        <a href="../index.php" class="nav-item emergency-item">
            <div class="emergency-circle">
                <i class="fas fa-bell"></i>
            </div>
            <span>Emergency</span>
        </a>

        <a href="../news.php" class="nav-item">
            <i class="fas fa-newspaper"></i>
            <span>News</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-shopping-bag"></i>
            <span>Shopping</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="js/social.js"></script>
</body>
</html>