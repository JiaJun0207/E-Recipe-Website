<?php 
include 'db.php'; 
session_start(); // Start the session to store the OTP

// PHPMailer library
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <title>Sign Up - Tasty Trio Recipe</title>
</head>
<body>
<div class="container">
    <!-- Left Panel with Image -->
    <div class="left-panel">
        <img src="assets/pic/LoginSignup.png" alt="Sign Up Image">
    </div>
    <!-- Right Panel with Form -->
    <div class="right-panel">
        <!-- Header with Logo and Title -->
        <div class="header">
            <div class="logo-container">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
            </div>
            <h1>Tasty Trio Recipe</h1>
        </div>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
            <h2>Sign Up</h2>
            <input type="text" name="full_name" placeholder="Full Name" style="font-family: Poppins, sans-serif;" required>
            <input type="email" name="email" placeholder="Email" style="font-family: Poppins, sans-serif;" required>
            <input type="password" name="password" placeholder="Password" style="font-family: Poppins, sans-serif;" required>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit" name="signup" style="font-family: Poppins, sans-serif;">Sign Up</button>
            <p>Already a member? <a href="login.php">Log in</a></p>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        // Handle image upload
        $profileImage = $_FILES['profile_image'];
        $imagePath = 'uploads/' . basename($profileImage["name"]);
        
        // Move the uploaded image to the uploads directory
        if (move_uploaded_file($profileImage["tmp_name"], $imagePath)) {
            // Store the image path in the session
            $_SESSION['user_img'] = $imagePath;
        } else {
            // Handle error if the image upload fails
            echo "<script>alert('Failed to upload image. Please try again.');</script>";
        }

        // Generate OTP
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // Store OTP and other data in session
        $_SESSION['otp'] = $otp;
        $_SESSION['password'] = $password; // Store the hashed password in the session
        $_SESSION['full_name'] = $fullName;
        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();                                         // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                           // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'chanjiajun321@gmail.com';                 // SMTP username
            $mail->Password = 'ivdg inba cphd pmlp';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
            $mail->Port = 587;                                        // TCP port to connect to

            // Recipients
            $mail->setFrom('chanjiajun321@gmail.com', 'Tasty Trio Recipe');
            $mail->addAddress($email); // Add the recipient's email address

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Your OTP for Tasty Trio Recipe Registration';
            $mail->Body    = "Your OTP for account verification is: <strong>$otp</strong>.";

            $mail->send();

            // If email sent successfully, redirect to OTP verification page
            echo "<script>
                    alert('An OTP has been sent to your email. Please verify your account.');
                    window.location.href = 'verify-otp.php?email=$email&full_name=$fullName'; // Redirect to OTP verification page
                  </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    ?>
</body>
</html>