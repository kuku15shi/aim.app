<?php
include '../../includes/db.php';

// Add Driver
if (isset($_POST['add_driver'])) {
    $type = $conn->real_escape_string($_POST['type']);
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $lat = $conn->real_escape_string($_POST['lat']);
    $lng = $conn->real_escape_string($_POST['lng']);
    $start = $conn->real_escape_string($_POST['start']);
    $end = $conn->real_escape_string($_POST['end']);

    $conn->query("INSERT INTO taxi_drivers (type, name, phone, lat, lng, timing_start, timing_end) VALUES ('$type', '$name', '$phone', '$lat', '$lng', '$start', '$end')");
}

// Delete Driver
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM taxi_drivers WHERE id=$id");
    header("Location: index.php");
    exit();
}

// Fetch Drivers
$drivers = $conn->query("SELECT * FROM taxi_drivers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Taxi/Auto Drivers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="app-container">
    
    <!-- Header -->
    <div class="sticky-top-area sticky-top">
        <div class="top-header">
            <div class="logo-section">
                <i class="fas fa-taxi fa-2x text-warning me-2"></i>
                <div class="main-text">Taxi Admin</div>
            </div>
            <div class="header-icons align-items-center">
                
                <a href="../../admin/index.php" class="text-muted"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper p-4">
        
        <div class="row">
            <!-- Add Driver Form -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Add Driver</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Vehicle Type</label>
                                <select name="type" class="form-select">
                                    <option value="auto">Auto Rickshaw</option>
                                    <option value="car">Car / Taxi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Driver Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="+91 99999 99999">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label>Start Time</label>
                                    <input type="time" name="start" class="form-control">
                                </div>
                                <div class="col-6 mb-3">
                                    <label>End Time</label>
                                    <input type="time" name="end" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Base Location (Lat/Lng)</label>
                                <div class="input-group mb-2">
                                    <input type="text" name="lat" id="lat" class="form-control" placeholder="Lat" required>
                                    <input type="text" name="lng" id="lng" class="form-control" placeholder="Lng" required>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="getLocation()">Get Current Location</button>
                            </div>
                            <button type="submit" name="add_driver" class="btn btn-primary w-100">Add Driver</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List Drivers -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Driver List</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Timings</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $drivers->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php if($row['type'] == 'auto'): ?>
                                                <span class="badge bg-warning text-dark"><i class="fas fa-shuttle-van"></i> Auto</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary"><i class="fas fa-car"></i> Car</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <small><?php echo date('h:i A', strtotime($row['timing_start'])); ?> - <?php echo date('h:i A', strtotime($row['timing_end'])); ?></small>
                                        </td>
                                        <td>
                                            <a href="edit_driver.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm rounded-circle me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Simple Delete only for now as requested -->
                                            <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Delete driver?');">
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

</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
        });
    } else {
        alert("Geolocation not supported");
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
