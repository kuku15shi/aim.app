<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Services</title>
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
<body class="government-body">

    <div class="app-container" style="background: transparent; box-shadow: none;">
        
        <!-- Header -->
        <div class="header-area">
            <a href="../index.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
            <div class="header-title-gov">GOVERNMENT SVC</div>
            <div style="width: 20px;"></div> <!-- Spacer -->
        </div>

        <!-- Government Grid -->
        <div class="gov-grid">
            
            <!-- Aadhar -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-id-card"></i>
                </div>
                <span class="item-label">Aadhar</span>
            </a>

            <!-- Ration Card -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-address-card"></i>
                </div>
                <span class="item-label">Ration Card</span>
            </a>

            <!-- Voters ID -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-check-to-slot"></i>
                </div>
                <span class="item-label">Voters ID</span>
            </a>

            <!-- Birth Cert -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-baby"></i>
                </div>
                <span class="item-label">Birth Cert</span>
            </a>

            <!-- Death Cert -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-file-signature"></i>
                </div>
                <span class="item-label">Death Cert</span>
            </a>

            <!-- Marriage Cert -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-ring"></i>
                </div>
                <span class="item-label">Marriage Cert</span>
            </a>

            <!-- Taxes -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <span class="item-label">Taxes</span>
            </a>

            <!-- Electricity -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="item-label">Electricity</span>
            </a>

            <!-- Water -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-faucet-drip"></i>
                </div>
                <span class="item-label">Water</span>
            </a>

            <!-- Police -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <span class="item-label">Police</span>
            </a>

            <!-- Passport -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-passport"></i>
                </div>
                <span class="item-label">Passport</span>
            </a>

            <!-- Post Office -->
            <a href="#" class="gov-item">
                <div class="icon-box">
                    <i class="fas fa-envelope"></i>
                </div>
                <span class="item-label">Post Office</span>
            </a>

            <!-- Other Button -->
            <div class="other-btn-container">
                <a href="#" class="other-btn">Other</a>
            </div>
        </div>

        <!-- Ad Banner -->
        <div class="ad-banner">
             <!-- Placeholder banner matching the theme -->
             <div style="background: #2d3436; color: white; padding: 30px 20px; border-radius: 20px; text-align: center;">
                 <h3 style="margin: 0; font-weight: 700;">DIGITAL INDIA</h3>
                 <p style="font-size: 10px; opacity: 0.7;">ACCESS ALL SERVICES ONLINE</p>
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
                     <i class="fas fa-lightbulb"></i>
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
