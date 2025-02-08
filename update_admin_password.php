<?php
include 'db.php'; // Ensure database connection is correct

$adminName = "admin"; // Make sure this matches the database entry exactly
$plainPassword = "TCC"; // The new admin password
$hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

$query = "UPDATE admin SET adminPass = ? WHERE adminName = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashedPassword, $adminName);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "✅ Password updated successfully! You can now log in with the new password.";
} else {
    echo "❌ Error: No admin account found with this name.";
}

$stmt->close();
$conn->close();
?>