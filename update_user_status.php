<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_userID'])) {
    $userID = $_POST['edit_userID'];
    $userStatus = $_POST['edit_userStatus'];

    $updateQuery = "UPDATE registered_user SET userStatus = ? WHERE userID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $userStatus, $userID);
    $stmt->execute();

    header("Location: manage-user.php");
    exit();
}
?>