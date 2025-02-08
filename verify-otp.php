<?php session_start(); // Start the session to retrieve the OTP and other session data ?>

<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Tasty Trio Recipe</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="assets/pic/LoginSignup.png" alt="OTP Verification Image">
        </div>

        <div class="right-panel">
            <div class="header">
                <div class="logo-container">
                    <img src="assets/pic/TastyTrioLogo.png" alt="Logo" class="logo">
                </div>
                <h1>Tasty Trio Recipe</h1>
            </div>
            <form method="POST" action="verify-otp.php">
                <h2>Verify OTP</h2>
                <!-- Fixed hidden input fields -->
                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                <input type="hidden" name="full_name" value="<?php echo isset($_GET['full_name']) ? htmlspecialchars($_GET['full_name']) : ''; ?>">
                <input type="hidden" name="user_img" value="<?php echo isset($_SESSION['user_img']) ? htmlspecialchars($_SESSION['user_img']) : ''; ?>">
                <input type="text" name="otp" placeholder="Enter OTP" required style="font-family: Poppins, sans-serif;">
                <button type="submit" name="verify_otp" style="font-family: Poppins, sans-serif;">Verify OTP</button>
            </form>
        </div>
    </div>

    <div id="toast-container"></div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
        $email = $_POST['email'];
        $otp = $_POST['otp'];
        $fullName = $_POST['full_name'];
        $userImg = $_POST['user_img'];
        $password = $_SESSION['password'];

        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
            $stmt = $conn->prepare("INSERT INTO Registered_User (userName, userEmail, userPass, userImg) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $fullName, $email, $password, $userImg);
                if ($stmt->execute()) {
                    echo "<script>
                            showToast('Account successfully created! Redirecting...', 'success');
                            setTimeout(function() { window.location.href = 'login.php'; }, 3000);
                          </script>";
                } else {
                    echo "<script>showToast('Database error: Failed to create account.', 'error');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>showToast('Database error: Statement preparation failed.', 'error');</script>";
            }
        } else {
            echo "<script>showToast('Invalid OTP. Please try again.', 'error');</script>";
        }
    }
    ?>
</body>
</html>