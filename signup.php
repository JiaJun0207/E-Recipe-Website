<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
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
    <form method="POST" action="signup.php">
        <h2>Sign Up</h2>
        <input type="text" name="full_name" placeholder="Full Name" style="font-family: Poppins, sans-serif;" required>
        <input type="email" name="email" placeholder="Email" style="font-family: Poppins, sans-serif;" required>
        <input type="password" name="password" placeholder="Password" style="font-family: Poppins, sans-serif;" required>
        <button type="submit" name="signup" style="font-family: Poppins, sans-serif;">Sign Up</button>
        <p>Already a member? <a href="login.php">Log in</a></p>
    </form>
</div>

    <?php
    if (isset($_POST['signup'])) {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO Registered_User (userName, userEmail, userPass) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullName, $email, $password);

        if ($stmt->execute()) {
            echo "<p>Sign up successful! <a href='login.php'>Log in</a></p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>