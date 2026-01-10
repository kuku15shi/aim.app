<?php
include '../../includes/db.php';

$route_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($route_id == 0) die("Invalid Route ID");

$route = $conn->query("SELECT * FROM bus_routes WHERE id=$route_id")->fetch_assoc();

// Add Stop to Route
if (isset($_POST['add_stop_to_route'])) {
    $stop_id = intval($_POST['stop_id']);
    $order = intval($_POST['order']);
    $conn->query("INSERT INTO bus_route_stops (route_id, stop_id, stop_order) VALUES ($route_id, $stop_id, $order)");
}

// Remove Link
if (isset($_GET['remove'])) {
    $link_id = intval($_GET['remove']);
    $conn->query("DELETE FROM bus_route_stops WHERE id=$link_id");
    header("Location: route_details.php?id=$route_id");
    exit();
}

$linked_stops = $conn->query("
    SELECT brs.id as link_id, brs.stop_order, bs.name, bs.lat, bs.lng 
    FROM bus_route_stops brs 
    JOIN bus_stops bs ON brs.stop_id = bs.id 
    WHERE brs.route_id = $route_id 
    ORDER BY brs.stop_order ASC
");

$all_stops = $conn->query("SELECT * FROM bus_stops ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Route Stops</title>
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
                <!-- Back arrow in header specifically for this deep page -->
                <a href="routes.php" class="text-dark me-2"><i class="fas fa-arrow-left"></i></a>
                <div class="main-text"><?php echo htmlspecialchars($route['route_name']); ?></div>
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
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5>Add Stop to Route</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Select Stop</label>
                                <select name="stop_id" class="form-select" required>
                                    <option value="">Choose...</option>
                                    <?php while($s = $all_stops->fetch_assoc()): ?>
                                        <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Order Number (Sequence)</label>
                                <input type="number" name="order" class="form-control" placeholder="e.g. 1, 2, 3" required>
                            </div>
                            <button type="submit" name="add_stop_to_route" class="btn btn-primary w-100">Add to Route</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5>Stops in Order</h5>
                        <?php if ($linked_stops->num_rows > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php while($ls = $linked_stops->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                <div>
                                    <span class="badge bg-secondary rounded-pill me-2"><?php echo $ls['stop_order']; ?></span>
                                    <strong><?php echo htmlspecialchars($ls['name']); ?></strong>
                                </div>
                                <a href="route_details.php?id=<?php echo $route_id; ?>&remove=<?php echo $ls['link_id']; ?>" class="btn btn-sm btn-outline-danger" style="border:none;">
                                    <i class="fas fa-times"></i> Remove
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php else: ?>
                            <p class="text-muted mt-3">No stops assigned to this route yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
        <a href="routes.php" class="nav-item active">
            <i class="fas fa-route"></i>
            <span>Routes</span>
        </a>
        <a href="trips.php" class="nav-item">
            <i class="fas fa-clock"></i>
            <span>Trips</span>
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>
