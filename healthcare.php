<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Modernized Styles maintaining Sage Theme */
        .healthcare-body {
            background-color: var(--primary-bg) !important;
            min-height: 100vh;
        }
        .header-title {
            text-align: center;
            font-weight: 800;
            margin-top: 20px;
            font-size: 28px;
            background: linear-gradient(45deg, #2d3436, #000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
        }
        .search-container {
            padding: 0 20px 25px;
        }
        .search-box {
            background: var(--glass-bg);
            border-radius: 20px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        .search-box:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            margin-left: 10px;
            font-size: 16px;
            background: transparent;
            color: var(--text-dark);
        }
        .menu-container {
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: var(--card-bg);
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            min-height: 70vh;
            margin-top: 10px;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.05);
        }
        .menu-btn {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 24px;
            border-radius: 24px;
            font-size: 16px;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .menu-btn::after {
            content: '\f054';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            opacity: 0.3;
            transition: transform 0.3s ease;
        }
        .menu-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: var(--text-muted);
        }
        .menu-btn:hover::after {
            transform: translateX(5px);
            opacity: 0.8;
            color: var(--text-muted);
        }
        
        [data-theme="dark"] .header-title {
            background: linear-gradient(45deg, #fff, #a7b0b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        [data-theme="dark"] .menu-btn {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-color: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }
        [data-theme="dark"] .menu-btn:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="healthcare-body">

<div class="app-container" style="background: transparent; box-shadow: none;">
    
    <div class="d-flex align-items-center p-3">
        <a href="index.php" class="text-dark me-3" style="font-size: 24px;"><i class="fas fa-arrow-left"></i></a>
    </div>

    <h1 class="header-title">HEALTHCARE</h1>

    <div class="search-container mt-3">
        <div class="search-box">
            <i class="fas fa-search text-muted"></i>
            <input type="text" placeholder="Search......">
        </div>
    </div>

    <div class="menu-container">
        <a href="healthcare_list.php?type=government" class="menu-btn">GOVERNMENT</a>
        <a href="healthcare_list.php?type=private" class="menu-btn">PRIVATE</a>
        <a href="healthcare_list.php?type=laboratory" class="menu-btn">LABORATORY</a>
        <a href="healthcare_list.php?type=clinic" class="menu-btn">CLINIC</a>
        <a href="healthcare_list.php?type=medical_shop" class="menu-btn">MEDICAL SHOP</a>
    </div>

    <!-- Padding for Bottom Nav -->
    <div style="height: 100px;"></div>

<!-- Emergency Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Emergency Contacts</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="list-group list-group-flush">
            <a href="tel:100" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-user-shield fa-lg"></i></div>
                <div><h6 class="mb-0">Police Control Room</h6><small class="text-muted">Tap to Call: 100</small></div>
            </a>
            <a href="tel:108" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-danger"><i class="fas fa-ambulance fa-lg"></i></div>
                <div><h6 class="mb-0">Ambulance Service</h6><small class="text-muted">Tap to Call: 108</small></div>
            </a>
            <a href="tel:101" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-warning"><i class="fas fa-fire-extinguisher fa-lg"></i></div>
                <div><h6 class="mb-0">Fire Force</h6><small class="text-muted">Tap to Call: 101</small></div>
            </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="index.php" class="nav-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-bullhorn"></i>
        <span>Updates</span>
    </a>
    <a href="#" class="nav-item emergency-item" data-bs-toggle="modal" data-bs-target="#emergencyModal">
        <div class="emergency-circle">
            <i class="fas fa-bell"></i>
        </div>
        <span>Emergency</span>
    </a>
    <a href="news.php" class="nav-item">
        <i class="fas fa-newspaper"></i>
        <span>News</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-shopping-bag"></i>
        <span>Shopping</span>
    </a>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
