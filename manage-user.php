<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
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

        img.user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <?php include('admin_Side_Nav.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage User</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>User Image</th>
                        <th>User Email</th>
                        <th>User Bio</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT userID, userName, userImg, userEmail, userBio FROM registered_user";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['userID'] ?></td>
                                <td><?= $row['userName'] ?></td>
                                <td>
                                    <img src="<?= $row['userImg'] ?>" alt="User Image" class="user-image">
                                </td>
                                <td><?= $row['userEmail'] ?></td>
                                <td><?= $row['userBio'] ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="view-btn" onclick="viewUser('<?= $row['userID'] ?>')">View</button>
                                        <button class="delete-btn" onclick="confirmDelete('<?= $row['userID'] ?>')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="6">No users found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function viewUser(id) {
            window.location.href = "view_user.php?id=" + id;
        }

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "manage_user.php?delete=" + id;
            }
        }
    </script>

</body>
</html>
