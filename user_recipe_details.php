<?php
session_start();
include 'db.php';

// Default values
$userImg = 'uploads/default.png'; 
$userName = 'Guest';
$isLoggedIn = false;
$userID = null;
$toastMessage = '';
if (isset($_SESSION['toastMessage'])) {
    $toastMessage = $_SESSION['toastMessage'];
    unset($_SESSION['toastMessage']); // Clear the message after displaying
}

if (isset($_SESSION['userID'])) {
    $isLoggedIn = true;
    $userID = $_SESSION['userID'];

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

// Validate Recipe ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $toastMessage = 'Invalid Recipe ID.';
} else {
    $recipeID = $_GET['id'];

    // Handle comment submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        if (!$isLoggedIn) {
            $toastMessage = 'You must be logged in to leave a comment.';
        } else {
            $comment = trim($_POST['comment']);
            $rating = $_POST['rating'];
    
            if (!empty($comment)) {
                $stmt = $conn->prepare("INSERT INTO feedback (userID, recipeID, ratingID, comment, feedbackDate) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("iiis", $userID, $recipeID, $rating, $comment);
    
                if ($stmt->execute()) {
                    $_SESSION['toastMessage'] = 'Your feedback has been submitted!';
                    header("Location: user_recipe_details.php?id=" . urlencode($recipeID));
                    exit();
                }
            }
        }
    }

    // Fetch recipe details
    $query = "SELECT recipe.*, registered_user.userName, registered_user.userImg AS creatorImg, 
                     meal_difficulty.mealDiff, meal_type.mealType 
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
        $toastMessage = 'Recipe not found.';
    }
    // Check if the recipe is already in the user's favorites
    $isFavorited = false;
    if ($isLoggedIn) {
        $query = "SELECT * FROM favorite WHERE userID = ? AND recipeID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userID, $recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        $isFavorited = $result->num_rows > 0;
    }
}

// Fetch ratings
$ratingQuery = "SELECT ratingID, ratingText FROM rating";
$ratingResult = $conn->query($ratingQuery);

// Fetch comments
$commentsQuery = "SELECT feedback.*, registered_user.userName, registered_user.userImg, rating.ratingText 
                  FROM feedback 
                  JOIN registered_user ON feedback.userID = registered_user.userID
                  JOIN rating ON feedback.ratingID = rating.ratingID
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
    <title><?= htmlspecialchars($recipe['recipeName']) ?> - Recipe Details</title>
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
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .recipe-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        .comment-card {
            display: flex;
            align-items: flex-start;
            padding: 10px;
            border-radius: 10px;
            background: #f8f8f8;
            margin-top: 10px;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
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
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast {
            display: none;
            min-width: 250px;
            padding: 15px;
            background: #28a745;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        .toast.error {
            background: #dc3545;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container">
    <h2><?= htmlspecialchars($recipe['recipeName']) ?></h2>
    <img src="<?= htmlspecialchars($recipe['recipeImg']) ?>" class="recipe-img" alt="Recipe Image">
    
    <p><strong>Author:</strong> <?= htmlspecialchars($recipe['userName']) ?></p>
    <p><strong>Difficulty:</strong> <?= htmlspecialchars($recipe['mealDiff']) ?></p>
    <p><strong>Meal Type:</strong> <?= htmlspecialchars($recipe['mealType']) ?></p>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($recipe['recipeDesc'])) ?></p>
    <!-- Favorite Button -->
    <form id="favoriteForm">
        <input type="hidden" name="recipeID" value="<?= $recipeID ?>">
            <button type="button" id="favoriteBtn" class="btn btn-outline-danger">
                <i class="fa <?= $isFavorited ? 'fa-heart' : 'fa-heart-o' ?>"></i> 
                <span id="favoriteText"><?= $isFavorited ? "Remove from Favorites" : "Add to Favorites" ?></span>
            </button>
    </form>
    <h3 class="mt-4">Leave a Review</h3>
    <?php if ($isLoggedIn): ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating:</label>
                <select name="rating" class="form-select" required>
                    <?php while ($rating = $ratingResult->fetch_assoc()): ?>
                        <option value="<?= $rating['ratingID'] ?>"><?= $rating['ratingText'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea name="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-custom">Submit</button>
        </form>
    <?php else: ?>
        <p><a href="index.php">Log in</a> to leave a comment.</p>
    <?php endif; ?>

    <h3 class="mt-4">User Feedback</h3>
    <?php while ($comment = $commentsResult->fetch_assoc()): ?>
        <div class="comment-card">
            <img src="<?= htmlspecialchars($comment['userImg'] ?? 'uploads/default.png') ?>" class="profile-img">
            <div>
            <h6><strong><?= htmlspecialchars($comment['userName']) ?></strong> - 
                <span class="text-warning">
                    <?php for ($i = 0; $i < $comment['ratingID']; $i++): ?>
                        <i class="fa fa-star"></i>
                    <?php endfor; ?>
                    <?php for ($i = $comment['ratingID']; $i < 5; $i++): ?>
                        <i class="fa fa-star text-muted"></i>
                    <?php endfor; ?>
                </span>
            </h6>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
            <small class="text-muted">
                Posted on <?= date("F j, Y, g:i a", strtotime($comment['feedbackDate'])) ?>
            </small>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<div class="toast-container">
    <div class="toast" id="toastMessage"><?= $toastMessage ?></div>
</div>

<script>
    $(document).ready(function() {
        let toast = $("#toastMessage");
        if (toast.text().trim() !== "") {
            toast.fadeIn().delay(3000).fadeOut();
        }
    });
    $(document).ready(function () {
    $("#favoriteBtn").click(function () {
        $.ajax({
            url: "toggle_favorite.php",
            type: "POST",
            data: { recipeID: <?= $recipeID ?> },
            dataType: "json",
            success: function (response) {
                if (response.status === "added") {
                    $("#favoriteBtn i").removeClass("fa-heart-o").addClass("fa-heart");
                    $("#favoriteText").text("Remove from Favorites");
                } else if (response.status === "removed") {
                    $("#favoriteBtn i").removeClass("fa-heart").addClass("fa-heart-o");
                    $("#favoriteText").text("Add to Favorites");
                }

                // Update toast message and show toast
                $("#toastMessageContent").text(response.message);
                $("#favoriteToast").removeClass("bg-success bg-danger").addClass(response.toastClass);
                var toast = new bootstrap.Toast(document.getElementById("favoriteToast"));
                toast.show();
            }
        });
    });
});
</script>
</body>
</html>
