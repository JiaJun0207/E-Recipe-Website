<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <!-- Left Panel -->
        <div class="left-panel">
            <img src="assets/pic/forgot-password-image.png" alt="Forgot Password Image">
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="header">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
                <h1>Tasty Trio Recipe</h1>
            </div>

            <form method="POST" action="send-reset-link.php">
                <h2>Forgot Password</h2>
                <p>Enter your email address to receive a password reset link.</p>
                <input type="email" name="email" placeholder="Enter your email" required style="font-family: Poppins, sans-serif;">
                <button type="submit" name="request_reset" style="font-family: Poppins, sans-serif;">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>

