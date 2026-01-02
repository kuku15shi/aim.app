<?php
session_start();
require_once 'includes/db_config.php';

if (!isset($_SESSION['verify_email'])) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['verify_email'];
    $otp_input = trim($_POST['otp']);

    $sql = "SELECT id, otp FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $otp_db);
            $stmt->fetch();
            
            if ($otp_input === $otp_db) {
                // Verify Success
                $update_sql = "UPDATE users SET is_verified = 1, otp = NULL WHERE id = ?";
                if ($update_stmt = $conn->prepare($update_sql)) {
                    $update_stmt->bind_param("i", $id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    $_SESSION['message'] = "Email verified successfully! You can now login.";
                    unset($_SESSION['verify_email']);
                    header("location: index.php");
                    exit;
                }
            } else {
                $error = "Invalid OTP. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Verify Your Email</h3>
                         <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info"><?php echo $_SESSION['message']; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <p>We sent an OTP to <strong><?php echo htmlspecialchars($_SESSION['verify_email']); ?></strong></p>
                        
                        <form action="verify_otp.php" method="POST">
                            <input type="text" name="otp" class="form-control mb-3 text-center" placeholder="Enter 6-digit OTP" maxlength="6" required>
                            <button type="submit" class="btn btn-primary w-100">Verify</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p class="text-muted">Didn't receive the code? <a href="resend_otp.php" class="text-decoration-none">Resend OTP</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
