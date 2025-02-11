<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Initialize variables for user data
$userImg = 'uploads/default.png';
$userName = 'Guest';
$userEmail = '';
$userBio = '';

// Check if user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $query = "SELECT userImg, userName, userEmail, userBio FROM registered_user WHERE userID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                $userImg = $userData['userImg'];
                $userName = $userData['userName'];
                $userEmail = $userData['userEmail'];
                $userBio = $userData['userBio'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .active-link {
            color: #E75480 !important;
            font-weight: bold;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logo-title {
            display: flex;
            align-items: center;
        }
        .logo-title img {
            height: 50px;
            margin-right: 10px;
        }
        .logo-title h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #E75480;
        }
        header input {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .navbar {
            display: flex;
            gap: 20px;
        }
        .navbar a {
            text-decoration: none;
            color: black;
            font-weight: 500;
            padding: 5px 10px;
            transition: color 0.3s ease;
        }
        .navbar a:hover {
            color: #D81B60;
            text-decoration: underline;
        }
        .profile-container {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
        }
        .sidebar {
            width: 280px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .sidebar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .sidebar h2 {
            color: #E75480;
            font-size: 22px;
            font-weight: 600;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #555;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #D81B60;
        }
        .sidebar a i {
            margin-right: 8px;
        }
        .content {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-left: 20px;
        }
        .content h3 {
            font-weight: 600;
        }
        .btn-primary {
            background-color: #E75480;
            border-color: #E75480;
        }
        .btn-primary:hover {
            background-color: #D81B60;
            border-color: #D81B60;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="profile-container">
    <div class="sidebar">
        <img src="<?php echo htmlspecialchars($userImg); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($userName); ?></h2>
        <a href="profile-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="profile.php" class="active-link"><i class="fas fa-user"></i> Account Details</a>
        <a href="change-password.php"><i class="fas fa-lock"></i> Change Password</a>
        <a href="user_recipe.php"><i class="fas fa-utensils"></i> My Recipes</a>
        <a href="addRecipe.php"><i class="fas fa-plus-circle"></i> Submit Recipe</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>
    <div class="content">
        <h3>Account Settings</h3>
        <form action="update-profile.php" method="POST" enctype="multipart/form-data">
            <label>Email Address (Cannot be changed)</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($userEmail); ?>" disabled>
            <br>
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($userName); ?>" required>
            <br>
            <label>Bio</label>
            <textarea class="form-control" name="bio"><?php echo htmlspecialchars($userBio); ?></textarea>
            <br>
            <h3>Change Profile Picture</h3>
            <input type="file" name="profile_pic" class="form-control">
            <br>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
</body>
</html>
