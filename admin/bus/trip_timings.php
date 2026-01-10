<?php
include '../../includes/db.php';

$trip_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($trip_id == 0) die("Invalid Trip ID");

// Fetch Trip & Route Info
$trip = $conn->query("
    SELECT bt.*, br.route_name 
    FROM bus_trips bt 
    JOIN bus_routes br ON bt.route_id = br.id 
    WHERE bt.id = $trip_id
")->fetch_assoc();

if (!$trip) die("Trip not found");

// Handle Time Updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_timings'])) {
    // Delete existing timings for this trip to avoid complexity (or UPDATE/INSERT ON DUPLICATE)
    // Simple approach: Delete all for this trip_id and re-insert non-empty ones.
    $conn->query("DELETE FROM bus_timings WHERE trip_id = $trip_id");

    foreach ($_POST['timings'] as $stop_id => $time) {
        if (!empty($time)) {
            $stop_id = intval($stop_id);
            $time = $conn->real_escape_string($time);
            $conn->query("INSERT INTO bus_timings (trip_id, stop_id, arrival_time) VALUES ($trip_id, $stop_id, '$time')");
        }
    }
    $success = "Timings updated successfully!";
}

// Fetch Route Stops (In Order)
$route_stops = $conn->query("
    SELECT brs.stop_id, brs.stop_order, bs.name 
    FROM bus_route_stops brs 
    JOIN bus_stops bs ON brs.stop_id = bs.id 
    WHERE brs.route_id = {$trip['route_id']} 
    ORDER BY brs.stop_order ASC
");

// Fetch Existing Timings
$existing_timings = [];
$timings_res = $conn->query("SELECT stop_id, arrival_time FROM bus_timings WHERE trip_id = $trip_id");
while($row = $timings_res->fetch_assoc()) {
    // Format time to HH:MM for input type="time"
    $existing_timings[$row['stop_id']] = date('H:i', strtotime($row['arrival_time']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Timings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="app-container">
    
    <!-- Header -->
    <div class="sticky-top-area sticky-top">
        <div class="top-header">
            <div class="logo-section">
                <!-- Back arrow in header -->
                <a href="trips.php" class="text-dark me-2"><i class="fas fa-arrow-left"></i></a>
                <div class="main-text">Edit Timings</div>
                <div class="sub-text ms-2"><?php echo htmlspecialchars($trip['trip_name']); ?></div>
            </div>
            <div class="header-icons align-items-center">
                <div id="theme-toggle" class="theme-toggle-pill me-2">
                    <i class="fas fa-sun" id="theme-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper p-4">
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0">
                <h5 class="mb-0">Route: <?php echo htmlspecialchars($trip['route_name']); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order</th>
                                    <th>Stop Name</th>
                                    <th>Arrival Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($route_stops->num_rows > 0):
                                    while($rs = $route_stops->fetch_assoc()): 
                                        $val = isset($existing_timings[$rs['stop_id']]) ? $existing_timings[$rs['stop_id']] : '';
                                ?>
                                <tr>
                                    <td><span class="badge bg-secondary rounded-pill"><?php echo $rs['stop_order']; ?></span></td>
                                    <td><?php echo htmlspecialchars($rs['name']); ?></td>
                                    <td>
                                        <input type="time" name="timings[<?php echo $rs['stop_id']; ?>]" class="form-control" value="<?php echo $val; ?>">
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No stops assigned to this route. <a href="route_details.php?id=<?php echo $trip['route_id']; ?>">Add Stops first</a>.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" name="update_timings" class="btn btn-warning w-100 mt-3">Save Timings</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Spacer -->
    <div style="height: 100px;"></div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="../../admin/index.php" class="nav-item">
            <i class="fas fa-arrow-left"></i>
            <span>Exit</span>
        </a>
        <a href="index.php" class="nav-item">
            <i class="fas fa-th-large"></i>
            <span>Dash</span>
        </a>
        <a href="stops.php" class="nav-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Stops</span>
        </a>
        <a href="routes.php" class="nav-item">
            <i class="fas fa-route"></i>
            <span>Routes</span>
        </a>
        <a href="trips.php" class="nav-item active">
            <i class="fas fa-clock"></i>
            <span>Trips</span>
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>
