<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to add favorites."]);
    exit();
}

$userID = $_SESSION['userID'];
$recipeID = $_POST['recipeID'];

// Check if the recipe is already in favorites
$checkQuery = "SELECT * FROM favourite WHERE userID = ? AND recipeID = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $userID, $recipeID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "This recipe is already in your favorites."]);
} else {
    // Insert into favourites table
    $insertQuery = "INSERT INTO favourite (userID, recipeID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userID, $recipeID);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Recipe added to favorites!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add recipe to favorites."]);
    }
}

$stmt->close();
$conn->close();
?>
