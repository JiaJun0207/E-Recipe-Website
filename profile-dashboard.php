<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png';
$userName = 'Guest';
$userEmail = '';
$userBio = '';

// Check if user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Fetch user details
    $query = "SELECT userImg, userName, userEmail, userBio FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $userImg = $userData['userImg'];
                $userName = $userData['userName'];
                $userEmail = $userData['userEmail'];
                $userBio = $userData['userBio'];
            }
        }
        $stmt->close();
    }

    // Fetch favorite recipes
    $favQuery = "SELECT r.recipeID, r.recipeImg, r.recipeName, d.mealDiff, u.userName 
                 FROM favourite f
                 JOIN recipe r ON f.recipeID = r.recipeID
                 JOIN registered_user u ON r.userID = u.userID
                 JOIN meal_difficulty d ON r.diffID = d.diffID
                 WHERE f.userID = ?";
    
    $favStmt = $conn->prepare($favQuery);
    if ($favStmt) {
        $favStmt->bind_param("i", $userID);
        $favStmt->execute();
        $favResult = $favStmt->get_result();
    }

    // Fetch submitted recipes
    $subQuery = "SELECT recipeID, recipeImg, recipeName, recipeStatus, diffID FROM recipe WHERE userID = ?";
    $subStmt = $conn->prepare($subQuery);
    if ($subStmt) {
        $subStmt->bind_param("i", $userID);
        $subStmt->execute();
        $subResult = $subStmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tasty Trio Recipe</title>
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
        .active-link {
            color: #E75480 !important;
            font-weight: bold;
        }
        .profile-container {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
        }
        .sidebar {
            width: 280px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .sidebar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .sidebar h2 {
            color: #E75480;
            font-size: 22px;
            font-weight: 600;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #555;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #D81B60;
        }
        .sidebar a i {
            margin-right: 8px;
        }
        .content {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-left: 20px;
        }
        .content h3 {
            font-weight: 600;
        }
        .recipe-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 250px;
            margin: 10px;
            display: inline-block;
        }
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        .recipe-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 10px;
        }
        .recipe-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="profile-container">
    <div class="sidebar">
        <img src="<?php echo htmlspecialchars($userImg); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($userName); ?></h2>
        <a href="profile-dashboard.php" class="active-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="profile.php"><i class="fas fa-user"></i> Account Details</a>
        <a href="change-password.php"><i class="fas fa-lock"></i> Change Password</a>
        <a href="#"><i class="fas fa-utensils"></i> Recipe Submission</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>
    <div class="content">
        <h3>Dashboard</h3>
        <p>Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($userEmail); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Bio:</label>
            <textarea class="form-control" disabled><?php echo htmlspecialchars($userBio); ?></textarea>
        </div>

        <h3>Favorite Recipes</h3>
        <div class="recipes">
            <?php while ($row = $favResult->fetch_assoc()): ?>
                <div class="recipe-card">
                    <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                    <div class="recipe-content">
                        <h4 class="recipe-title"><?= htmlspecialchars($row['recipeName']) ?></h4>
                        <p class="recipe-meta"><?= htmlspecialchars($row['userName']) ?> - <?= htmlspecialchars($row['mealDiff']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <h3>Submitted Recipes</h3>
        <div class="recipes">
            <?php while ($row = $subResult->fetch_assoc()): ?>
                <div class="recipe-card">
                    <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                    <div class="recipe-content">
                        <h4 class="recipe-title"><?= htmlspecialchars($row['recipeName']) ?></h4>
                        <p class="recipe-meta"><?= htmlspecialchars($row['recipeStatus']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
</body>
</html>