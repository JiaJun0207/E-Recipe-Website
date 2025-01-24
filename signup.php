<?php include 'db.php'; ?>

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

        // Check if the file was uploaded properly
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $profileImage = $_FILES['profile_image'];
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($profileImage["name"]);

            // Ensure the uploads directory exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (move_uploaded_file($profileImage["tmp_name"], $targetFile)) {
                // Insert user into the database
                $stmt = $conn->prepare("INSERT INTO Registered_User (userName, userEmail, userPass, userImg) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fullName, $email, $password, $targetFile);
                if ($stmt->execute()) {
                    // Success, show a toast and redirect
                    echo "<script>
                            alert('Account created successfully!');
                            window.location.href = 'login.php'; // Redirect to login page
                          </script>";
                } else {
                    echo "<script>
                            alert('Error creating account. Please try again.');
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Failed to upload profile image.');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('No profile image uploaded or an error occurred.');
                  </script>";
        }
    }
    ?>
</body>
</html>