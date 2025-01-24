<?php session_start(); // Start the session to retrieve the OTP and other session data ?>

<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <title>Verify OTP - Tasty Trio Recipe</title>
</head>
<body>
    <div class="container">
        <!-- Left Panel with Image -->
        <div class="left-panel">
            <img src="assets/pic/LoginSignup.png" alt="OTP Verification Image">
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

            <!-- OTP Verification Form -->
            <form method="POST" action="verify-otp.php">
                <h2>Verify OTP</h2>
                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
                <input type="hidden" name="full_name" value="<?php echo isset($_GET['full_name']) ? $_GET['full_name'] : ''; ?>">
                <input type="hidden" name="user_img" value="<?php echo isset($_SESSION['user_img']) ? $_SESSION['user_img'] : ''; ?>">
                <input type="text" name="otp" placeholder="Enter OTP" style="font-family: Poppins, sans-serif;" required>
                <button type="submit" name="verify_otp" style="font-family: Poppins, sans-serif;">Verify OTP</button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
        $email = $_POST['email'];
        $otp = $_POST['otp'];
        $fullName = $_POST['full_name'];
        $userImg = $_POST['user_img']; // Retrieve image path from session
        $password = $_SESSION['password']; // Get the password from the session

        // Check if the OTP entered matches the one stored in session
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
            // Insert user data into the Registered_User table
            $stmt = $conn->prepare("INSERT INTO Registered_User (userName, userEmail, userPass, userImg) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullName, $email, $password, $userImg); // Bind data
            $stmt->execute();
            $stmt->close();

            // Success message and redirect to login page
            echo "<script>
                    alert('Account successfully created! You can now log in.');
                    window.location.href = 'login.php'; // Redirect to login page
                  </script>";
        } else {
            // Invalid OTP
            echo "<script>
                    alert('Invalid OTP. Please try again.');
                  </script>";
        }
    }
    ?>
</body>
</html>