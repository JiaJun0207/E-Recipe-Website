<?php 
include 'db.php';

// Fetch total users from 'registered_user' table
$userQuery = "SELECT COUNT(*) AS total_users FROM registered_user";
$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);
$totalUsers = $userData['total_users'];

// Fetch total recipes from 'recipe' table
$recipeQuery = "SELECT COUNT(*) AS total_recipes FROM recipe";
$recipeResult = mysqli_query($conn, $recipeQuery);
$recipeData = mysqli_fetch_assoc($recipeResult);
$totalRecipes = $recipeData['total_recipes'];

// Fetch total feedbacks from 'feedback' table
$feedbackQuery = "SELECT COUNT(*) AS total_feedback FROM feedback";
$feedbackResult = mysqli_query($conn, $feedbackQuery);
$feedbackData = mysqli_fetch_assoc($feedbackResult);
$totalFeedback = $feedbackData['total_feedback'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .navbar .top-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar .user {
            display: flex;
            align-items: center;
        }

        .navbar .user i {
            font-size: 22px;
            margin-right: 5px;
        }

        .navbar .nav-links {
            display: flex;
            justify-content: center;
            padding: 10px 0;
            background-color: #444;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #f8f8f8;
            padding: 20px 0;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #333;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a i {
            font-size: 22px;
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .sidebar .active {
            background-color: #e7e7e7;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 30px;
        }

        .cards {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            flex: 1;
            min-width: 300px;
            max-width: 400px;
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .card i {
            font-size: 50px;
        }

        .card-content {
            text-align: left;
        }

        .card-content h3 {
            margin: 0;
            font-size: 28px;
        }

        .card-content p {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        .card:nth-child(1) {
            background-color: #b19cd9;
            color: white;
        }

        .card:nth-child(2) {
            background-color: #f8cf61;
            color: white;
        }

        .card:nth-child(3) {
            background-color: #79d4e2;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="top-section">
            <div class="logo">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
                <h1>Tasty Trio Recipe</h1>
            </div>
            <div class="user">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="manage-user.php">User</a>
            <a href="manage-recipe.php">Recipe</a>
            <a href="#">Feedback</a>
            <a href="#">Recipe Status</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="manage-user.php"><i class="fas fa-users"></i> Manage User</a>
        <a href="manage-recipe.php"><i class="fas fa-utensils"></i> Manage Recipe</a>
        <a href="#"><i class="fas fa-comments"></i> Manage Feedback</a>
        <a href="#"><i class="fas fa-clipboard-check"></i> Manage Recipe Status</a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>
        <div class="cards">
            <div class="card">
                <i class="fas fa-user"></i>
                <div class="card-content">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsers; ?></p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-book-open"></i>
                <div class="card-content">
                    <h3>Total Recipes</h3>
                    <p><?php echo $totalRecipes; ?></p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-comments"></i>
                <div class="card-content">
                    <h3>Total Feedback</h3>
                    <p><?php echo $totalFeedback; ?></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
