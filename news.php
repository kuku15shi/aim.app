<?php
include 'includes/db.php';

// Fetch all news
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash News</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Scoped styles for news specific elements */
        .news-header {
            background-color: var(--card-bg);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .back-btn {
            font-size: 24px;
            color: var(--text-dark);
            margin-right: 15px;
            text-decoration: none;
        }
        .page-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
        }
        
        .news-container {
            padding: 15px;
            padding-bottom: 120px; /* Increased space for floating bottom nav */
        }
        
        .news-card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px var(--shadow-color);
            display: flex;
            flex-direction: column; /* Mobile first */
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }
        
        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px var(--shadow-color);
        }
        
        /* Cards without images take full width */
        .news-card.no-image {
            flex-direction: column !important;
            height: auto !important;
        }
        
        .news-card.no-image .news-content {
            padding: 20px;
        }
        
        @media (min-width: 576px) {
            .news-card {
                flex-direction: row;
                height: 160px;
            }
        }

        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        @media (min-width: 576px) {
            .news-image {
                width: 160px;
                height: 100%;
            }
        }

        .news-content {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .news-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 5px;
            line-height: 1.3;
            color: var(--text-dark);
        }
        
        .news-meta {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        
        .news-desc {
            font-size: 13px;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }
        
        /* Animation for news items */
        .news-card {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modal Styles */
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-dark);
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }
        .modal-footer {
            border-top: 1px solid var(--border-color);
        }
        .btn-close {
            filter: invert(var(--invert-icon)); /* Need to handle this via variable or filter */
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
</head>
<body>

<div class="app-container">

<div class="header news-header">
    <div class="d-flex align-items-center">
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
        <h1 class="page-title">FLASH NEWS</h1>
    </div>
    <div id="theme-toggle" class="theme-toggle-pill">
        <i class="fas fa-sun" id="theme-icon"></i>
    </div>
</div>

<div class="news-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="news-card <?php echo !$row['image_path'] ? 'no-image' : ''; ?>" data-bs-toggle="modal" data-bs-target="#newsModal<?php echo $row['id']; ?>" style="cursor: pointer;">
                <?php if($row['image_path']): ?>
                    <img src="<?php echo $row['image_path']; ?>" class="news-image" alt="News Image">
                <?php endif; ?>
                
                <div class="news-content">
                    <div class="news-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="news-meta">
                        <i class="far fa-clock me-1"></i>
                        <?php 
                            // Calculate time ago
                            $time_ago = strtotime($row['created_at']);
                            $current_time = time();
                            $time_difference = $current_time - $time_ago;
                            $seconds = $time_difference;
                            $minutes      = round($seconds / 60 );
                            $hours           = round($seconds / 3600);
                            $days          = round($seconds / 86400);

                            if($seconds <= 60) { echo "Just Now"; }
                            else if($minutes <=60) { echo "$minutes min ago"; }
                            else if($hours <=24) { echo "$hours hr ago"; }
                            else { echo "$days days ago"; }
                        ?>
                    </div>
                    <div class="news-desc">
                        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                    </div>
                </div>
            </div>

            <!-- Modal for this news item -->
            <div class="modal fade" id="newsModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php if($row['image_path']): ?>
                                <img src="<?php echo $row['image_path']; ?>" class="img-fluid rounded mb-3" alt="News Image" style="width: 100%; max-height: 400px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?php echo date('F d, Y - h:i A', strtotime($row['created_at'])); ?>
                                </small>
                            </div>
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #333;">
                                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center mt-5 text-muted">
            <i class="fas fa-newspaper fa-3x mb-3"></i>
            <p>No news updates available.</p>
        </div>
    <?php endif; ?>
</div>

</div> <!-- End app-container -->



<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="index.php" class="nav-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="updates/" class="nav-item">
        <i class="fas fa-bullhorn"></i>
        <span>Updates</span>
    </a>

    <a href="news.php" class="nav-item active news-icon-item">
        <i class="fas fa-newspaper"></i>
        <span>News</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-shopping-bag"></i>
        <span>Shopping</span>
    </a>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>
</body>
</html>
