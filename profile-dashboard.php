<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    $userImg = 'uploads/default.png';
    $userName = 'Guest';
    $userEmail = '';
    $userBio = '';
    $userStatus = '';
    $userNotLoggedIn = true; // Set flag to show pop-up
} else {
    $userID = $_SESSION['userID'];
    $userNotLoggedIn = false; // Allow page to load normally

    // Fetch user details
    $query = "SELECT userImg, userName, userEmail, userBio, userStatus FROM registered_user WHERE userID = ?";
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
                $userStatus = $userData['userStatus'];
            }
        }
        $stmt->close();
    }

    // Fetch favorite recipes with creator info
    $favQuery = "SELECT r.recipeID, r.recipeImg, r.recipeName, r.recipeDate, u.userName AS creatorName 
                FROM favorite f
                JOIN recipe r ON f.recipeID = r.recipeID
                JOIN registered_user u ON r.userID = u.userID
                WHERE f.userID = ? 
                ORDER BY r.recipeDate DESC";

    $favStmt = $conn->prepare($favQuery);
    if ($favStmt) {
        $favStmt->bind_param("i", $userID);
        $favStmt->execute();
        $favResult = $favStmt->get_result();
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
        .recipe-title a {
            text-decoration: none;
            color: #D81B60;
            transition: 0.3s;
        }
        .recipe-title a:hover {
            text-decoration: underline;
        }
        .recipe-meta {
            font-size: 14px;
            color: gray;
        }
        .status-container {
            display: inline-block;
            padding: 5px 15px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            color: white;
            min-width: 100px;
        }
        .status-active {
            background-color: #28a745; /* Green */
        }
        .status-banned {
            background-color: #dc3545; /* Red */
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<?php if ($userNotLoggedIn): ?>
    <!-- Show Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login Required</h5>
                </div>
                <div class="modal-body">
                    <p>You need to log in to access the **Profile Page**.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='home.php'">Back to Home</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Login Now</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#loginModal").modal('show');
        });
    </script>

<?php else: ?>

<div class="profile-container">
    <div class="sidebar">
        <img src="<?php echo htmlspecialchars($userImg); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($userName); ?></h2>
        <a href="profile-dashboard.php" class="active-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="profile.php"><i class="fas fa-user"></i> Account Details</a>
        <a href="change-password.php"><i class="fas fa-lock"></i> Change Password</a>
        <a href="user_recipe.php"><i class="fas fa-utensils"></i> My Recipes</a>
        <a href="addRecipe.php"><i class="fas fa-plus-circle"></i> Submit Recipe</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>
    <div class="content">
        <h3>Dashboard</h3>
        <p>Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
        <div class="mb-3">
            <label class="form-label">Bio:</label>
            <p class="form-control" disabled><?php echo htmlspecialchars($userBio); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Status:</label>
            <div class="status-container <?php echo ($userStatus === 'Active') ? 'status-active' : 'status-banned'; ?>">
                <?php echo htmlspecialchars($userStatus); ?>
            </div>
        </div>

        
        <h3>My Favorite Recipes</h3>
        <div class="recipes">
            <?php if ($favResult->num_rows > 0): ?>
                <?php while ($row = $favResult->fetch_assoc()): ?>
                    <div class="recipe-card">
                        <img src="<?= htmlspecialchars($row['recipeImg']) ?>" alt="<?= htmlspecialchars($row['recipeName']) ?>">
                        <div class="recipe-content">
                            <h4 class="recipe-title">
                                <a href="user_recipe_details.php?id=<?= $row['recipeID'] ?>">
                                    <?= htmlspecialchars($row['recipeName']) ?>
                                </a>
                            </h4>
                            <p class="recipe-meta">Created by: <?= htmlspecialchars($row['creatorName']) ?></p>
                            <p class="recipe-meta">Added on: <?= htmlspecialchars(date("d M Y, H:i", strtotime($row['recipeDate']))) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No favorite recipes added yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>