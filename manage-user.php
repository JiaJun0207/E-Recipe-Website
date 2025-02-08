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

    <?php include('admin_Side_Nav.php'); ?>
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