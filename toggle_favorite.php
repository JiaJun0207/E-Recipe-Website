<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "Please log in to save favorites."]);
    exit();
}

$userID = $_SESSION['userID'];
$recipeID = $_POST['recipeID'];

// Check if the recipe is already favorited
$query = "SELECT * FROM favorite WHERE userID = ? AND recipeID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userID, $recipeID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove from favorites
    $query = "DELETE FROM favorite WHERE userID = ? AND recipeID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userID, $recipeID);
    $stmt->execute();
    echo json_encode(["status" => "removed", "message" => "Removed from Favorites!", "toastClass" => "toast-error"]);
} else {
    // Add to favorites
    $query = "INSERT INTO favorite (userID, recipeID) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userID, $recipeID);
    $stmt->execute();
    echo json_encode(["status" => "added", "message" => "Added to Favorites!", "toastClass" => "toast-success"]);
}
?>
