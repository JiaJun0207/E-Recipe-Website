<?php
require 'db.php'; // Add your database connection details

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send reset email using PHPMailer
function sendResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Use your email provider's SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chanjiajun321@gmail.com'; // Your email
        $mail->Password   = 'ivdg inba cphd pmlp'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('chanjiajun321@gmail.com', 'Tasty Trio Recipe');
        $mail->addAddress($email); // Add recipient email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
            <h1>Password Reset Request</h1>
            <p>Click the link below to reset your password:</p>
            <a href='$resetLink'>$resetLink</a>
            <p>If you did not request this, please ignore this email.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle the reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT userID FROM Registered_User WHERE userEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userID);
    if ($stmt->fetch()) {
        $stmt->close();

        // Generate a reset token (no expiry now)
        $resetToken = bin2hex(random_bytes(32)); // Generate a secure random token

        // Store the reset token in the database without expiry
        $stmt = $conn->prepare("UPDATE Registered_User SET reset_token = ? WHERE userEmail = ?");
        $stmt->bind_param("ss", $resetToken, $email);
        $stmt->execute();
        $stmt->close();

        // Send the reset link via email
        $resetLink = "http://localhost/E-Recipe-Website/reset-password.php?token=$resetToken";
        if (sendResetEmail($email, $resetLink)) {
            echo "Password reset link sent to your email.";
        } else {
            echo "Failed to send reset email.";
        }
    } else {
        echo "Email not found.";
    }
}
?>