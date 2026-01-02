<?php
include 'includes/db.php';

echo "Adding sample news articles...\n\n";

$sample_news = [
    [
        'title' => 'Breaking: Major Development in Technology Sector',
        'content' => 'In a significant development, tech companies are announcing new innovations that could revolutionize the industry. Experts predict this will have far-reaching implications for consumers and businesses alike.',
        'image' => null
    ],
    [
        'title' => 'Sports Update: Cricket Team Wins Championship',
        'content' => 'The national cricket team has secured a historic victory in the championship finals. The match saw spectacular performances from key players, thrilling fans across the nation.',
        'image' => null
    ],
    [
        'title' => 'Weather Alert: Heavy Rainfall Expected',
        'content' => 'Meteorological department has issued warnings for heavy rainfall in several regions. Residents are advised to take necessary precautions and stay updated with weather forecasts.',
        'image' => null
    ],
    [
        'title' => 'Economic Growth: Markets Show Positive Trends',
        'content' => 'Financial markets are showing encouraging signs of growth with major indices reaching new highs. Analysts attribute this to strong economic fundamentals and investor confidence.',
        'image' => null
    ],
    [
        'title' => 'Education Reform: New Policies Announced',
        'content' => 'The government has unveiled comprehensive education reforms aimed at improving quality and accessibility. The new policies focus on digital learning and skill development.',
        'image' => null
    ]
];

$count = 0;
foreach ($sample_news as $news) {
    $title = $conn->real_escape_string($news['title']);
    $content = $conn->real_escape_string($news['content']);
    
    $sql = "INSERT INTO news (title, content, image_path) VALUES ('$title', '$content', NULL)";
    if ($conn->query($sql)) {
        $count++;
        echo "✓ Added: {$news['title']}\n";
    }
}

echo "\n✅ Successfully added $count sample news articles!\n";
echo "\nYou can now:\n";
echo "1. View news at: news.php\n";
echo "2. Go to Admin Panel → Auto News to fetch real news from API\n";
echo "3. Or add custom news from Admin Panel → News\n";

$conn->close();
?>
