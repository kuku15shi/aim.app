<?php
include '../../includes/db.php';

// Add Trip
if (isset($_POST['add_trip'])) {
    $route_id = intval($_POST['route_id']);
    $trip_name = $conn->real_escape_string($_POST['trip_name']);
    $conn->query("INSERT INTO bus_trips (route_id, trip_name) VALUES ($route_id, '$trip_name')");
}

// Delete Trip
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bus_trips WHERE id=$id");
    header("Location: trips.php");
    exit();
}

// Fetch Trips
$trips = $conn->query("
    SELECT bt.*, br.route_name 
    FROM bus_trips bt 
    JOIN bus_routes br ON bt.route_id = br.id 
    ORDER BY bt.id DESC
");
$routes = $conn->query("SELECT * FROM bus_routes ORDER BY route_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trips</title>
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
                <i class="fas fa-bus fa-2x text-primary me-2"></i>
                <div class="main-text">Bus Admin</div>
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
        <h4 class="mb-4">Manage Bus Trips & Timings</h4>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5>Create New Trip / Bus Run</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Select Route</label>
                                <select name="route_id" class="form-select" required>
                                    <option value="">Choose Route...</option>
                                    <?php while($r = $routes->fetch_assoc()): ?>
                                        <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['route_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Trip Name / Bus ID</label>
                                <input type="text" name="trip_name" class="form-control" placeholder="e.g. 08:00 AM City Loop" required>
                            </div>
                            <button type="submit" name="add_trip" class="btn btn-warning w-100">Create Trip</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Trip Name</th>
                                        <th>Route</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $trips->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['trip_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['route_name']); ?></td>
                                        <td>
                                            <a href="trip_timings.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="fas fa-stopwatch me-1"></i> Timings
                                            </a>
                                            <a href="trips.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Delete this trip?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
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
