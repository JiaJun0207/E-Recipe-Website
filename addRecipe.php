<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png'; // Default profile image
$userName = 'Guest';

// Check if user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    // Fetch user data from the database
    $query = "SELECT userImg, userName FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $userID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $userImg = $userData['userImg'];
                $userName = $userData['userName'];
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeName = $_POST['recipeName'];
    $recipeIngred = $_POST['recipeIngred'];
    $recipeDesc = $_POST['recipeDesc'];
    $recipeStatus = $_POST['recipeStatus'];
    $userID = $_POST['userID']; // Change dynamically based on logged-in user
    $diffID = $_POST['diffID'];
    $typeID = $_POST['typeID'];

    // Handling Image Upload
    $target_dir = "uploads/"; // Folder to store images
    $recipeImg = basename($_FILES["recipeImg"]["name"]);
    $target_file = $target_dir . $recipeImg;
    move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $target_file);

    // Insert Data into Database
    $sql = "INSERT INTO recipe (recipeImg, recipeName, recipeIngred, recipeDesc, recipeStatus, userID, diffID, typeID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiis", $recipeImg, $recipeName, $recipeIngred, $recipeDesc, $recipeStatus, $userID, $diffID, $typeID);

    if ($stmt->execute()) {
        echo "Recipe added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>

    </style>
</head>
<body>

    <h2>Add a New Recipe</h2>
    <form action="insert_recipe.php" method="post" enctype="multipart/form-data">
        <label>Recipe Name:</label>
        <input type="text" name="recipeName" required><br>

        <label>Ingredients:</label>
        <textarea name="recipeIngred" required></textarea><br>

        <label>Description:</label>
        <textarea name="recipeDesc" required></textarea><br>

        <label>Recipe Image:</label>
        <input type="file" name="recipeImg" required><br>

        <label>Difficulty Level:</label>
        <input type="number" name="diffID"><br>

        <label>Recipe Type:</label>
        <input type="number" name="typeID"><br>


        <input type="hidden" name="userID" value="1"> <!-- Change dynamically based on user login -->
        
        <button type="submit">Add Recipe</button>
    </form>

</body>
</html>
