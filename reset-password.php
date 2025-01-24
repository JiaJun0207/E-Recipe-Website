<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <!-- Left Panel -->
        <div class="left-panel">
            <img src="assets/pic/reset-password-image.png" alt="Reset Password Image">
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="header">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
                <h1>Tasty Trio Recipe</h1>
            </div>

            <form method="POST" action="update-password.php">
                <h2>Reset Password</h2>
                <p>Enter a new password for your account.</p>
                <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                <input type="password" name="new_password" placeholder="Enter new password" required style="font-family: Poppins, sans-serif;">
                <input type="password" name="confirm_password" placeholder="Confirm new password" required style="font-family: Poppins, sans-serif;">
                <button type="submit" name="reset_password" style="font-family: Poppins, sans-serif;">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>

