<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png'; // Default profile image
$userName = 'Guest';
$isLoggedIn = false;

if (isset($_SESSION['userID'])) {
    $isLoggedIn = true;
    $userID = $_SESSION['userID'];

    // Fetch user data
    $query = "SELECT userImg, userName FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);
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

if (!$recipe) {
    echo "Recipe not found.";
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!$isLoggedIn) {
        echo "You must be logged in to leave a comment.";
    } else {
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];
        
        $stmt = $conn->prepare("INSERT INTO feedback (userID, recipeID, ratingID, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $userID, $recipeID, $rating, $comment);
        $stmt->execute();
    }
}

// Fetch existing comments
$commentsQuery = "SELECT feedback.*, registered_user.userName FROM feedback 
                  JOIN registered_user ON feedback.userID = registered_user.userID
                  WHERE feedback.recipeID = ? ORDER BY feedbackDate DESC";
$commentsStmt = $conn->prepare($commentsQuery);
$commentsStmt->bind_param("i", $recipeID);
$commentsStmt->execute();
$commentsResult = $commentsStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

<?php include('header.php'); ?>

<div class="container">
    <h1><?= $recipe['recipeName'] ?></h1>
    <img src="<?= $recipe['recipeImg'] ?: 'https://via.placeholder.com/500' ?>" alt="Recipe Image" class="img-fluid">
    <p><strong>Author:</strong> <?= $recipe['userName'] ?></p>
    <p><strong>Difficulty:</strong> <?= $recipe['mealDiff'] ?></p>
    <p><strong>Meal Type:</strong> <?= $recipe['mealType'] ?></p>
    <p><strong>Description:</strong> <?= nl2br($recipe['recipeDesc']) ?></p>
    
    <a href="eRecipeList.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to All Recipe</a>

    <!-- Add to Favorites -->
    <?php if ($isLoggedIn): ?>
        <button id="addToFavoriteBtn" class="btn btn-warning">Add to Favorites</button>

        <!-- Success/Error Modal -->
        <div class="modal fade" id="favoriteModal" tabindex="-1" aria-labelledby="favoriteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="favoriteModalLabel">Favorite Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="favoriteModalMessage">
                        <!-- Message will be inserted here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Comment & Rating Form -->
    <?php if ($isLoggedIn): ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating:</label>
                <select name="rating" class="form-select" required>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea name="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to leave a comment.</p>
    <?php endif; ?>

    <!-- Display Comments -->
    <h3 class="mt-4">User Feedback</h3>
    <?php while ($comment = $commentsResult->fetch_assoc()): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"> <?= $comment['userName'] ?> </h5>
                <p class="card-text"> <?= nl2br($comment['comment']) ?> </p>
                <p><small>Posted on: <?= $comment['feedbackDate'] ?></small></p>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#addToFavoriteBtn").click(function () {
            $.ajax({
                url: "add_favorite.php",
                type: "POST",
                data: { recipeID: <?= $recipeID ?> },
                dataType: "json",
                success: function (response) {
                    $("#favoriteModalMessage").text(response.message);
                    $("#favoriteModal").modal("show");
                },
                error: function () {
                    $("#favoriteModalMessage").text("An error occurred.");
                    $("#favoriteModal").modal("show");
                }
            });
        });
    });
</script>


</body>
</html>
