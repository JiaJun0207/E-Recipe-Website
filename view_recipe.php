<?php
include 'db.php';

// Check if recipe ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Recipe ID.";
    exit();
}

$recipeID = $_GET['id'];

// Fetch recipe details
$query = "SELECT recipe.*, registered_user.userName, meal_difficulty.mealDiff, meal_type.mealType 
          FROM recipe 
          LEFT JOIN registered_user ON recipe.userID = registered_user.userID
          LEFT JOIN meal_difficulty ON recipe.diffID = meal_difficulty.diffID
          LEFT JOIN meal_type ON recipe.typeID = meal_type.typeID
          WHERE recipe.recipeID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

// If no recipe found, show error
if (!$recipe) {
    echo "Recipe not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .recipe-image {
            display: block;
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .details {
            margin-top: 20px;
        }

        .details p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .highlight {
            font-weight: bold;
            color: #222;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f06292;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Recipe Details</h1>

        <!-- Recipe Image -->
        <?php if ($recipe['recipeImg']) { ?>
            <img src="<?= $recipe['recipeImg'] ?>" alt="Recipe Image" class="recipe-image">
        <?php } else { ?>
            <img src="https://via.placeholder.com/500" alt="No Image Available" class="recipe-image">
        <?php } ?>

        <div class="details">
            <p><span class="highlight">Recipe Name:</span> <?= $recipe['recipeName'] ?></p>
            <p><span class="highlight">Author:</span> <?= $recipe['userName'] ?: 'Unknown' ?></p>
            <p><span class="highlight">Difficulty:</span> <?= $recipe['mealDiff'] ?: 'Unknown' ?></p>
            <p><span class="highlight">Meal Type:</span> <?= $recipe['mealType'] ?: 'Unknown' ?></p>
            <p><span class="highlight">Status:</span> <?= $recipe['recipeStatus'] ?></p>
            <p><span class="highlight">Date Posted:</span> <?= $recipe['recipeDate'] ?></p>
            <p><span class="highlight">Ingredients:</span><br> <?= nl2br($recipe['recipeIngred']) ?></p>
            <p><span class="highlight">Description:</span><br> <?= nl2br($recipe['recipeDesc']) ?></p>
        </div>

        <!-- Back Button -->
        <a href="manage_recipe_status.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Recipe Status</a>
    </div>

</body>
</html>
