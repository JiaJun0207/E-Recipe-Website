<?php
require 'db.php'; // Include database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the token and passwords from the form submission
    $token = $_POST['token'];
    $password = $_POST['new_password'];
    $password_confirmation = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $password_confirmation) {
        echo 'Passwords do not match';
        exit;
    }

    // Validate the reset token and expiry in the database
    $query = "SELECT userID FROM Registered_User WHERE BINARY reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token); // Bind the token to the query
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token is valid, proceed with updating the password
        $stmt->bind_result($userID);
        $stmt->fetch();
        $stmt->close();

        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Update the password in the database and clear the reset token
        $update_query = "UPDATE Registered_User SET userPass = ?, reset_token = NULL WHERE userID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $hashed_password, $userID);

        if ($update_stmt->execute()) {
            echo 'Password has been reset successfully.';
        } else {
            echo 'Error updating password.';
        }
    } else {
        echo 'Invalid or expired token.';
    }
}
?>

