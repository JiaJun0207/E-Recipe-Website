<?php
// Start session
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['userID'];

// Check if recipe ID is provided
if (!isset($_GET['recipeID']) || empty($_GET['recipeID'])) {
    die("Invalid Recipe ID");
}

$recipeID = intval($_GET['recipeID']); 

// Fetch the recipe to ensure it belongs to the user
$query = "SELECT recipeImg FROM recipe WHERE recipeID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $recipeID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found or you do not have permission to delete it.");
}

$recipe = $result->fetch_assoc();
$recipeImg = $recipe['recipeImg'];

$stmt->close();

// Delete recipe from database
$deleteQuery = "DELETE FROM recipe WHERE recipeID = ? AND userID = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("ii", $recipeID, $userID);

if ($deleteStmt->execute()) {
    // Remove image file if it exists
    if (!empty($recipeImg) && file_exists($recipeImg)) {
        unlink($recipeImg);
    }
    echo "<script>alert('Recipe deleted successfully!'); window.location='user_recipe.php';</script>";
} else {
    echo "<script>alert('Error deleting recipe!');</script>";
}

$deleteStmt->close();
$conn->close();
?>
