<?php
include 'db.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $typeID = $_GET['delete'];
    $deleteQuery = "DELETE FROM meal_type WHERE typeID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $typeID);
    $stmt->execute();
    header("Location: manage_meal_type.php");
    exit();
}

// Fetch all meal types
$query = "SELECT * FROM meal_type";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Meal Types</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 30px;
        }
       
    </style>
</head>
<body>
    <?php include('admin_Side_Nav.php'); ?>
    <div class="main-content">
            <h2>Manage Meal Types</h2>

        <!-- Add Meal Type Form -->
        <form action="add_meal_type.php" method="post">
            <input type="text" name="mealType" placeholder="Enter Meal Type" required>
            <button type="submit">Add Meal Type</button>
        </form>

        <!-- Display Meal Type List -->
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Meal Type</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['typeID'] ?></td>
                <td><?= $row['mealType'] ?></td>
                <td>
                    <a href="edit_meal_type.php?edit=<?= $row['typeID'] ?>">Edit</a> | 
                    <a href="manage_meal_type.php?delete=<?= $row['typeID'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>

</body>
</html>
