<?php
session_start();

// Admin Credentials
$adminID = "admin";
$adminPassword = "TCC";

// Handle Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["admin_login"])) {
    $inputID = $_POST["admin_id"];
    $inputPassword = $_POST["admin_password"];

    if ($inputID === $adminID && $inputPassword === $adminPassword) {
        $_SESSION["admin_logged_in"] = true;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Invalid Admin ID or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><strong>Admin Log In - Tasty Trio Recipe</strong></title>
    <link href='http://fonts.googleapis.com/css?family=Poppins:wght@600' rel='stylesheet' type='text/css'>
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
            width: 50px; /* Smaller logo */
            height: auto;
        }
        .logo-container h3 {
            font-size: 18px; /* Reduce title font size */
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
            <label><strong>Admin ID</strong></label>
            <input type="text" name="admin_id" class="form-control" required>
            <label><strong>Password</strong></label>
            <input type="password" name="admin_password" class="form-control" required>
            <button type="submit" name="admin_login"><strong>Log In</strong></button>
        </form>
        <?php if (isset($error)) echo "<p class='error'><strong>$error</strong></p>"; ?>
        <p class="back-link"><a href="login.php"><strong>Back to User Login</strong></a></p>
    </div>
</div>

</body>
</html>