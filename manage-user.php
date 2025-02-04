<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .navbar .top-bar {
            display: flex;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            margin-right: auto;
        }

        .navbar .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar .user {
            display: flex;
            align-items: center;
        }

        .navbar .user span {
            margin-left: 10px;
        }

        .navbar .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 10px 0;
            background-color: #444;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {
            width: 200px;
            background-color: #f8f8f8;
            padding: 20px 0;
            position: fixed;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a img {
            height: 20px;
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .sidebar .active {
            background-color: #e7e7e7;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            margin-left: 220px;
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

        .add-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f06292;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #e91e63;
        }

        .action-buttons button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-buttons .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .action-buttons .delete-btn {
            background-color: #f44336;
            color: white;
        }

        img.user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="top-bar">
            <div class="logo">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
                <h1>Tasty Trio Recipe</h1>
            </div>
            <div class="user">
                <img src="" alt="User" style="border-radius: 50%;">
                <span>Admin</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="manage-user.php">User</a>
            <a href="manage-recipe.php">Recipe</a>
            <a href="#">Feedback</a>
            <a href="#">Recipe Status</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin-dashboard.php" ><img src="" alt="">Dashboard</a>
        <a href="manage-user.php" class="active"><img src="" alt="">Manage User</a>
        <a href="manage-recipe.php"><img src="" alt="">Manage Recipe</a>
        <a href="#"><img src="" alt="">Manage Feedback</a>
        <a href="#"><img src="" alt="">Manage Recipe Status</a>
        <a href="#"><img src="" alt="">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage User</h1>
        <a href="#" class="add-btn">Add New</a>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>User Image</th>
                        <th>User Email</th>
                        <th>User Bio</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT userID, userName, userImg, userEmail, userBio FROM registered_user";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['userID'] . "</td>";
                            echo "<td>" . $row['userName'] . "</td>";
                            echo "<td><img src='" . $row['userImg'] . "' alt='User Image' class='user-image'></td>";
                            echo "<td>" . $row['userEmail'] . "</td>";
                            echo "<td>" . $row['userBio'] . "</td>";
                            echo "<td class='action-buttons'>
                                    <button class='edit-btn'>Edit</button>
                                    <button class='delete-btn'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>