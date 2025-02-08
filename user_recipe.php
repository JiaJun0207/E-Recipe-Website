<?php
// Start session
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
// if (!isset($_SESSION['userID'])) {
//     header("Location: login.php");
//     exit();
// }
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); 
    exit();
} else {
    $userNotLoggedIn = false; // Allow page to load normally

    // Initialize variables for user data
    $userImg = 'uploads/default.png';
    $userName = 'Guest';
    $userEmail = '';
    $userBio = '';

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
}

$userID = $_SESSION['userID']; // Get logged-in user ID

// Fetch user recipes
$query = "SELECT recipeID, recipeImg, recipeName, recipeStatus, recipeDate 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .table th {
            background-color: #f8f9fa;
        }
        .table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 5px;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container mt-4">
    <h2 class="mb-3">My Recipes</h2>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Status</th>
                <th>Submitted Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="<?= !empty($row['recipeImg']) ? htmlspecialchars($row['recipeImg']) : 'uploads/default_recipe.png' ?>" 
                             alt="<?= htmlspecialchars($row['recipeName']) ?>">
                    </td>
                    <td><?= htmlspecialchars($row['recipeName']) ?></td>
                    <td><?= htmlspecialchars($row['recipeStatus']) ?></td>
                    <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($row['recipeDate']))) ?></td>
                    <td class="action-buttons">
                        <a href="edit_recipe.php?recipeID=<?= $row['recipeID'] ?>" class="btn edit-btn btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_recipe.php?recipeID=<?= $row['recipeID'] ?>" class="btn delete-btn btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this recipe?');">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
