<?php
include 'auth_check.php';
include '../includes/db.php';

if (!isset($_GET['center_id'])) {
    header("Location: manage_healthcare.php");
    exit();
}

$center_id = intval($_GET['center_id']);
$center_res = $conn->query("SELECT * FROM healthcare_centers WHERE id=$center_id");
$center = $center_res->fetch_assoc();

if (!$center) {
    die("Center not found.");
}

// Handle Add/Delete Doctor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_doctor'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $qualification = $conn->real_escape_string($_POST['qualification']);
        $specialty = $conn->real_escape_string($_POST['specialty']);
        $designation = $conn->real_escape_string($_POST['designation']);
        $department = $conn->real_escape_string($_POST['department']);

        $sql = "INSERT INTO doctors (center_id, name, qualification, specialty, designation, department) VALUES ('$center_id', '$name', '$qualification', '$specialty', '$designation', '$department')";
        $conn->query($sql);
    } elseif (isset($_POST['delete_doctor'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $conn->query("DELETE FROM doctors WHERE id='$id'");
    }
}

$doctors_res = $conn->query("SELECT * FROM doctors WHERE center_id=$center_id ORDER BY department, name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors - <?php echo htmlspecialchars($center['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Doctors</h2>
            <h5 class="text-muted"><?php echo htmlspecialchars($center['name']); ?></h5>
        </div>
        <a href="manage_healthcare.php" class="btn btn-secondary">Back to Centers</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">Add New Doctor</div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="add_doctor" value="1">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Doctor Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Dr. John Doe">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control" placeholder="e.g. MBBS, MD">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control" placeholder="e.g. General Surgery">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Specialty (Optional)</label>
                        <input type="text" name="specialty" class="form-control" placeholder="e.g. Cardiologist">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Designation (Optional)</label>
                        <input type="text" name="designation" class="form-control" placeholder="e.g. Professor & HOD">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Doctor</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Doctor List</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qualification</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $doctors_res->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['qualification']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td><?php echo htmlspecialchars($row['designation']); ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Remove doctor?');">
                                <input type="hidden" name="delete_doctor" value="1">
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
