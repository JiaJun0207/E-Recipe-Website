<?php
include 'db.php';

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

// Handle edit feedback request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_feedbackID'])) {
    $feedbackID = $_POST['edit_feedbackID'];
    $comment = $_POST['edit_comment'];
    $updateQuery = "UPDATE feedback SET comment = ? WHERE feedbackID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $comment, $feedbackID);
    $stmt->execute();
    header("Location: manage_feedback.php");
    exit();
}

// Fetch all feedbacks
$query = "SELECT feedback.feedbackID, feedback.comment, feedback.feedbackDate, 
                 registered_user.userName, recipe.recipeName 
          FROM feedback 
          LEFT JOIN registered_user ON feedback.userID = registered_user.userID
          LEFT JOIN recipe ON feedback.recipeID = recipe.recipeID";
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

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .delete-btn:hover, .edit-btn:hover {
            opacity: 0.8;
        }

        /* Popup Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .modal-content textarea {
            width: 90%;
            height: 80px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        .modal-content button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
        }

        .close-btn {
            background-color: #f44336;
            color: white;
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
