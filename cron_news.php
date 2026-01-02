<?php
// Cron job script to automatically fetch news
// Add to crontab: 0 * * * * php /path/to/cron_news.php

include 'includes/db.php';

// Check if auto-update is enabled
$auto_status = file_exists('config/auto_news.txt') ? file_get_contents('config/auto_news.txt') : '0';

if ($auto_status != '1') {
    echo "Auto-update is disabled.\n";
    exit;
}

echo "Starting automatic news fetch...\n";

$api_key = '56eb73fca33849e79e49ec38b7276302';

try {
    // Using NewsAPI.org
    $url = "https://newsapi.org/v2/top-headlines?country=in&category=general&pageSize=10&apiKey={$api_key}";
    
    // Fetch data using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200) {
        echo "Failed to fetch news. HTTP Code: $http_code\n";
        exit;
    }
    
    $data = json_decode($response, true);
    
    if ($data['status'] == 'ok' && isset($data['articles'])) {
        $count = 0;
        
        foreach ($data['articles'] as $article) {
            $title = $article['title'];
            $description = $article['description'] ?? 'No description available.';
            $image_url = $article['urlToImage'] ?? null;
            
            // Clean and validate content
            $title = trim($title);
            $description = trim($description);
            
            // Skip if title is empty or contains [Removed]
            if (empty($title) || strpos($title, '[Removed]') !== false) {
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
            if ($image_url) {
                $image_dir = "assets/images/news/";
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
                    echo "Added: $title\n";
                }
            }
        }
        
        echo "Successfully added $count new articles!\n";
    } else {
        echo "API Error: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn->close();
?>
