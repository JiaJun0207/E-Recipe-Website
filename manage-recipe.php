<?php
include 'db.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $recipeID = $_GET['delete'];
    $deleteQuery = "DELETE FROM recipe WHERE recipeID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $recipeID);
    $stmt->execute();
    header("Location: manage_recipe.php");
    exit();
}

// Fetch all recipes
$query = "SELECT recipeID, recipeName, recipeImg, recipeIngred, recipeDesc FROM recipe";
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
            min-width: 80px;
        }

        .view-btn {
            background-color: #2196F3;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .view-btn:hover, .delete-btn:hover {
            opacity: 0.8;
        }

        img.recipe-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
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
                        <th>Recipe Ingredient</th>
                        <th>Recipe Description</th>
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
                            <td><?= nl2br($row['recipeIngred']) ?></td>
                            <td><?= $row['recipeDesc'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="view-btn" onclick="viewRecipe('<?= $row['recipeID'] ?>')">View</button>
                                    <button class="delete-btn" onclick="confirmDelete('<?= $row['recipeID'] ?>')">Delete</button>
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

    <script>
        function viewRecipe(id) {
            window.location.href = "view_recipe.php?id=" + id;
        }

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this recipe?")) {
                window.location.href = "manage_recipe.php?delete=" + id;
            }
        }
    </script>

</body>
</html>
