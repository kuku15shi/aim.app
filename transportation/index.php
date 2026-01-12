<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="transportation-body">

    <div class="app-container">
        
        <!-- Sticky Header -->
        <div class="sticky-top-area sticky-top" style="background: var(--card-bg); z-index: 1000;">
            <div class="top-header">
                <div class="logo-section">
                    <a href="../index.php" class="text-decoration-none text-dark me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <i class="fas fa-taxi fa-sm"></i>
                        </div>
                        <div>
                            <div class="main-text">TRANSPORTATION</div>
                            <div class="sub-text">SERVICES</div>
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
            

        <!-- Transportation Grid -->
        <div class="transport-grid">
            <!-- Cars -->
            <a href="taxi.php" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-car-side"></i>
                </div>
                <span class="item-label">Cars</span>
            </a>

            <!-- Autorickshaw -->
            <a href="taxi.php" class="transport-item">
                <div class="icon-box">
                    <!-- Icon for Auto/Rickshaw - using taxi/shuttle as fallback or custom SVG if needed later -->
                    <i class="fas fa-taxi"></i>
                </div>
                <span class="item-label">Autorickshaw</span>
            </a>

            <!-- Bus -->
            <a href="bus.php" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-bus"></i>
                </div>
                <span class="item-label">Bus</span>
            </a>

            <!-- Kasrtc -->
            <!-- Kasrtc -->
            <a href="ksrtc.php" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-bus-simple"></i>
                </div>
                <span class="item-label">KSRTC</span>
            </a>

            <!-- Train -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-train"></i>
                </div>
                <!-- Corrected 'Trin' to Train -->
                <span class="item-label">Train</span>
            </a>

            <!-- Flight -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-plane"></i>
                </div>
                <!-- Corrected 'Fligt' to Flight -->
                <span class="item-label">Flight</span>
            </a>

            <!-- Lorry -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-truck"></i>
                </div>
                <span class="item-label">Lorry</span>
            </a>

            <!-- Pickup -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-truck-pickup"></i>
                </div>
                <span class="item-label">Pickup</span>
            </a>

            <!-- Construction -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-hard-hat"></i>
                </div>
                <span class="item-label">Construction</span>
            </a>

            <!-- Tourist -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
                <!-- Corrected 'Tourst' to Tourist -->
                <span class="item-label">Tourist</span>
            </a>

            <!-- Rent -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-key"></i>
                </div>
                <span class="item-label">Rent</span>
            </a>

            <!-- Emergency -->
            <a href="#" class="transport-item">
                <div class="icon-box">
                    <i class="fas fa-truck-medical"></i>
                </div>
                <!-- Corrected 'Emergncy' to Emergency -->
                <span class="item-label">Emergency</span>
            </a>

            <!-- Other Button -->
            <div class="other-btn-container">
                <a href="#" class="other-btn">Other</a>
            </div>
        </div>

        <!-- Ad Banner -->
        <div class="ad-banner">
             <!-- Placeholder banner matching the dark theme in the image -->
             <div style="background: #2d3436; color: white; padding: 30px 20px; border-radius: 20px; text-align: center;">
                 <h3 style="margin: 0; font-weight: 700;">NEW RECIPES NOW AVAILABLE!</h3>
                 <p style="font-size: 10px; opacity: 0.7;">THE BEST POSTERS WITH SEASONAL FRUITS</p>
             </div>
        </div>

        <!-- Padding for Nav -->
        <div style="height: 100px;"></div>

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <a href="../index.php" class="nav-item">
                <i class="fas fa-home"></i>
                <span style="font-size: 9px;">Home</span>
            </a>
            <a href="../updates/" class="nav-item">
                <i class="fas fa-bullhorn"></i>
                <span style="font-size: 9px;">Updates</span>
            </a>
            
            <div class="nav-item">
                 <div class="emergency-circle">
                     <i class="fas fa-lightbulb"></i> <!-- Using lightbulb/siren icon -->
                 </div>
                 <span style="font-size: 9px; margin-top: 5px;">Emergency</span>
            </div>

            <a href="../news.php" class="nav-item">
                <i class="fas fa-newspaper"></i>
                <span style="font-size: 9px;">News</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-shopping-bag"></i>
                <span style="font-size: 9px;">Shopping</span>
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
