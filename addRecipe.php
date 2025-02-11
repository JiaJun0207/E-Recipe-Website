<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['userID']; // Get logged-in user ID

// Fetch user details for header display
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
    $stmt->close();
}

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
    $recipeStatus = "Pending"; // Default status
    $uploadOk = 1;

    // Ensure 'uploads' directory exists
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Handle Image Upload
    $recipeImg = $_FILES["recipeImg"]["name"];
    $imageFileType = strtolower(pathinfo($recipeImg, PATHINFO_EXTENSION));
    $newFileName = uniqid() . "_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $newFileName;

    // Validate file type
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check file size (max 2MB)
    if ($_FILES["recipeImg"]["size"] > 2 * 1024 * 1024) {
        echo "<script>alert('Sorry, your file is too large. Max size: 2MB.');</script>";
        $uploadOk = 0;
    }

    // Move uploaded file if valid
    if ($uploadOk && move_uploaded_file($_FILES["recipeImg"]["tmp_name"], $target_file)) {
        // Insert Data into Database
        $sql = "INSERT INTO recipe (recipeImg, recipeName, recipeIngred, recipeDesc, recipeStatus, userID, diffID, typeID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssiis", $target_file, $recipeName, $recipeIngred, $recipeDesc, $recipeStatus, $userID, $diffID, $typeID);

        if ($stmt->execute()) {
            echo "<script>alert('Recipe added successfully!'); window.location.href='eRecipeList.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #E75480;
            font-weight: 600;
        }
        label {
            font-weight: 500;
            margin-top: 10px;
        }
        .form-control {
            border-radius: 8px;
        }
        .form-select {
            border-radius: 8px;
        }
        .btn-custom {
            background-color: #E75480;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            padding: 10px;
            margin-top: 15px;
        }
        .btn-custom:hover {
            background-color: #d64068;
        }
        .back-btn {
            text-decoration: none;
            color: #E75480;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }
        .back-btn i {
            margin-right: 5px;
        }
        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
    <div class="container">
        <h2>Add a New Recipe</h2>
        <form action="addRecipe.php" method="post" enctype="multipart/form-data">
            <label>Recipe Name:</label>
            <input type="text" name="recipeName" class="form-control" required>

            <label>Ingredients:</label>
            <textarea name="recipeIngred" class="form-control" required></textarea>

            <label>Description:</label>
            <textarea name="recipeDesc" class="form-control" required></textarea>

            <label>Recipe Image:</label>
            <input type="file" name="recipeImg" class="form-control" accept="image/*" required onchange="previewImage(event)">
            <img id="preview" class="image-preview" src="" alt="Image Preview" style="display: none;">

            <label>Difficulty Level:</label>
            <select name="diffID" class="form-select" required>
                <option value="">Select Difficulty</option>
                <?php while ($diff = mysqli_fetch_assoc($diffResult)) { ?>
                    <option value="<?= $diff['diffID'] ?>"><?= $diff['mealDiff'] ?></option>
                <?php } ?>
            </select>

            <label>Recipe Type:</label>
            <select name="typeID" class="form-select" required>
                <option value="">Select Type</option>
                <?php while ($type = mysqli_fetch_assoc($typeResult)) { ?>
                    <option value="<?= $type['typeID'] ?>"><?= $type['mealType'] ?></option>
                <?php } ?>
            </select>

            <input type="hidden" name="userID" value="<?= $userID ?>"> 
            
            <button type="submit" class="btn btn-custom">Add Recipe</button>
        </form>
        
        <a href="eRecipeList.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to All Recipes</a>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = "block";
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>
</html>
