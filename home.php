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
            }
        }
    }
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .hero {
            background: url('assets/pic/banner.jpg') no-repeat center center/cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .hero img {
            height: 60px; /* Adjust this value for proper size */
            margin-right: 10px; /* Space between logo and text */
        }

        .hero-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

    </style>
</head>
<body>
<?php include('header.php'); ?>

<header class="hero">
    <div class="hero-content">
        <img src="assets/pic/TastyTrioLogo.png" alt="Tasty Trio Recipe Logo">
        <h1 >Welcome to Tasty Trio Recipe</h1>
    </div>
</header>
<h1>Most Favourite Recipe</h1>
<?php include('about_us.php'); ?>
</body>
</html>
