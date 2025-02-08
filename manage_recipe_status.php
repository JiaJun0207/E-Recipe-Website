<?php
include 'db.php';

// Handle update status request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_recipeID'])) {
    $recipeID = $_POST['edit_recipeID'];
    $recipeStatus = $_POST['edit_recipeStatus'];
    $updateQuery = "UPDATE recipe SET recipeStatus = ? WHERE recipeID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $recipeStatus, $recipeID);
    $stmt->execute();
    header("Location: manage_recipe_status.php");
    exit();
}

// Fetch all recipes
$query = "SELECT recipe.recipeID, recipe.recipeName, recipe.recipeStatus, recipe.recipeDate, 
                 registered_user.userName, meal_difficulty.mealDiff, meal_type.mealType 
          FROM recipe 
          LEFT JOIN registered_user ON recipe.userID = registered_user.userID
          LEFT JOIN meal_difficulty ON recipe.diffID = meal_difficulty.diffID
          LEFT JOIN meal_type ON recipe.typeID = meal_type.typeID";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipe Status</title>
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

        .view-btn {
            background-color: #2196F3;
            color: white;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .view-btn:hover, .edit-btn:hover {
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

        .modal-content select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        <h1>Manage Recipe Status</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Recipe Name</th>
                        <th>Author</th>
                        <th>Difficulty</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['recipeID'] ?></td>
                            <td><?= $row['recipeName'] ?></td>
                            <td><?= $row['userName'] ?: 'Guest' ?></td>
                            <td><?= $row['mealDiff'] ?: 'Unknown' ?></td>
                            <td><?= $row['mealType'] ?: 'Unknown' ?></td>
                            <td><?= $row['recipeStatus'] ?></td>
                            <td><?= $row['recipeDate'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="openEditModal('<?= $row['recipeID'] ?>', '<?= $row['recipeStatus'] ?>')">Update Status</button>
                                    <a href="view_recipe.php?id=<?= $row['recipeID'] ?>" target="_blank">
                                        <button class="view-btn">View</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Popup Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Recipe Status</h3>
            <form method="post">
                <input type="hidden" id="edit_recipeID" name="edit_recipeID">
                <select id="edit_recipeStatus" name="edit_recipeStatus">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="close-btn" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, status) {
            document.getElementById("edit_recipeID").value = id;
            document.getElementById("edit_recipeStatus").value = status;
            document.getElementById("editModal").style.display = "block";
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>

</body>
</html>
