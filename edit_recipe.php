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

// Fetch recipe details
$query = "SELECT * FROM recipe WHERE recipeID = ? AND userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $recipeID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recipe not found or you do not have permission to edit it.");
}

$recipe = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipeName = $_POST['recipeName'];
    $recipeIngred = $_POST['recipeIngred'];
    $recipeDesc = $_POST['recipeDesc'];
    $recipeStatus = $_POST['recipeStatus'];

    // Handle Image Upload (if a new image is uploaded)
    if (!empty($_FILES['recipeImg']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["recipeImg"]["name"]);
        move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $targetFile);
    } else {
        $targetFile = $recipe['recipeImg']; // Keep old image
    }

    // Update query
    $updateQuery = "UPDATE recipe SET recipeName = ?, recipeIngred = ?, recipeDesc = ?, recipeStatus = ?, recipeImg = ? WHERE recipeID = ? AND userID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssssi", $recipeName, $recipeIngred, $recipeDesc, $recipeStatus, $targetFile, $recipeID, $userID);

    if ($updateStmt->execute()) {
        echo "<script>alert('Recipe updated successfully!'); window.location='user_recipe.php';</script>";
    } else {
        echo "<script>alert('Error updating recipe!');</script>";
    }
    $updateStmt->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h2>Edit Recipe</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Recipe Name</label>
            <input type="text" name="recipeName" class="form-control" value="<?= htmlspecialchars($recipe['recipeName']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ingredients</label>
            <textarea name="recipeIngred" class="form-control" rows="5" required><?= htmlspecialchars($recipe['recipeIngred']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="recipeDesc" class="form-control" rows="5" required><?= htmlspecialchars($recipe['recipeDesc']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Recipe Status</label>
            <select name="recipeStatus" class="form-control">
                <option value="Pending" <?= $recipe['recipeStatus'] === "Pending" ? "selected" : "" ?>>Pending</option>
                <option value="Approved" <?= $recipe['recipeStatus'] === "Approved" ? "selected" : "" ?>>Approved</option>
                <option value="Rejected" <?= $recipe['recipeStatus'] === "Rejected" ? "selected" : "" ?>>Rejected</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Recipe Image</label>
            <input type="file" name="recipeImg" class="form-control">
            <img src="<?= htmlspecialchars($recipe['recipeImg']) ?>" width="100" class="mt-2">
        </div>
        <button type="submit" class="btn btn-success">Update Recipe</button>
        <a href="user_recipe.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
