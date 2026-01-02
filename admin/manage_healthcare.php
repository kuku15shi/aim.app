<?php
include 'auth_check.php';
include '../includes/db.php';

// Handle Add/Edit/Delete Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_center'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $type = $conn->real_escape_string($_POST['type']);
        $address = $conn->real_escape_string($_POST['address']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $location = $conn->real_escape_string($_POST['location_url']);
        
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/images/healthcare/";
            if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
            $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "assets/images/healthcare/" . basename($target_file);
            }
        }

        $sql = "INSERT INTO healthcare_centers (name, type, address, phone, location_url, image_path) VALUES ('$name', '$type', '$address', '$phone', '$location', '$image_path')";
        $conn->query($sql);
    } elseif (isset($_POST['delete_center'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $conn->query("DELETE FROM healthcare_centers WHERE id='$id'");
    }
}

$result = $conn->query("SELECT * FROM healthcare_centers ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Healthcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Healthcare Centers</h2>
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add New Center</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_center" value="1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Type</label>
                        <select name="type" class="form-select" required>
                            <option value="government">Government</option>
                            <option value="private">Private</option>
                            <option value="laboratory">Laboratory</option>
                            <option value="clinic">Clinic</option>
                            <option value="medical_shop">Medical Shop</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Location Maps URL</label>
                        <input type="text" name="location_url" class="form-control" placeholder="Google Maps Link">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Center</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Existing Centers</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if($row['image_path']): ?>
                                <img src="../<?php echo $row['image_path']; ?>" width="50" height="50" style="object-fit:cover;">
                            <?php else: ?>
                                <span class="text-muted">No Img</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo strtoupper($row['type']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <a href="manage_doctors.php?center_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-user-md"></i> Doctors
                            </a>
                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this center?');">
                                <input type="hidden" name="delete_center" value="1">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
