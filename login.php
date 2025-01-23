<?php include 'db.php'; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <link href='http://fonts.googleapis.com/css?family=Poppins' rel='stylesheet' type='text/css'>
    <title>Log In - Tasty Trio Recipe</title>
</head>
<body>
    <div class="container">
        <!-- Left Panel with Image -->
        <div class="left-panel">
            <img src="assets/pic/LoginSignup.png" alt="Log In Image">
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
            <form method="POST" action="login.php">
                <h2>Log In</h2>
                <input type="email" name="email" placeholder="Email" style="font-family: Poppins, sans-serif;" required>
                <input type="password" name="password" placeholder="Password" style="font-family: Poppins, sans-serif;" required>
                <button type="submit" name="login" style="font-family: Poppins, sans-serif;">Log In</button>
                <p>Don’t have an account? <a href="signup.php">Sign Up</a></p>
            </form>
        </div>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT userPass FROM Registered_User WHERE userEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_email'] = $email;
            echo "<p>Login successful! <a href='index.php'>Go to Home</a></p>";
        } else {
            echo "<p>Invalid email or password.</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>