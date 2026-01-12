<?php
include '../includes/db.php';

header('Content-Type: application/json');

$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 0;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 0;
$dest_id = isset($_GET['dest_id']) ? intval($_GET['dest_id']) : 0;

if ($lat == 0 || $lng == 0 || $dest_id == 0) {
    echo json_encode(['error' => 'Missing location or destination']);
    exit;
}

// 1. Find Nearest Stop (Haversine Formula)
// 6371 km radius
$sql = "
    SELECT *, ( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance 
    FROM bus_stops 
    ORDER BY distance ASC 
    LIMIT 1
";
$nearest_stop = $conn->query($sql)->fetch_assoc();

if (!$nearest_stop) {
    echo json_encode(['error' => 'No bus stops found.']);
    exit;
}

// If nearest stop is too far (e.g. > 50km), maybe warn? (Skipping for now)

// 2. Find Routes from Nearest Stop -> Destination
// We need routes where Nearest Stop Order < Destination Stop Order
$start_id = $nearest_stop['id'];

$routes_sql = "
    SELECT r.id, r.route_name, 
           s1.stop_order as start_order, s2.stop_order as end_order
    FROM bus_routes r
    JOIN bus_route_stops s1 ON r.id = s1.route_id AND s1.stop_id = $start_id
    JOIN bus_route_stops s2 ON r.id = s2.route_id AND s2.stop_id = $dest_id
    WHERE s1.stop_order < s2.stop_order
";

// Optional: Filter by type if provided
if(isset($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $routes_sql = str_replace("WHERE", "WHERE r.type = '$type' AND", $routes_sql);
}

$routes_res = $conn->query($routes_sql);

$results = [];
$current_time = date('H:i:s');

while($route = $routes_res->fetch_assoc()) {
    $route_id = $route['id'];
    
    // 3. Find Next Trips for this Route
    // Get timings for the start stop
    $timings_sql = "
        SELECT bt.arrival_time as start_time, trips.trip_name, trips.id as trip_id
        FROM bus_timings bt
        JOIN bus_trips trips ON bt.trip_id = trips.id
        WHERE bt.stop_id = $start_id 
          AND trips.route_id = $route_id
          AND bt.arrival_time >= '$current_time'
        ORDER BY bt.arrival_time ASC
        LIMIT 3
    ";
    
    $timings = $conn->query($timings_sql);
    
    while($trip = $timings->fetch_assoc()) {
        // Find arrival time at destination
        $dest_time_sql = "SELECT arrival_time FROM bus_timings WHERE trip_id = {$trip['trip_id']} AND stop_id = $dest_id";
        $dest_time_res = $conn->query($dest_time_sql)->fetch_assoc();
        $dest_time = $dest_time_res ? $dest_time_res['arrival_time'] : 'N/A';

        // Calculate estimated duration
        $start_dt = new DateTime($trip['start_time']);
        $end_dt = ($dest_time != 'N/A') ? new DateTime($dest_time) : null;
        $duration = ($end_dt) ? $start_dt->diff($end_dt)->format('%h h %i m') : 'Unknown';

        // Map Link (Google Maps Directions)
        // From User Location -> Start Stop -> Destination Stop
        // But simply showing stop location is enough per requirements
        
        $results[] = [
            'route_name' => $route['route_name'],
            'trip_name' => $trip['trip_name'],
            'start_time' => date('h:i A', strtotime($trip['start_time'])),
            'dest_time' => ($dest_time != 'N/A') ? date('h:i A', strtotime($dest_time)) : 'N/A',
            'duration' => $duration
        ];
    }
}

// Sort by earliest start time
usort($results, function($a, $b) {
    return strtotime($a['start_time']) - strtotime($b['start_time']);
});

echo json_encode([
    'nearest_stop' => $nearest_stop,
    'routes' => $results
]);
?>
