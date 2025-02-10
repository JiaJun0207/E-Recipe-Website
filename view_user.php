<?php
include 'db.php';

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid User ID.";
    exit();
}

$userID = $_GET['id'];

// Fetch user details
$query = "SELECT userID, userImg, userName, userEmail, userBio FROM registered_user WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If no user found, show error
if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .user-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px 0;
            border: 2px solid #ddd;
        }

        .details p {
            font-size: 16px;
            color: #555;
        }

        .highlight {
            font-weight: bold;
            color: #222;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f06292;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>User Details</h1>

        <!-- User Image -->
        <?php if ($user['userImg']) { ?>
            <img src="<?= $user['userImg'] ?>" alt="User Image" class="user-image">
        <?php } else { ?>
            <img src="" alt="No Image Available" class="user-image">
        <?php } ?>

        <div class="details">
            <p><span class="highlight">User Name:</span> <?= $user['userName'] ?></p>
            <p><span class="highlight">Email:</span> <?= $user['userEmail'] ?></p>
            <p><span class="highlight">Bio:</span> <?= $user['userBio'] ?: "No bio available" ?></p>
        </div>

        <!-- Back Button -->
        <a href="manage-user.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Manage Users</a>
    </div>

</body>
</html>
