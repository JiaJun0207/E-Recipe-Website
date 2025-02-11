<?php
// Start session
session_start();
include 'db.php';

// Check if user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Fetch user details
    $query = "SELECT userImg, userName, userEmail, userBio FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $userImg = $userData['userImg'];
                $userName = $userData['userName'];
                $userEmail = $userData['userEmail'];
                $userBio = $userData['userBio'];
            }
        }
        $stmt->close();
    }
}

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
    $recipeStatus = "Re-Submitted"; // Automatically set to "Re-Submitted" when updating

    // Handle Image Upload (if a new image is uploaded)
    if (!empty($_FILES['recipeImg']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["recipeImg"]["name"]);
        move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $targetFile);
    } else {
        $targetFile = $recipe['recipeImg']; // Keep old image
    }

    // Update query
    $updateQuery = "UPDATE recipe SET recipeName = ?, recipeIngred = ?, recipeDesc = ?, recipeStatus = 'Re-Submitted', recipeImg = ? WHERE recipeID = ? AND userID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssi", $recipeName, $recipeIngred, $recipeDesc, $targetFile, $recipeID, $userID); 

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-custom {
            background-color: #ff4500;
            color: white;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            transition: 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #e03e00;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
        }
        .cancel-btn:hover {
            background-color: #5a6268;
        }
        .img-preview {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="container">
    <div class="edit-container">
        <h2>Edit Your Recipe</h2>
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
                <label class="form-label">Recipe Image</label>
                <input type="file" name="recipeImg" class="form-control" id="recipeImg">
                <img id="previewImg" src="<?= htmlspecialchars($recipe['recipeImg']) ?>" class="img-preview">
            </div>
            <button type="submit" class="btn btn-custom">Update Recipe</button>
            <a href="user_recipe.php" class="btn cancel-btn mt-2">Cancel</a>
        </form>
    </div>
</div>

<script>
    document.getElementById("recipeImg").addEventListener("change", function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.getElementById("previewImg");
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

</body>
</html>

