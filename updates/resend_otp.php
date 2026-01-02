<?php
session_start();
require_once 'includes/db_config.php';
require_once 'includes/send_email.php';

if (!isset($_SESSION['verify_email'])) {
    header("location: index.php");
    exit;
}

$email = $_SESSION['verify_email'];
$otp = sprintf("%06d", mt_rand(1, 999999));

// Update user with new OTP
$sql = "UPDATE users SET otp = ? WHERE email = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $otp, $email);
    
    if ($stmt->execute()) {
        // Send OTP Email
        $subject = "Resend: Verify Your Email - AIM";
        $message = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 5px; text-align: center;'>
                    <h2>Welcome Back to AIM (all in malapuram)!</h2>
                    <p>You requested a new OTP. Please use the following code to verify your email address:</p>
                    <h1 style='color: #0095f6; letter-spacing: 5px;'>$otp</h1>
                    <p>If you did not request this, please ignore this email.</p>
                </div>
            </div>";

        if(sendEmail($email, $subject, $message)) {
             $_SESSION['message'] = "A new OTP has been sent to your email.";
        } else {
             $_SESSION['error'] = "Failed to send OTP email. Please check configuration.";
        }
    } else {
        $_SESSION['error'] = "Database error. Please try again.";
    }
    $stmt->close();
}
$conn->close();

header("location: verify_otp.php");
exit;
?>
