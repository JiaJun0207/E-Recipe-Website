<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$userID = $_SESSION['userID'];
$recipeID = $_POST['recipeID'];

// Check if the recipe is already in favorites
$query = "SELECT * FROM favourite WHERE userID = ? AND recipeID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userID, $recipeID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Recipe is in favorites, remove it
    $deleteQuery = "DELETE FROM favourite WHERE userID = ? AND recipeID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $userID, $recipeID);
    if ($deleteStmt->execute()) {
        echo json_encode(["status" => "removed"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to remove favorite."]);
    }
} else {
    // Recipe is not in favorites, add it
    $insertQuery = "INSERT INTO favourite (userID, recipeID) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ii", $userID, $recipeID);
    if ($insertStmt->execute()) {
        echo json_encode(["status" => "added"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add favorite."]);
    }
}
?>
