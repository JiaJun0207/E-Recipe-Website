<?php 
include 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}
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

    <?php include('admin_Side_Nav.php'); ?>

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
