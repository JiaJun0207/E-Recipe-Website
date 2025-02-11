<?php
include 'db.php';

// Check if recipe ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Recipe ID.";
    exit();
}

$recipeID = $_GET['id'];

// Fetch recipe details
$recipeQuery = "SELECT recipeName FROM recipe WHERE recipeID = ?";
$stmt = $conn->prepare($recipeQuery);
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

// If no recipe found, show error
if (!$recipe) {
    echo "Recipe not found.";
    exit();
}

// Fetch all reviews for the recipe
$reviewQuery = "SELECT feedback.comment, feedback.feedbackDate, rating.ratingNum, 
                registered_user.userName 
                FROM feedback 
                LEFT JOIN rating ON feedback.ratingID = rating.ratingID
                LEFT JOIN registered_user ON feedback.userID = registered_user.userID
                WHERE feedback.recipeID = ?";
$stmt = $conn->prepare($reviewQuery);
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reviews - <?= htmlspecialchars($recipe['recipeName']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .review-list {
            text-align: left;
            margin-top: 20px;
        }

        .review-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-item p {
            margin: 5px 0;
        }

        .review-item .rating {
            color: #FFD700;
            font-size: 18px;
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
        <h1>Reviews for "<?= htmlspecialchars($recipe['recipeName']) ?>"</h1>

        <div class="review-list">
            <?php if ($reviews->num_rows > 0) {
                while ($review = $reviews->fetch_assoc()) { ?>
                    <div class="review-item">
                        <p><span class="highlight">Reviewer:</span> <?= htmlspecialchars($review['userName'] ?: 'Anonymous') ?></p>
                        <p><span class="highlight">Rating:</span> 
                            <span class="rating"><?= str_repeat("★", $review['ratingNum']) ?><?= str_repeat("☆", 5 - $review['ratingNum']) ?></span>
                        </p>
                        <p><span class="highlight">Comment:</span> <?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        <p><span class="highlight">Date:</span> <?= $review['feedbackDate'] ?></p>
                    </div>
                <?php }
            } else { ?>
                <p>No reviews available for this recipe.</p>
            <?php } ?>
        </div>

        <!-- Back Button -->
        <a href="manage-recipe.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Manage Recipes</a>
    </div>

</body>
</html>
