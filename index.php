<?php 
include 'db.php'; 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Tasty Trio Recipe</title>
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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }
        .toast {
            font-size: 16px;
            border-radius: 8px;
            padding: 10px;
            background-color: #28a745 !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="assets/pic/LoginSignup.png" alt="Log In Image">
        </div>
        <div class="right-panel">
            <div class="header">
                <div class="logo-container">
                    <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
                </div>
                <h1>Tasty Trio Recipe</h1>
            </div>
            <form method="POST" action="index.php">
                <h2>Log In</h2>
                <input type="email" name="email" placeholder="Email" required style="font-family: Poppins, sans-serif;">
                <input type="password" name="password" placeholder="Password" required style="font-family: Poppins, sans-serif;">
                <button type="submit" name="login" style="font-family: Poppins, sans-serif;">Log In</button>
                <p>Don’t have an account? <a href="signup.php" style="font-family: Poppins, sans-serif;">Sign Up</a></p>
                <p>or</p>
                <p>Continue as <a href="home.php" style="font-family: Poppins, sans-serif;">Guest</a></p>
                <p><a href="http://localhost/E-Recipe-Website/forgot-password.php" style="font-family: Poppins, sans-serif;">Forgot Password?</a></p>
                <p><a href="admin-login.php" style="font-family: Poppins, sans-serif;">Admin Login</a></p>
            </form>
        </div>
    </div>

    <div id="toast-container"></div>

    <?php
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT userID, userPass FROM Registered_User WHERE userEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($userID, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['userID'] = $userID;
            $_SESSION['user_email'] = $email;

            echo "<script>
                    showToast('Login Successful! Redirecting...', 'success');
                    setTimeout(function() { window.location.href = 'home.php'; }, 2000);
                  </script>";
        } else {
            echo "<script>
                    showToast('Invalid email or password.', 'error');
                  </script>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>




