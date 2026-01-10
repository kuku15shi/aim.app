<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Taxi or Auto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        .taxi-pulse-dot {
            width: 10px;
            height: 10px;
            background-color: #ffc107;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            animation: pulse-yellow 1.5s infinite;
        }

        @keyframes pulse-yellow {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        .location-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: rgba(255, 193, 7, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 193, 7, 0.3);
            margin-bottom: 25px;
        }

        .driver-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #ffc107;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
            box-shadow: 0 4px 15px var(--shadow-color);
        }
        
        .driver-card:hover {
            transform: scale(1.02);
        }

        .call-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
            transition: 0.3s;
        }

        .call-btn:hover {
            transform: scale(1.1);
            background: #27ae60;
            color: white;
        }
        
        .vehicle-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .vehicle-btn {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--input-bg);
            color: var(--text-dark);
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .vehicle-btn.active {
            background: #ffc107;
            color: #000;
            font-weight: bold;
            border-color: #ffc107;
        }

        .skeleton {
            background: linear-gradient(90deg, rgba(127, 140, 141, 0.1) 25%, rgba(127, 140, 141, 0.2) 50%, rgba(127, 140, 141, 0.1) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
            height: 20px;
            margin-bottom: 10px;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body>

<div class="app-container">
    
    <!-- Sticky Header -->
    <div class="sticky-top-area sticky-top">
        <div class="top-header">
            <div class="logo-section">
                <a href="../transportation/index.php" class="text-decoration-none text-dark me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <div class="d-flex align-items-center">
                    <i class="fas fa-taxi fa-lg text-warning me-2"></i>
                    <div>
                        <div class="main-text">EcoRide</div>
                        <div class="sub-text">TAXI & AUTO</div>
                    </div>
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
        
        <div class="location-status">
            <div class="d-flex align-items-center">
                <div class="taxi-pulse-dot" id="gps-dot"></div>
                <span id="location-text" style="font-size: 0.9rem;">Locating you...</span>
            </div>
        </div>

        <div class="vehicle-toggle">
            <div class="vehicle-btn active" onclick="filterDrivers('auto', this)">
                <i class="fas fa-shuttle-van"></i> Auto
            </div>
            <div class="vehicle-btn" onclick="filterDrivers('car', this)">
                <i class="fas fa-car-side"></i> Car
            </div>
            <div class="vehicle-btn" onclick="filterDrivers('all', this)">
                <i class="fas fa-layer-group"></i> All
            </div>
        </div>

        <div id="driver-list">
            <!-- Drivers injected here -->
        </div>

    </div>

    <!-- Spacer for Bottom Nav -->
    <div style="height: 100px;"></div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="updates/" class="nav-item">
            <i class="fas fa-bullhorn"></i>
            <span>Updates</span>
        </a>
        <a href="#" class="nav-item active">
            <div class="emergency-circle" style="background: linear-gradient(135deg, #f1c40f, #f39c12);">
                <i class="fas fa-taxi"></i>
            </div>
            <span>Ride</span>
        </a>
        <a href="news.php" class="nav-item">
            <i class="fas fa-newspaper"></i>
            <span>News</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-shopping-bag"></i>
            <span>Shop</span>
        </a>
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>

<script>
let gpsLat = null;
let gpsLng = null;
let currentType = 'auto'; // Default

document.addEventListener('DOMContentLoaded', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                gpsLat = position.coords.latitude;
                gpsLng = position.coords.longitude;
                updateLocationStatus("Location Found", true);
                fetchDrivers();
            },
            (error) => {
                updateLocationStatus("GPS Failed. Using City Center.", false);
                gpsLat = 12.9716; // Default fallback
                gpsLng = 77.5946;
                fetchDrivers();
            }
        );
    } else {
        updateLocationStatus("GPS Not Supported.", false);
    }
});

function updateLocationStatus(msg, isLive) {
    const locText = document.getElementById('location-text');
    const dot = document.getElementById('gps-dot');
    
    locText.innerHTML = msg;
    if(isLive) {
        dot.style.animation = "pulse-yellow 1.5s infinite";
        dot.style.backgroundColor = "#ffc107";
    } else {
        dot.style.animation = "none";
        dot.style.backgroundColor = "gray";
    }
}

function filterDrivers(type, btn) {
    // Update active button
    document.querySelectorAll('.vehicle-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    currentType = type;
    fetchDrivers();
}

function fetchDrivers() {
    const list = document.getElementById('driver-list');
    
    if(!gpsLat || !gpsLng) return;

    // Loading State
    list.innerHTML = `
        <div class="skeleton" style="height: 80px;"></div>
        <div class="skeleton" style="height: 80px;"></div>
        <div class="skeleton" style="height: 80px;"></div>
    `;

    fetch(`api_taxi.php?lat=${gpsLat}&lng=${gpsLng}&type=${currentType}`)
    .then(response => response.json())
    .then(data => {
        if(data.error) {
            list.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
        }

        if(data.drivers.length === 0) {
            list.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-sad-tear fa-3x mb-3 opacity-50"></i>
                    <p>No ${currentType == 'all' ? 'drivers' : currentType + 's'} found nearby right now.</p>
                </div>
            `;
            return;
        }

        let html = '';
        data.drivers.forEach(driver => {
            let icon = driver.type === 'auto' ? 'fa-shuttle-van' : 'fa-car-side';
            let badgeColor = driver.type === 'auto' ? 'bg-warning text-dark' : 'bg-primary text-white';
            
            html += `
                <div class="driver-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-center">
                            <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas ${icon} fa-lg text-dark"></i>
                            </div>
                            <span class="badge ${badgeColor} mt-2" style="font-size: 0.6rem;">${driver.distance}</span>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">${driver.name}</h5>
                            ${driver.location_name ? `<div class="small text-muted mb-1"><i class="fas fa-map-pin text-warning"></i> ${driver.location_name}</div>` : ''}
                            <div class="small text-muted"><i class="far fa-clock"></i> ${driver.timings}</div>
                            <div class="small text-success"><i class="fas fa-check-circle"></i> Available</div>
                        </div>
                    </div>
                    <a href="tel:${driver.phone}" class="call-btn">
                        <i class="fas fa-phone-alt"></i>
                    </a>
                </div>
            `;
        });
        
        list.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        list.innerHTML = '<p class="text-center text-danger">Failed to load drivers.</p>';
    });
}
</script>

</body>
</html>
