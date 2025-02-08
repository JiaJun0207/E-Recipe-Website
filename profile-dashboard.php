<?php
// Start the session
session_start();

// Include your database connection file
include 'db.php';

// Check if the user is not logged in
if (!isset($_SESSION['userID'])) {
    $userNotLoggedIn = true; // Set flag to show pop-up
} else {
    $userNotLoggedIn = false; // Allow page to load normally

    // Initialize variables for user data
    $userImg = 'uploads/default.png';
    $userName = 'Guest';
    $userEmail = '';
    $userBio = '';

    $userID = $_SESSION['userID'];

    // Fetch user details
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
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tasty Trio Recipe</title>
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
        .modal-header {
            background-color: #e75480;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-footer button {
            font-weight: bold;
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
    </style>
</head>
<body>

<?php include('header.php'); ?>

<?php if ($userNotLoggedIn): ?>
    <!-- Show Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login Required</h5>
                </div>
                <div class="modal-body">
                    <p>You need to log in to access the **Profile Page**.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='home.php'">Back to Home</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Login Now</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#loginModal").modal('show');
        });
    </script>

<?php else: ?>

<div class="profile-container">
    <div class="sidebar">
        <img src="<?php echo htmlspecialchars($userImg); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($userName); ?></h2>
        <a href="profile-dashboard.php" class="active-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="profile.php"><i class="fas fa-user"></i> Account Details</a>
        <a href="change-password.php"><i class="fas fa-lock"></i> Change Password</a>
        <a href="#"><i class="fas fa-utensils"></i> Recipe Submission</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>
    <div class="content">
        <h3>Dashboard</h3>
        <p>Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($userEmail); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Bio:</label>
            <textarea class="form-control" disabled><?php echo htmlspecialchars($userBio); ?></textarea>
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>