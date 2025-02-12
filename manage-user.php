<?php include 'db.php'; 
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .status-btn {
            background-color: #FFA500;
            color: white;
        }

        .view-btn:hover, .status-btn:hover {
            opacity: 0.8;
        }

        img.user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
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
        <h1>Manage Users</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>User Image</th>
                        <th>User Email</th>
                        <th>User Bio</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all users from the database
                    $sql = "SELECT userID, userName, userImg, userEmail, userBio, userStatus FROM registered_user";
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
                                <td><?= $row['userBio'] ?: "No bio available" ?></td>
                                <td><?= $row['userStatus'] ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="view-btn" onclick="viewUser('<?= $row['userID'] ?>')">View</button>
                                        <button class="status-btn" onclick="openStatusModal('<?= $row['userID'] ?>', '<?= $row['userStatus'] ?>')">Manage Status</button>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr><td colspan="7">No users found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manage Status Popup Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h3>Manage User Status</h3>
            <form method="post" action="update_user_status.php">
                <input type="hidden" id="edit_userID" name="edit_userID">
                <select id="edit_userStatus" name="edit_userStatus">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Banned">Banned</option>
                </select>
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="close-btn" onclick="closeStatusModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function viewUser(id) {
            window.location.href = "view_user.php?id=" + id;
        }

        function openStatusModal(id, status) {
            document.getElementById("edit_userID").value = id;
            document.getElementById("edit_userStatus").value = status;
            document.getElementById("statusModal").style.display = "block";
        }

        function closeStatusModal() {
            document.getElementById("statusModal").style.display = "none";
        }
    </script>

</body>
</html>
