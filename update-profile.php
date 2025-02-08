<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['userID'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $bio = filter_var(trim($_POST['bio']), FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($name)) {
        $_SESSION['error'] = "Name is required!";
        header("Location: profile.php");
        exit();
    }

    // Handle Profile Image Upload
    $targetDir = "uploads/";  // Ensure this folder exists and is writable
    $profileImage = $_FILES["profile_pic"]["name"];
    $targetFile = $targetDir . basename($profileImage);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allow only image file types
    if (!empty($profileImage)) {
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        if ($uploadOk && move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            // Update with new profile image
            $query = "UPDATE registered_user SET userName = ?, userBio = ?, userImg = ? WHERE userID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $name, $bio, $targetFile, $userID);
        } else {
            $_SESSION['error'] = "Error uploading profile picture.";
            header("Location: profile.php");
            exit();
        }
    } else {
        // Update without changing profile image
        $query = "UPDATE registered_user SET userName = ?, userBio = ? WHERE userID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $bio, $userID);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating profile.";
    }

    $stmt->close();
}

// Redirect back to profile page
header("Location: profile.php");
exit();
?>