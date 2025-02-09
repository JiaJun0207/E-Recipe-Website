<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png'; // Default profile image
$userName = 'Guest';

// Check if user is logged in
$isLoggedIn = false;
if (isset($_SESSION['userID'])) {
    $isLoggedIn = true;
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

// Fetch recipes (APRROVED ONLY)
$sql = "SELECT r.recipeID, r.recipeImg, r.recipeName, r.recipeStatus, r.recipeDesc, r.recipeIngred, 
               u.userName AS creator, d.mealDiff 
        FROM recipe r
        JOIN registered_user u ON r.userID = u.userID
        JOIN meal_difficulty d ON r.diffID = d.diffID
        WHERE r.recipeStatus = 'approved'";
$result = $conn->query($sql);
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
        .add-recipe-btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #ff4500;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .add-recipe-btn:hover {
            background-color: #e03e00;
            transform: translateY(-1px);
        }

        .recipes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 40px;
        }
        .recipe-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 20px;
        }
        .recipe-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
        .favorite-icon {
            float: right;
            font-size: 18px;
            color: #ffd700;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<!-- Add Recipe Button -->
<?php if ($isLoggedIn): ?>
    <a href="addRecipe.php" class="add-recipe-btn">+ Add Recipe</a>
<?php endif; ?>

<section class="recipes">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="recipe-card">
                <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>" style="text-decoration: none; color: inherit;">
                    <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                    <div class="recipe-content">
                        <h3 class="recipe-title"> <?= htmlspecialchars($row['recipeName']) ?> </h3>
                        <p class="recipe-meta"> <?= htmlspecialchars($row['creator']) ?> &bullet; <?= htmlspecialchars($row['mealDiff']) ?> </p>
                    </div>
                </a>
                <i class="favorite-icon">&#9734;</i>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No recipes found.</p>
    <?php endif; ?>
</section>


</body>
</html>
