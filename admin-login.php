<?php
session_start();
include 'db.php'; // Include database connection

$error = ""; // Initialize error message

// Handle Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["admin_login"])) {
    $inputName = trim($_POST["admin_name"]); // Trim spaces to prevent login issues
    $inputPassword = trim($_POST["admin_password"]);

    if (!empty($inputName) && !empty($inputPassword)) {
        // Fetch admin credentials from the database
        $query = "SELECT adminID, adminPass FROM admin WHERE adminName = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $inputName);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($adminID, $hashedPassword);
            $stmt->fetch();

            // Verify password
            if ($stmt->num_rows > 0) {
                if (password_verify($inputPassword, $hashedPassword)) {
                    $_SESSION["admin_logged_in"] = true;
                    $_SESSION["admin_id"] = $adminID;
                    header("Location: admin-dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password. Please try again.";
                }
            } else {
                $error = "Admin not found. Please check your credentials.";
            }
            $stmt->close();
        } else {
            $error = "Database error: Unable to prepare statement.";
        }
    } else {
        $error = "Please enter both Admin Name and Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log In - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/loginandsignup.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #e75480, #8a2be2);
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-box {
            display: flex;
            width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left-panel {
            width: 50%;
            background: #8a2be2;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .left-panel img {
            width: 80%;
            max-width: 250px;
        }
        .right-panel {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo-container img {
            width: 50px;
            height: auto;
        }
        .logo-container h3 {
            font-size: 18px;
            color: #8a2be2;
            font-weight: bold;
        }
        .right-panel h2 {
            color: #8a2be2;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .right-panel form {
            width: 100%;
        }
        .right-panel button {
            background-color: #8a2be2;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .back-link a {
            color: #8a2be2;
            text-decoration: none;
            font-weight: bold;
        }
        label, input, p {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="admin-box">
    <div class="left-panel">
        <img src="assets/pic/admin-login.png" alt="Admin Illustration">
    </div>
    <div class="right-panel">
        <div class="logo-container">
            <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
            <h3><strong>Tasty Trio Recipe</strong></h3>
        </div>
        <h2><strong>Admin Log In</strong></h2>
        <form method="POST" action="admin-login.php">
            <label><strong>Admin Name</strong></label>
            <input type="text" name="admin_name" class="form-control" required>
            <label><strong>Password</strong></label>
            <input type="password" name="admin_password" class="form-control" required>
            <button type="submit" name="admin_login"><strong>Log In</strong></button>
        </form>
        <?php if (!empty($error)) echo "<p class='error'><strong>$error</strong></p>"; ?>
        <p class="back-link"><a href="login.php"><strong>Back to User Login</strong></a></p>
    </div>
</div>

</body>
</html>



