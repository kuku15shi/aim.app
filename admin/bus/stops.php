<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../includes/db.php';

// Handle Add Stop
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stop'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $lat = $conn->real_escape_string($_POST['lat']);
    $lng = $conn->real_escape_string($_POST['lng']);
    
    $conn->query("INSERT INTO bus_stops (name, lat, lng) VALUES ('$name', '$lat', '$lng')");
}

// Handle Delete Stop
// ... (Logic simplified for brevity, assume secure context for admin)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bus_stops WHERE id=$id");
    header("Location: stops.php");
    exit();
}

$stops = $conn->query("SELECT * FROM bus_stops ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stops</title>
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
        <h4 class="mb-4">Manage Bus Stops</h4>

        <div class="row">
            <!-- Add Stop -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h5 class="mb-0">Add New Stop</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Stop Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Central Station">
                            </div>
                            <div class="mb-3">
                                <label>Latitude</label>
                                <input type="text" name="lat" id="lat" class="form-control" required placeholder="12.9716">
                            </div>
                            <div class="mb-3">
                                <label>Longitude</label>
                                <input type="text" name="lng" id="lng" class="form-control" required placeholder="77.5946">
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mb-3 w-100" onclick="getLocation()">
                                <i class="fas fa-location-arrow"></i> Use My Location
                            </button>
                            <button type="submit" name="add_stop" class="btn btn-primary w-100">Add Stop</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List Stops -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $stops->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                        <td>
                                            <small class="text-muted d-block"><?php echo $row['lat']; ?>, <?php echo $row['lng']; ?></small>
                                            <a href="https://www.google.com/maps?q=<?php echo $row['lat']; ?>,<?php echo $row['lng']; ?>" target="_blank" class="text-decoration-none" style="font-size: 0.8rem;">
                                                View Map
                                            </a>
                                        </td>
                                        <td>
                                            <a href="stops.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Delete this stop?');">
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
        <a href="stops.php" class="nav-item active">
            <i class="fas fa-map-marker-alt"></i>
            <span>Stops</span>
        </a>
        <a href="routes.php" class="nav-item">
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
<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        alert("Geolocation is not supported by this browser.");
    }
}
function showPosition(position) {
    document.getElementById("lat").value = position.coords.latitude;
    document.getElementById("lng").value = position.coords.longitude;
}
</script>

</body>
</html>
