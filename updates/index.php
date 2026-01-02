<?php 
session_start(); 
require_once 'includes/db_config.php'; 

// Check if logged in, redirect to feed.php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font for Logo -->
    <link href="https://fonts.googleapis.com/css2?family=Grand+Hotel&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex align-items-center min-vh-100 position-relative">
    <div style="position: absolute; top: 20px; right: 20px;">
        <div id="theme-toggle" class="theme-toggle-pill">
            <i class="fas fa-sun" id="theme-icon"></i>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            
            <!-- Phone / Preview Section (Hidden on mobile) -->
            <div class="col-md-6 d-none d-md-block text-center" style="position: relative;">
                 <!-- Placeholder for phone mockup or grid -->
                 <div class="row g-2" style="transform: rotate(-2deg); opacity: 0.9;">
                    <?php
                     // Fetch thumbnails for preview
                     $sql_preview = "SELECT image_url FROM posts WHERE approval_status = 'approved' ORDER BY created_at DESC LIMIT 6";
                     $res_preview = $conn->query($sql_preview);
                     if($res_preview && $res_preview->num_rows > 0) {
                         while($rw = $res_preview->fetch_assoc()) {
                             $ext = strtolower(pathinfo($rw['image_url'], PATHINFO_EXTENSION));
                             if(!in_array($ext, ['mp4','mov','avi'])) {
                                 echo '<div class="col-6"><img src="'.htmlspecialchars($rw['image_url']).'" class="img-fluid rounded shadow-sm border" style="object-fit: cover; height: 250px; width: 100%;"></div>';
                             }
                         }
                     } else {
                         // Fallback placeholders
                         echo '<div class="col-6"><img src="https://via.placeholder.com/300x500?text=Preview+1" class="img-fluid rounded shadow-sm"></div>';
                         echo '<div class="col-6"><img src="https://via.placeholder.com/300x500?text=Preview+2" class="img-fluid rounded shadow-sm"></div>';
                     }
                    ?>
                 </div>
            </div>

            <!-- Login / Auth Section -->
            <div class="col-md-5">
                
                <!-- Main Auth Card -->
                <div class="login-card text-center">
                    <span class="brand-logo">Updates</span>
                    
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success p-2 small"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger p-2 small"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="login.php" method="POST" id="loginForm">
                        <div class="mb-2">
                            <input type="text" class="form-control auth-input" name="username_email" placeholder="Phone number, username, or email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control auth-input" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Log In</button>
                    </form>

                     <!-- Divider -->
                    <div class="or-divider">OR</div>

                    <!-- Register Form (Hidden by default, or just toggle visibility) -->
                    <!-- Simpler approach: JavaScript toggle -->
                    <form action="register.php" method="POST" id="registerForm" style="display:none;">
                         <div class="mb-2">
                            <input type="email" class="form-control auth-input" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control auth-input" name="username" placeholder="Full Name (Username)" required>
                        </div>
                         <div class="mb-2">
                            <input type="text" class="form-control auth-input" name="username" placeholder="Username" style="display:none;"> <!-- Hidden duplicate field to satisfy simple backend? No, backend expects 'username' -->
                            <!-- Re-using name='username' implies backend uses it. -->
                        </div> 
                        
                        <div class="mb-3">
                            <input type="password" class="form-control auth-input" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        
                        <p class="small text-muted mt-3">By signing up, you agree to our Terms.</p>
                    </form>

                    <a href="#" class="text-decoration-none small" style="color:#00376b">Forgot password?</a>
                </div>

                <!-- Toggle Card -->
                <div class="login-card text-center py-3">
                    <span id="toggleText">Don't have an account? <a href="#" class="fw-bold text-decoration-none" style="color:#0095f6" onclick="toggleAuth(event)">Sign up</a></span>
                </div>

                <div class="text-center">
                    <p class="small text-muted">Get the app.</p>
                    <div class="d-flex justify-content-center gap-2">
                         <img src="https://static.cdninstagram.com/rsrc.php/v3/yt/r/Yfc020cRR_q.png" height="40">
                         <img src="https://static.cdninstagram.com/rsrc.php/v3/yu/r/EHY6QnZYdNX.png" height="40">
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        function toggleAuth(e) {
            e.preventDefault();
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const toggleText = document.getElementById('toggleText');
            
            if (loginForm.style.display === 'none') {
                // Switch to Login
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                toggleText.innerHTML = 'Don\'t have an account? <a href="#" class="fw-bold text-decoration-none" style="color:#0095f6" onclick="toggleAuth(event)">Sign up</a>';
            } else {
                // Switch to Register
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                toggleText.innerHTML = 'Have an account? <a href="#" class="fw-bold text-decoration-none" style="color:#0095f6" onclick="toggleAuth(event)">Log in</a>';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>