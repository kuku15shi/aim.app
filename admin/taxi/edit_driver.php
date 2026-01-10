<?php
include '../../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id == 0) {
    header("Location: index.php");
    exit();
}

// Fetch Driver
$driver = $conn->query("SELECT * FROM taxi_drivers WHERE id=$id")->fetch_assoc();
if(!$driver) die("Driver not found");

// Update Driver
if (isset($_POST['update_driver'])) {
    $type = $conn->real_escape_string($_POST['type']);
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $lat = $conn->real_escape_string($_POST['lat']);
    $lng = $conn->real_escape_string($_POST['lng']);
    $start = $conn->real_escape_string($_POST['start']);
    $end = $conn->real_escape_string($_POST['end']);

    $sql = "UPDATE taxi_drivers SET type='$type', name='$name', phone='$phone', lat='$lat', lng='$lng', timing_start='$start', timing_end='$end' WHERE id=$id";
    
    if($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Driver</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="app-container p-4">
    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-white">
            <h4>Edit Driver</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label>Vehicle Type</label>
                    <select name="type" class="form-select">
                        <option value="auto" <?php echo ($driver['type'] == 'auto') ? 'selected' : ''; ?>>Auto Rickshaw</option>
                        <option value="car" <?php echo ($driver['type'] == 'car') ? 'selected' : ''; ?>>Car / Taxi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Driver Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($driver['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($driver['phone']); ?>" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Start Time</label>
                        <input type="time" name="start" class="form-control" value="<?php echo $driver['timing_start']; ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label>End Time</label>
                        <input type="time" name="end" class="form-control" value="<?php echo $driver['timing_end']; ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Base Location (Lat/Lng)</label>
                    <div class="input-group mb-2">
                        <input type="text" name="lat" class="form-control" value="<?php echo $driver['lat']; ?>" required>
                        <input type="text" name="lng" class="form-control" value="<?php echo $driver['lng']; ?>" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="update_driver" class="btn btn-primary flex-grow-1">Update Driver</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
