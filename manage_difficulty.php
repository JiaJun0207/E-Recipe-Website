<?php
include 'db.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}
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

// Handle add difficulty request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mealDiff'])) {
    $mealDiff = $_POST['mealDiff'];
    if (!empty($mealDiff)) {
        $insertQuery = "INSERT INTO meal_difficulty (mealDiff) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $mealDiff);
        $stmt->execute();
        header("Location: manage_difficulty.php");
        exit();
    }
}

// Handle edit difficulty request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_diffID'])) {
    $diffID = $_POST['edit_diffID'];
    $mealDiff = $_POST['edit_mealDiff'];
    $updateQuery = "UPDATE meal_difficulty SET mealDiff = ? WHERE diffID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $mealDiff, $diffID);
    $stmt->execute();
    header("Location: manage_difficulty.php");
    exit();
}

// Fetch all difficulty levels
$query = "SELECT * FROM meal_difficulty";
$result = $conn->query($query);
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

        .add-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .add-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 250px;
        }

        .add-btn {
            padding: 10px 20px;
            background-color: #f06292;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .add-btn:hover {
            background-color: #e91e63;
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

        .modal-content input {
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
        <h1>Manage Meal Difficulty</h1>

        <!-- Add New Difficulty -->
        <div class="add-container">
            <form action="" method="post">
                <input type="text" name="mealDiff" class="add-input" placeholder="Enter Difficulty Level" required>
                <button type="submit" class="add-btn">Add Difficulty</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Difficulty Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['diffID'] ?></td>
                            <td><?= $row['mealDiff'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="openEditModal('<?= $row['diffID'] ?>', '<?= $row['mealDiff'] ?>')">Edit</button>
                                    <a href="manage_difficulty.php?delete=<?= $row['diffID'] ?>" onclick="return confirm('Are you sure you want to delete this difficulty?');">
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

    <!-- Edit Popup Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Difficulty</h3>
            <form method="post">
                <input type="hidden" id="edit_diffID" name="edit_diffID">
                <input type="text" id="edit_mealDiff" name="edit_mealDiff" required>
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="close-btn" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name) {
            document.getElementById("edit_diffID").value = id;
            document.getElementById("edit_mealDiff").value = name;
            document.getElementById("editModal").style.display = "block";
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>

</body>
</html>
