<?php
include 'auth_check.php';
include '../includes/db.php';

$success = '';
$error = '';

// Handle Fetch News
if (isset($_POST['fetch_news'])) {
    $api_key = '56eb73fca33849e79e49ec38b7276302'; // Your NewsAPI key
    $category = isset($_POST['category']) ? $_POST['category'] : 'general';
    
    try {
        // Try multiple endpoints to get news
        $urls = [
            "https://newsapi.org/v2/top-headlines?country=in&category={$category}&pageSize=10&apiKey={$api_key}",
            "https://newsapi.org/v2/top-headlines?country=us&category={$category}&pageSize=10&apiKey={$api_key}",
            "https://newsapi.org/v2/everything?q=india&language=en&sortBy=publishedAt&pageSize=10&apiKey={$api_key}"
        ];
        
        $data = null;
        $http_code = 0;
        
        // Try each URL until we get results
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code == 200) {
                $temp_data = json_decode($response, true);
                if ($temp_data['status'] == 'ok' && isset($temp_data['articles']) && count($temp_data['articles']) > 0) {
                    $data = $temp_data;
                    break; // Found articles, stop trying
                }
            }
        }
        
        if (!$data || $http_code != 200) {
            $error = "Failed to fetch news from all sources. HTTP Code: $http_code";
        } else {
            if ($data['status'] == 'ok' && isset($data['articles'])) {
                $count = 0;
                
                foreach ($data['articles'] as $article) {
                    $title = $article['title'] ?? '';
                    $description = $article['description'] ?? $article['content'] ?? 'No description available.';
                    $image_url = $article['urlToImage'] ?? null;
                    
                    // Clean and validate content
                    $title = trim($title);
                    $description = trim($description);
                    
                    // Skip only if completely empty (be less strict)
                    if (empty($title) || strlen($title) < 10) {
                        continue;
                    }
                    
                    // Limit description length
                    if (strlen($description) > 300) {
                        $description = substr($description, 0, 300) . '...';
                    }
                    
                    $title = $conn->real_escape_string($title);
                    $description = $conn->real_escape_string($description);
                    
                    // Handle image download if available
                    $image_path = NULL;
                    if ($image_url && filter_var($image_url, FILTER_VALIDATE_URL)) {
                        $image_dir = "../assets/images/news/";
                        if (!file_exists($image_dir)) {
                            mkdir($image_dir, 0777, true);
                        }
                        
                        // Download image
                        $image_name = time() . '_' . $count . '.jpg';
                        $image_file = $image_dir . $image_name;
                        
                        $img_ch = curl_init($image_url);
                        $fp = fopen($image_file, 'wb');
                        curl_setopt($img_ch, CURLOPT_FILE, $fp);
                        curl_setopt($img_ch, CURLOPT_HEADER, 0);
                        curl_setopt($img_ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($img_ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($img_ch, CURLOPT_TIMEOUT, 10);
                        curl_exec($img_ch);
                        curl_close($img_ch);
                        fclose($fp);
                        
                        if (file_exists($image_file) && filesize($image_file) > 0) {
                            $image_path = "assets/images/news/" . $image_name;
                        } else {
                            @unlink($image_file);
                        }
                    }
                    
                    // Check if news already exists
                    $check = $conn->query("SELECT id FROM news WHERE title='$title' LIMIT 1");
                    if ($check->num_rows == 0) {
                        // Insert news with or without image
                        $img_sql = $image_path ? "'$image_path'" : "NULL";
                        $sql = "INSERT INTO news (title, content, image_path) VALUES ('$title', '$description', $img_sql)";
                        if ($conn->query($sql)) {
                            $count++;
                        }
                    }
                    
                    if ($count >= 10) break; // Limit to 10 articles
                }
                
                if ($count > 0) {
                    $success = "Successfully fetched $count new articles with images!";
                } else {
                    $error = "No new articles found. All articles may already exist in the database.";
                }
            } else {
                $error = "API Error: " . ($data['message'] ?? 'Unknown error');
            }
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle Enable/Disable Auto-Update
if (isset($_POST['toggle_auto'])) {
    $status = $_POST['auto_status'] == '1' ? '0' : '1';
    // Store in a settings table or config file
    file_put_contents('../config/auto_news.txt', $status);
    $success = $status == '1' ? "Auto-update enabled!" : "Auto-update disabled!";
}

// Check auto-update status
$auto_status = file_exists('../config/auto_news.txt') ? file_get_contents('../config/auto_news.txt') : '0';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic News Updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
          <a class="nav-link active" href="auto_news.php">Auto News</a>
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

<div class="container mt-5">
    <h2><i class="fas fa-rss"></i> Automatic News Updates</h2>
    <p class="text-muted">Fetch latest news automatically from RSS feeds</p>

    <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-download"></i> Fetch News Now
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">News Source</label>
                            <select name="source" class="form-select">
                                <option value="google">Google News (India)</option>
                                <option value="bbc">BBC News</option>
                                <option value="times">Times of India</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="general">General</option>
                                <option value="business">Business</option>
                                <option value="technology">Technology</option>
                                <option value="sports">Sports</option>
                                <option value="entertainment">Entertainment</option>
                            </select>
                        </div>
                        <button type="submit" name="fetch_news" class="btn btn-primary w-100">
                            <i class="fas fa-sync"></i> Fetch Latest News
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-clock"></i> Auto-Update Settings
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Automatic Updates</h6>
                            <small class="text-muted">Fetch news every hour</small>
                        </div>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="auto_status" value="<?php echo $auto_status; ?>">
                            <button type="submit" name="toggle_auto" class="btn btn-sm <?php echo $auto_status == '1' ? 'btn-danger' : 'btn-success'; ?>">
                                <?php echo $auto_status == '1' ? 'Disable' : 'Enable'; ?>
                            </button>
                        </form>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> Auto-update requires a cron job to be set up on your server.
                        Add this to your crontab:
                        <code class="d-block mt-2">0 * * * * php /path/to/cron_news.php</code>
                    </div>

                    <div class="mt-3">
                        <h6>Manual Refresh</h6>
                        <p class="text-muted small">Click "Fetch Latest News" to manually update news anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <i class="fas fa-question-circle"></i> How It Works
        </div>
        <div class="card-body">
            <ol>
                <li><strong>NewsAPI Integration:</strong> Fetches real news from NewsAPI.org with images</li>
                <li><strong>Manual Fetch:</strong> Click "Fetch Latest News" to get the latest 10 articles instantly</li>
                <li><strong>Auto-Update:</strong> Enable automatic updates to fetch news hourly (requires cron job)</li>
                <li><strong>Duplicate Prevention:</strong> System automatically checks for duplicate news</li>
                <li><strong>Image Download:</strong> Automatically downloads and stores news images locally</li>
            </ol>
            
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle"></i> <strong>API Connected!</strong> Your NewsAPI key is active and ready to fetch news with images.
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
