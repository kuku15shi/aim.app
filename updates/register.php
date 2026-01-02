<?php
session_start();
require_once 'includes/db_config.php';
require_once 'includes/send_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill all fields.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Insert user with otp and is_verified = 0
        $sql = "INSERT INTO users (username, email, password_hash, otp, is_verified, status) VALUES (?, ?, ?, ?, 0, 'active')";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_username, $param_email, $param_hash, $param_otp);
            $param_username = $username;
            $param_email = $email;
            $param_hash = $password_hash;
            $param_otp = $otp;

            try {
                if ($stmt->execute()) {
                    // Send OTP Email
                    $subject = "Verify Your Email - AIMPRO";
                    $message = "
                        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;'>
                            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 5px; text-align: center;'>
                                <h2>Welcome to AIM (all in malapuram)!</h2>
                                <p>Thank you for registering. Please use the following OTP to verify your email address:</p>
                                <h1 style='color: #0095f6; letter-spacing: 5px;'>$otp</h1>
                                <p>If you did not request this, please ignore this email.</p>
                            </div>
                        </div>";

                    // Use mail() for simplicity as first step. in production use PHPMailer
                    $sendResult = sendEmail($email, $subject, $message);
                    if($sendResult === true) {
                         $_SESSION['message'] = "Registration successful! OTP sent to your email: $email";
                         $_SESSION['verify_email'] = $email;
                         header("location: verify_otp.php");
                         exit;
                    } else {
                         // Fallback/Error
                         $_SESSION['message'] = "Registration successful, but email failed. Error: " . $sendResult . ". OTP: " . $otp; 
                         $_SESSION['verify_email'] = $email;
                         header("location: verify_otp.php");
                         exit;
                    }

                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                     $_SESSION['error'] = "The username or email is already taken.";
                } else {
                     $_SESSION['error'] = "Something went wrong: " . $e->getMessage();
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
    if(isset($_SESSION['error'])) {
        header("location: index.php");
        exit;
    }
}
?>