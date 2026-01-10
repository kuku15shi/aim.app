<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - AIM</title>
    <?php
    // Dynamically determine base URL (e.g., /home/ or /project/home/)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    $base_url = rtrim($path, '/\\') . '/'; 
    ?>
    <base href="<?php echo $base_url; ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script>
        // Pass PHP resolved path to JS
        window.AIM_LOGO_URL = "<?php echo $base_url; ?>assets/logo/aim-3d.glb";
    </script>
    <style>
        .error-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: var(--primary-bg);
            overflow: hidden;
        }
        
        #error-3d-scene {
            width: 300px;
            height: 300px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--sage-green), var(--text-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0.2;
            position: absolute;
            z-index: 0;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .error-msg {
            z-index: 1;
            position: relative;
        }

        .btn-home {
            margin-top: 30px;
            padding: 12px 30px;
            border-radius: 30px;
            background: var(--text-dark);
            color: var(--primary-bg);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .btn-home:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: var(--green-accent);
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-code">404</div>
        
        <!-- 3D Logo Container -->
        <div id="error-3d-scene"></div>

        <div class="error-msg">
            <h2 class="mb-3">Oops! Page Not Found</h2>
            <p class="text-muted mb-4">The page you are looking for might have been removed or is temporarily unavailable.</p>
            <a href="/home/" class="btn-home">Go Back Home</a>
        </div>
    </div>

    <!-- Three.js & Custom Script -->
    <script type="module" src="assets/js/error_3d.js"></script>

</body>
</html>
