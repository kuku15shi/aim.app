<?php
include '../includes/db.php';

$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 0;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 0;
$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : 'all';

if($lat == 0 || $lng == 0) {
    echo json_encode(['error' => 'Invalid location']);
    exit;
}

$typeClause = ($type != 'all') ? "WHERE type = '$type'" : "";

// Simple Haversine formula for distance in km
$sql = "
    SELECT *, ( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
    FROM taxi_drivers 
    $typeClause
    HAVING distance < 50 
    ORDER BY distance ASC 
    LIMIT 20
";

$result = $conn->query($sql);
$drivers = [];

while($row = $result->fetch_assoc()) {
    $drivers[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'phone' => $row['phone'],
        'type' => $row['type'],
        'location_name' => $row['location_name'] ?? '', 
        'distance' => round($row['distance'], 2) . ' km',
        'timings' => date('h:i A', strtotime($row['timing_start'])) . ' - ' . date('h:i A', strtotime($row['timing_end']))
    ];
}

echo json_encode(['drivers' => $drivers]);
?>
