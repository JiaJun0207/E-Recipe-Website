<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "Please log in to add a recipe.";
    exit();
}

$userID = $_SESSION['userID']; // Get logged-in user ID

// Fetch difficulty levels
$diffQuery = "SELECT * FROM meal_difficulty";
$diffResult = mysqli_query($conn, $diffQuery);

// Fetch meal types
$typeQuery = "SELECT * FROM meal_type";
$typeResult = mysqli_query($conn, $typeQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeName = $_POST['recipeName'];
    $recipeIngred = $_POST['recipeIngred'];
    $recipeDesc = $_POST['recipeDesc'];
    $diffID = $_POST['diffID'];
    $typeID = $_POST['typeID'];
    $uploadOk = 1;

    // Ensure 'uploads' directory exists
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create folder if it doesn't exist
    }

    // Handle Image Upload
    $recipeImg = $_FILES["recipeImg"]["name"];
    $imageFileType = strtolower(pathinfo($recipeImg, PATHINFO_EXTENSION));
    $newFileName = uniqid() . "_" . time() . "." . $imageFileType; // Generate unique filename
    $target_file = $target_dir . $newFileName;

    // Validate file type
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (max 2MB)
    if ($_FILES["recipeImg"]["size"] > 2 * 1024 * 1024) {
        echo "Sorry, your file is too large. Max size: 2MB.";
        $uploadOk = 0;
    }

    // Move uploaded file if valid
    if ($uploadOk && move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $target_file)) {
        // Insert Data into Database
        $sql = "INSERT INTO recipe (recipeImg, recipeName, recipeIngred, recipeDesc, userID, diffID, typeID)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiis", $target_file, $recipeName, $recipeIngred, $recipeDesc, $userID, $diffID, $typeID);

        if ($stmt->execute()) {
            echo "Recipe added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <h2>Add a New Recipe</h2>
    <form action="addRecipe.php" method="post" enctype="multipart/form-data">
        <label>Recipe Name:</label>
        <input type="text" name="recipeName" required><br>

        <label>Ingredients:</label>
        <textarea name="recipeIngred" required></textarea><br>

        <label>Description:</label>
        <textarea name="recipeDesc" required></textarea><br>

        <label>Recipe Image:</label>
        <input type="file" name="recipeImg" accept="image/*" required><br>

        <label>Difficulty Level:</label>
        <select name="diffID" required>
            <option value="">Select Difficulty</option>
            <?php while ($diff = mysqli_fetch_assoc($diffResult)) { ?>
                <option value="<?= $diff['diffID'] ?>"><?= $diff['mealDiff'] ?></option>
            <?php } ?>
        </select><br>

        <label>Recipe Type:</label>
        <select name="typeID" required>
            <option value="">Select Type</option>
            <?php while ($type = mysqli_fetch_assoc($typeResult)) { ?>
                <option value="<?= $type['typeID'] ?>"><?= $type['mealType'] ?></option>
            <?php } ?>
        </select><br>

        <input type="hidden" name="userID" value="<?= $userID ?>"> 
        
        <button type="submit">Add Recipe</button>
    </form>

</body>
</html>
