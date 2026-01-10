<?php
include '../includes/db.php';

// Fetch all stops into an array effectively to use multiple times
$stopsList = [];
$res = $conn->query("SELECT * FROM bus_stops ORDER BY name ASC");
while($r = $res->fetch_assoc()) {
    $stopsList[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bus Connect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Bus Page Specific Styles */
        .bus-pulse-dot {
            width: 10px;
            height: 10px;
            background-color: #00f2fe;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 0 0 0 rgba(0, 242, 254, 0.7);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 242, 254, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 242, 254, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 242, 254, 0); }
        }

        .location-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: rgba(79, 172, 254, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(79, 172, 254, 0.3);
            margin-bottom: 25px;
        }

        .dest-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px var(--shadow-color);
            border: 1px solid var(--border-color);
        }

        .btn-find {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            border-radius: 12px;
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .btn-find:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3);
            color: white;
        }

        .bus-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #00f2fe;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
            box-shadow: 0 4px 15px var(--shadow-color);
        }
        
        .bus-card:hover {
            transform: scale(1.02);
        }

        .bus-time {
            font-size: 1.5rem;
            font-weight: 700;
            color: #00f2fe;
        }
        
        .bus-route {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* Result Section transition */
        .result-section {
            opacity: 0;
            transform: translateY(20px);
            transition: 0.5s all ease;
        }
        .result-section.visible {
            opacity: 1;
            transform: translateY(0);
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

        /* Switch Toggle */
        .form-check-input {
            width: 3em; 
            height: 1.5em;
            cursor: pointer;
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
                    <i class="fas fa-bus-alt fa-lg text-primary me-2"></i>
                    <div>
                        <div class="main-text">SmartBus</div>
                        <div class="sub-text">LIVE TRACKING</div>
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
                <div class="bus-pulse-dot" id="gps-dot"></div>
                <span id="location-text" style="font-size: 0.9rem;">Auto-Detect Location</span>
            </div>
            <div class="form-check form-switch ms-3">
                <input class="form-check-input" type="checkbox" id="manualLocationToggle">
                <label class="form-check-label small" for="manualLocationToggle">Manual</label>
            </div>
        </div>

        <div class="dest-card mb-4">
            
            <!-- Manual Start Location Input (Hidden by default) -->
            <div id="manual-start-container" class="mb-3" style="display: none;">
                <label class="text-muted small mb-2 text-uppercase"><i class="fas fa-map-pin text-danger"></i> Start Location</label>
                <select id="start-location" class="form-select form-select-lg mb-3" style="background-color: var(--input-bg); color: var(--text-dark); border: 1px solid var(--border-color);">
                    <option value="">Choose Starting Point...</option>
                    <?php foreach($stopsList as $stop): ?>
                        <option value="<?php echo $stop['id']; ?>" data-lat="<?php echo $stop['lat']; ?>" data-lng="<?php echo $stop['lng']; ?>">
                            <?php echo htmlspecialchars($stop['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <hr style="border-color: var(--border-color);">
            </div>

            <h3 class="mb-4" style="font-weight: 600;">Where to?</h3>
            
            <div class="mb-3">
                <label class="text-muted small mb-2 text-uppercase">Select Destination</label>
                <select id="destination" class="form-select form-select-lg" style="background-color: var(--input-bg); color: var(--text-dark); border: 1px solid var(--border-color);">
                    <option value="">Choose Stop...</option>
                    <?php foreach($stopsList as $stop): ?>
                        <option value="<?php echo $stop['id']; ?>"><?php echo htmlspecialchars($stop['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button class="btn-find" onclick="findBus()">
                <i class="fas fa-search-location me-2"></i> Find Buses
            </button>
        </div>

        <div id="results" class="result-section">
            <!-- Results injected here -->
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
            <div class="emergency-circle">
                <i class="fas fa-bus"></i>
            </div>
            <span>Bus</span>
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
let isManual = false;

// Auto Detect Location on Load
document.addEventListener('DOMContentLoaded', () => {
    // Check GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                gpsLat = position.coords.latitude;
                gpsLng = position.coords.longitude;
                updateLocationStatus("GPS Connected", true);
            },
            (error) => {
                updateLocationStatus("GPS Failed. Switch to Manual.", false);
            }
        );
    } else {
        updateLocationStatus("GPS Not Supported.", false);
    }
    
    // Toggle Logic
    const toggle = document.getElementById('manualLocationToggle');
    const manualContainer = document.getElementById('manual-start-container');
    
    toggle.addEventListener('change', function() {
        isManual = this.checked;
        if(isManual) {
            manualContainer.style.display = 'block';
            updateLocationStatus("Manual Mode Active", false);
        } else {
            manualContainer.style.display = 'none';
            if(gpsLat) {
                updateLocationStatus("GPS Connected", true);
            } else {
                updateLocationStatus("Waiting for GPS...", false);
            }
        }
    });
});

function updateLocationStatus(msg, isLive) {
    const locText = document.getElementById('location-text');
    const dot = document.getElementById('gps-dot');
    
    locText.innerHTML = msg;
    if(isLive) {
        locText.style.color = "#00f2fe";
        dot.style.backgroundColor = "#00f2fe";
        dot.style.animation = "pulse 1.5s infinite";
    } else {
        locText.style.color = "var(--text-muted)";
        dot.style.backgroundColor = "gray";
        dot.style.animation = "none";
    }
}

function findBus() {
    const destId = document.getElementById('destination').value;
    const resultsDiv = document.getElementById('results');
    
    let searchLat, searchLng;

    if(!destId) {
        alert("Please select a destination");
        return;
    }

    // Determine coordinates based on mode
    if (isManual) {
        const startSelect = document.getElementById('start-location');
        const selectedOption = startSelect.options[startSelect.selectedIndex];
        
        if(!startSelect.value) {
            alert("Please select a Start Location");
            return;
        }
        
        searchLat = selectedOption.getAttribute('data-lat');
        searchLng = selectedOption.getAttribute('data-lng');
    } else {
        // GPS Mode
        if(!gpsLat || !gpsLng) {
            alert("GPS location not found yet. Please wait or switch to Manual Mode.");
            return;
        }
        searchLat = gpsLat;
        searchLng = gpsLng;
    }

    // Show Loading
    resultsDiv.innerHTML = `
        <h5 class="mb-3 text-muted">Searching Routes...</h5>
        <div class="skeleton" style="width: 60%; height: 30px;"></div>
        <div class="skeleton" style="height: 100px;"></div>
        <div class="skeleton" style="height: 100px;"></div>
    `;
    resultsDiv.classList.add('visible');

    // Fetch API
    fetch(`api_bus.php?lat=${searchLat}&lng=${searchLng}&dest_id=${destId}`)
    .then(response => response.json())
    .then(data => {
        if(data.error) {
            resultsDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
        }

        let html = `
            <div class="mb-4 p-3 rounded-3" style="background: rgba(0, 242, 254, 0.1); border: 1px solid rgba(0, 242, 254, 0.2);">
                <small class="text-muted d-block uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">NEAREST STOP FROM START</small>
                <h3 class="my-2" style="font-weight: 700;">${data.nearest_stop.name}</h3>
                <a href="https://www.google.com/maps?q=${data.nearest_stop.lat},${data.nearest_stop.lng}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                    <i class="fas fa-location-arrow me-1"></i> Navigate to Location
                </a>
            </div>
            <h5 class="mb-3 text-muted">Available Buses</h5>
        `;

        if(data.routes.length === 0) {
            html += `<div class="text-center py-4 text-muted">
                        <i class="fas fa-bus-alt fa-3x mb-3 opacity-50"></i>
                        <p>No buses found connecting these locations.</p>
                     </div>`;
        } else {
            data.routes.forEach(route => {
                html += `
                    <div class="bus-card">
                        <div>
                            <div class="bus-time">${route.start_time}</div>
                            <div class="bus-route badge bg-secondary">${route.route_name}</div>
                            <div class="small text-muted mt-1">Trip: ${route.trip_name}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-info fw-bold mb-1"><i class="fas fa-hourglass-half me-1"></i> ${route.duration}</div>
                            <small class="text-muted" style="font-size: 0.8rem;">Arrives: ${route.dest_time}</small>
                        </div>
                    </div>
                `;
            });
        }

        resultsDiv.innerHTML = html;
    })
    .catch(err => {
        resultsDiv.innerHTML = `<p class="text-danger">Error loading data. Please try again.</p>`;
        console.error(err);
    });
}
</script>

</body>
</html>
