<?php
include 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}
// Handle delete request
if (isset($_GET['delete'])) {
    $feedbackID = $_GET['delete'];
    $deleteQuery = "DELETE FROM feedback WHERE feedbackID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $feedbackID);
    $stmt->execute();
    header("Location: manage_feedback.php");
    exit();
}

// Fetch all feedbacks
$query = "SELECT feedback.feedbackID, feedback.comment, feedback.feedbackDate, 
                 registered_user.userName, recipe.recipeName, rating.ratingNum, rating.ratingText
          FROM feedback 
          LEFT JOIN registered_user ON feedback.userID = registered_user.userID
          LEFT JOIN recipe ON feedback.recipeID = recipe.recipeID
          LEFT JOIN rating ON feedback.ratingID = rating.ratingID";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .main-content h1 {
            margin-bottom: 20px;
        }

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .action-buttons button {
            padding: 7px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            min-width: 80px;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .delete-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <?php include('admin_Side_Nav.php'); ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Feedback</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Recipe</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['feedbackID'] ?></td>
                            <td><?= $row['userName'] ?: 'Guest' ?></td>
                            <td><?= $row['recipeName'] ?: 'Unknown' ?></td>
                            <td><?= $row['ratingNum'] . ' - ' . $row['ratingText'] ?></td>
                            <td><?= $row['comment'] ?></td>
                            <td><?= $row['feedbackDate'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="manage_feedback.php?delete=<?= $row['feedbackID'] ?>" onclick="return confirm('Are you sure you want to delete this feedback?');">
                                        <button class="delete-btn">Delete</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
