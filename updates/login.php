<?php
session_start();
require_once 'includes/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username_email = trim($_POST["username_email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, email, password_hash, status, role, is_verified, profile_pic FROM users WHERE username = ? OR email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $param_id, $param_id);
        $param_id = $username_email;

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $email_db, $password_hash, $status, $role, $is_verified, $profile_pic);
                if ($stmt->fetch()) {
                    
                    if (password_verify($password, $password_hash)) {
                        
                        if ($is_verified == 1) {
                             if ($status === 'active') {
                                // Successful Login
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["role"] = $role;
                                $_SESSION["profile_pic"] = !empty($profile_pic) ? $profile_pic : 'uploads/profiles/default.png';
                                
                                // Redirect based on role
                                if ($role === 'admin') {
                                     header("location: admin/index.php");
                                } else {
                                     header("location: feed.php");
                                }
                                exit;
                            } else {
                                $_SESSION['error'] = "Account is suspended or pending approval.";
                            }
                        } else {
                            $_SESSION['verify_email'] = $email_db; 
                            $_SESSION['error'] = "Please verify your email address first. We've sent you a new OTP.";
                            
                            // Optional: Auto-resend OTP here could be good, but let's just redirect to verify page which can have a resend button
                            header("location: verify_otp.php");
                            exit;
                        }
                       
                    } else {
                        $_SESSION['error'] = "The password you entered was not valid.";
                    }
                }
            } else {
                $_SESSION['error'] = "No account found with that username or email.";
            }
        } 
        $stmt->close();
    }
    $conn->close();
    header("location: index.php");
    exit;
}
?>