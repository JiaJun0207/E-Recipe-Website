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
            } else {
                // Debug: No user found
                echo "No user found with userID: " . $userID . "<br>";
            }
        } else {
            // Debug: Query execution failed
            echo "Query execution failed: " . $stmt->error . "<br>";
        }
    } else {
        // Debug: Failed to prepare statement
        echo "Failed to prepare statement: " . $conn->error . "<br>";
    }
} else {
    // Debug: User is not logged in
    echo "User is not logged in.<br>";
}

// Fetch recipes
$sql = "SELECT r.recipeID, r.recipeImg, r.recipeName, r.recipeStatus, r.recipeDesc, r.recipeIngred, 
               u.userName AS creator, d.mealDiff 
        FROM recipe r
        JOIN registered_user u ON r.userID = u.userID
        JOIN meal_difficulty d ON r.diffID = d.diffID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
        }
        .logo-title {
            display: flex;
            align-items: center;
        }
        .logo-title img {
            height: 50px;
            margin-right: 10px;
        }
        .logo-title h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #E75480;
        }
        header input {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .navbar {
            display: flex;
            gap: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: black;
            font-weight: 500;
            padding: 5px 10px;
            transition: color 0.3s ease, border-bottom 0.3s ease;
        }
        .navbar a:hover {
            color: #D81B60; /* Dark pink color */
            text-decoration: underline;
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
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-img {
            width: 40px; /* Adjust width as needed */
            height: 40px; /* Match width to make it circular */
            border-radius: 50%; /* Makes the image circular */
            object-fit: cover; /* Ensures the image is not stretched or squashed */
            margin-right: 10px;
        }
        .user-name {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<header>
    <div class="logo-title">
        <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
        <h1>Tasty Trio Recipe</h1>
    </div>
    <input type="text" placeholder="What you want to cook today?" style="font-family: 'Poppins', sans-serif;">
    <nav class="navbar">
        <a href="#">Recipes</a>
        <a href="#">Categories</a>
        <a href="#">Favourite</a>
        <a href="#">About Us</a>
    </nav>
    <div class="user-info">
        <img src="<?php echo htmlspecialchars($userImg); ?>" alt="User Image" class="user-img">
        <span class="user-name"><?php echo htmlspecialchars($userName); ?></span>
    </div>
</header>

<section class="recipes">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="recipe-card">
                <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                <div class="recipe-content">
                    <h3 class="recipe-title"><?= htmlspecialchars($row['recipeName']) ?></h3>
                    <p class="recipe-meta"><?= htmlspecialchars($row['creator']) ?> &bullet; <?= htmlspecialchars($row['mealDiff']) ?></p>
                    <i class="favorite-icon">&#9734;</i>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No recipes found.</p>
    <?php endif; ?>
</section>

</body>
</html>
