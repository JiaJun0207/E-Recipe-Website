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
    <title>Sign Up - Tasty Trio Recipe</title>
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, type) {
            let bgColorClass = type === 'success' ? 'bg-success' : 'bg-danger';
            let iconHTML = type === 'success' 
                ? '<i class="fas fa-spinner fa-spin me-2 text-white"></i>' 
                : '<i class="fas fa-times-circle me-2 text-white"></i>';
            
            let toastHTML = '<div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 1050;">' +
                '<div class="toast show align-items-center text-white ' + bgColorClass + ' border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="d-flex">' +
                '<div class="toast-body text-white">' + iconHTML + message + '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
            
            $("#toast-container").html(toastHTML);
            let toastElement = document.querySelector(".toast");
            let toast = new bootstrap.Toast(toastElement);
            toast.show();
        }
    </script>
    <style>
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }
        .toast {
            background-color: #28a745 !important;
            color: white !important;
        }
        a:hover{
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <img src="assets/pic/LoginSignup.png" alt="Sign Up Image">
    </div>
    <div class="right-panel">
        <div class="header">
            <div class="logo-container">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
            </div>
            <h1>Tasty Trio Recipe</h1>
        </div>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
            <h2>Sign Up</h2>
            <input type="text" name="full_name" placeholder="Full Name" required style="font-family: Poppins, sans-serif;">
            <input type="email" name="email" placeholder="Email" required style="font-family: Poppins, sans-serif;">
            <input type="password" name="password" placeholder="Password" required style="font-family: Poppins, sans-serif;">
            <label for="profile_image" style="font-family: Poppins, sans-serif;" >Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*" required style="font-family: Poppins, sans-serif;">
            <button type="submit" name="signup" style="font-family: Poppins, sans-serif;">Sign Up</button>
            <p>Already a member? <a href="index.php" style="font-family: Poppins, sans-serif;">Log in</a></p>
        </form>
    </div>
</div>

<div id="toast-container"></div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profileImage = $_FILES['profile_image'];
    $imagePath = 'uploads/' . basename($profileImage["name"]);
    
    if (move_uploaded_file($profileImage["tmp_name"], $imagePath)) {
        $_SESSION['user_img'] = $imagePath;
    } else {
        echo "<script>showToast('Failed to upload image. Please try again.', 'error');</script>";
    }

    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['password'] = $password;
    $_SESSION['full_name'] = $fullName;
    $_SESSION['email'] = $email;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chanjiajun321@gmail.com';
        $mail->Password = 'ivdg inba cphd pmlp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('chanjiajun321@gmail.com', 'Tasty Trio Recipe');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Tasty Trio Recipe Registration';
        $mail->Body    = "Your OTP for account verification is: <strong>$otp</strong>.";
        $mail->send();

        echo "<script>
                showToast('An OTP has been sent to your email. Please verify your account.', 'success');
                setTimeout(function() { window.location.href = 'verify-otp.php?email=$email&full_name=$fullName'; }, 3000);
              </script>";
    } catch (Exception $e) {
        echo "<script>showToast('Message could not be sent. Mailer Error: {$mail->ErrorInfo}', 'error');</script>";
    }
}
?>
</body>
</html>
