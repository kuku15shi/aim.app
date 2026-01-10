<?php
include '../../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS (Main Theme) -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="app-container">
    
    <!-- Sticky Header -->
    <div class="sticky-top-area sticky-top">
        <div class="top-header">
            <div class="logo-section">
                <!-- Use FontAwesome icon as logo if image missing -->
                <i class="fas fa-bus fa-2x text-primary me-2"></i>
                <div>
                    <div class="main-text">Bus Admin</div>
                    <div class="sub-text">MANAGEMENT PANEL</div>
                </div>
            </div>
            <div class="header-icons align-items-center">
                <div id="theme-toggle" class="theme-toggle-pill me-2">
                    <i class="fas fa-sun" id="theme-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper p-4">
        <h3 class="mb-4">Dashboard</h3>
        
        <div class="row g-4">
            <!-- Manage Stops -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4 border-0" onclick="window.location='stops.php'">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Bus Stops</h5>
                        <p class="card-text text-muted">Add, edit, or delete bus stops and coordinates.</p>
                    </div>
                </div>
            </div>

            <!-- Manage Routes -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4 border-0" onclick="window.location='routes.php'">
                    <div class="card-body">
                        <i class="fas fa-route fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Bus Routes</h5>
                        <p class="card-text text-muted">Create routes and assign stops to them.</p>
                    </div>
                </div>
            </div>

            <!-- Manage Timings -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4 border-0" onclick="window.location='trips.php'">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Bus Timings</h5>
                        <p class="card-text text-muted">Schedule trips and set arrival times.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preview App Link -->
        <div class="text-center mt-5">
            <a href="../../transportation/bus.php" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-mobile-alt me-2"></i> Preview User App
            </a>
        </div>
    </div>

    <!-- Spacer for Bottom Nav -->
    <div style="height: 100px;"></div>

    <!-- Liquid Glass Bottom Nav -->
    <div class="bottom-nav">
        <a href="../../admin/index.php" class="nav-item">
            <i class="fas fa-arrow-left"></i>
            <span>Exit</span>
        </a>
        <a href="index.php" class="nav-item active">
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
        <a href="trips.php" class="nav-item">
            <i class="fas fa-clock"></i>
            <span>Trips</span>
        </a>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../../assets/js/script.js"></script>

</body>
</html>
