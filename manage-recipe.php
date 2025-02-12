<?php
include 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}
// Handle delete request
if (isset($_GET['delete'])) {
    $recipeID = $_GET['delete'];
    $deleteQuery = "DELETE FROM recipe WHERE recipeID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $recipeID);
    $stmt->execute();
    header("Location: manage-recipe.php");
    exit();
}

// Handle remark update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_recipeID'])) {
    $recipeID = $_POST['edit_recipeID'];
    $remark = $_POST['edit_remark'];

    $updateQuery = "UPDATE recipe SET remark = ? WHERE recipeID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $remark, $recipeID);
    $stmt->execute();
    header("Location: manage-recipe.php");
    exit();
}

// Fetch all recipes
$query = "SELECT recipeID, recipeName, recipeImg, recipeStatus, remark FROM recipe";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipe</title>
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
            min-width: 100px;
        }

        .view-btn {
            background-color: #2196F3;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .remark-btn {
            background-color: #FFA500;
            color: white;
        }

        .review-btn {
            background-color: #4CAF50;
            color: white;
        }

        .view-btn:hover, .delete-btn:hover, .remark-btn:hover, .review-btn:hover {
            opacity: 0.8;
        }

        img.recipe-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
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
        <h1>Manage Recipe</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Recipe Name</th>
                        <th>Recipe Image</th>
                        <th>Recipe Status</th>
                        <th>Remark</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['recipeID'] ?></td>
                            <td><?= $row['recipeName'] ?></td>
                            <td>
                                <img src="<?= $row['recipeImg'] ?>" alt="Recipe Image" class="recipe-image">
                            </td>
                            <td><?= $row['recipeStatus'] ?></td>
                            <td><?= $row['remark'] ?: "No remarks yet" ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="view-btn" onclick="viewRecipe('<?= $row['recipeID'] ?>')">View Recipe</button>
                                    <button class="remark-btn" onclick="openRemarkModal('<?= $row['recipeID'] ?>', '<?= $row['remark'] ?>')">Remark</button>
                                    <button class="review-btn" onclick="viewReview('<?= $row['recipeID'] ?>')">View Reviews</button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($result->num_rows === 0) { ?>
                        <tr><td colspan="6">No recipes found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Remark Popup Modal -->
    <div id="remarkModal" class="modal">
        <div class="modal-content">
            <h3>Edit Remark</h3>
            <form method="post">
                <input type="hidden" id="edit_recipeID" name="edit_recipeID">
                <textarea id="edit_remark" name="edit_remark" required></textarea>
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="close-btn" onclick="closeRemarkModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function viewRecipe(id) {
            window.location.href = "view_recipe.php?id=" + id;
        }

        function viewReview(id) {
            window.location.href = "view_review.php?id=" + id;
        }

        function openRemarkModal(id, remark) {
            document.getElementById("edit_recipeID").value = id;
            document.getElementById("edit_remark").value = remark;
            document.getElementById("remarkModal").style.display = "block";
        }

        function closeRemarkModal() {
            document.getElementById("remarkModal").style.display = "none";
        }
    </script>

</body>
</html>
