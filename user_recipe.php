<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php"); 
    exit();
}

$userID = $_SESSION['userID'];

// Initialize variables for user data
$userImg = 'uploads/default.png';
$userName = 'Guest';
$userEmail = '';
$userBio = '';
$userStatus = '';

// Fetch user details including userStatus
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

// Fetch user recipes
$query = "SELECT recipeID, recipeImg, recipeName, recipeStatus, recipeDate, remark 
          FROM recipe 
          WHERE userID = ? 
          ORDER BY recipeDate DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .recipe-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .recipe-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .recipe-details {
            flex-grow: 1;
        }

        .recipe-details h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .recipe-details p {
            margin: 3px 0;
            font-size: 14px;
            color: #666;
        }

        .badge-status {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #fff;
        }

        .badge-approved {
            background-color: #28a745;
            color: #fff;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
        }

        .edit-btn {
            background-color: #17a2b8;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .edit-btn:hover {
            background-color: #138496;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">My Recipes</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="recipe-card">
            <img src="<?= !empty($row['recipeImg']) ? htmlspecialchars($row['recipeImg']) : 'uploads/default_recipe.png' ?>" 
                 alt="<?= htmlspecialchars($row['recipeName']) ?>">

            <div class="recipe-details">
                <h5><?= htmlspecialchars($row['recipeName']) ?></h5>
                <p>
                    <strong>Status:</strong> 
                    <span class="badge-status 
                        <?= $row['recipeStatus'] == 'Pending' ? 'badge-pending' : ($row['recipeStatus'] == 'Approved' ? 'badge-approved' : 'badge-rejected') ?>">
                        <?= htmlspecialchars($row['recipeStatus']) ?>
                    </span>
                </p>
                <p><strong>Remark:</strong> <?= !empty($row['remark']) ? htmlspecialchars($row['remark']) : "No remarks yet" ?></p>
                <p><strong>Submitted:</strong> <?= htmlspecialchars(date("d M Y, H:i", strtotime($row['recipeDate']))) ?></p>
            </div>

            <div class="action-buttons">
                <?php if ($userStatus !== 'Banned'): ?>
                    <a href="edit_recipe.php?recipeID=<?= $row['recipeID'] ?>" class="btn edit-btn btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                <?php endif; ?>
                <a href="delete_recipe.php?recipeID=<?= $row['recipeID'] ?>" class="btn delete-btn btn-sm" 
                   onclick="return confirm('Are you sure you want to delete this recipe?');">
                    <i class="fas fa-trash-alt"></i> Delete
                </a>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if ($result->num_rows === 0): ?>
        <p class="text-center">No recipes found.</p>
    <?php endif; ?>
</div>


</body>
</html>





