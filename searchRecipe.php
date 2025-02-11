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
        $stmt->close();
    }
}

// Check if search query is set
$searchQuery = "";
if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);

    // Fetch recipes based on search query (case-insensitive)
    $sql = "SELECT r.recipeID, r.recipeImg, r.recipeName, d.mealDiff, u.userName 
            FROM recipe r
            JOIN registered_user u ON r.userID = u.userID
            JOIN meal_difficulty d ON r.diffID = d.diffID
            WHERE r.recipeStatus = 'approved' 
            AND r.recipeName LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchPattern = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .search-box {
            display: flex;
            align-items: center;
            background: #f0e6fa;
            border-radius: 25px;
            padding: 10px 20px;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .search-box i {
            color: #8a2be2;
            font-size: 18px;
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 18px;
            margin-left: 10px;
            width: 100%;
        }
        .recipes-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .recipe-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 280px;
            text-decoration: none;
            color: inherit;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 15px;
        }
        .recipe-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
        .fav-icon {
            color: #E75480;
            float: right;
        }
        .fav-icon-gray {
            color: #ddd;
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <!-- Search Bar -->
    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search recipes..." onkeydown="if(event.key === 'Enter') searchRecipe()">
        </div>
    </div>

    <!-- Recipe List -->
    <section class="recipes-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>" class="recipe-card">
                    <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                    <div class="recipe-content">
                        <h3 class="recipe-title"> <?= htmlspecialchars($row['recipeName']) ?> </h3>
                        <p class="recipe-meta"><?= htmlspecialchars($row['userName']) ?> • <?= htmlspecialchars($row['mealDiff']) ?></p>
                        <i class="fas fa-star fav-icon <?= $isLoggedIn ? '' : 'fav-icon-gray' ?>"></i>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px; color: #888; width: 100%;">No recipes found.</p>
        <?php endif; ?>
    </section>

    <script>
        function searchRecipe() {
            let query = document.querySelector('.search-box input').value.trim();
            if (query.length > 0) {
                window.location.href = 'searchrecipe.php?query=' + encodeURIComponent(query);
            }
        }
    </script>

</body>
</html>
