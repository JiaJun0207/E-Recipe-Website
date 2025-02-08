<?php
include 'db.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $diffID = $_GET['delete'];
    $deleteQuery = "DELETE FROM meal_difficulty WHERE diffID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $diffID);
    $stmt->execute();
    header("Location: manage_difficulty.php");
    exit();
}

// Fetch all difficulty levels
$query = "SELECT * FROM meal_difficulty";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Meal Difficulty</title>
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
        <h2>Manage Meal Difficulty</h2>

    <!-- Add Difficulty Form -->
    <form action="add_difficulty.php" method="post">
        <input type="text" name="mealDiff" placeholder="Enter Difficulty Level" required>
        <button type="submit">Add Difficulty</button>
    </form>

    <!-- Display Difficulty List -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Difficulty Level</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['diffID'] ?></td>
            <td><?= $row['mealDiff'] ?></td>
            <td>
                <a href="edit_difficulty.php?edit=<?= $row['diffID'] ?>">Edit</a> | 
                <a href="manage_difficulty.php?delete=<?= $row['diffID'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    </div>
    
</body>
</html>
