<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        .navbar .top-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
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
            padding: 10px 0;
            background-color: #444;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
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

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .card img {
            height: 50px;
        }

        .card-content {
            text-align: left;
        }

        .card-content h3 {
            margin: 0;
            font-size: 24px;
        }

        .card-content p {
            font-size: 18px;
            color: #555;
        }

        .card:nth-child(1) {
            background-color: #b19cd9;
            color: white;
        }

        .card:nth-child(2) {
            background-color: #f8cf61;
            color: white;
        }

        .card:nth-child(3) {
            background-color: #79d4e2;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="top-section">
            <div class="logo">
                <img src="assets/pic/TastyTrioLogo.png" alt="Logo">
                <h1>Tasty Trio Recipe</h1>
            </div>
            <div class="user">
                <span>Admin</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="#">Dashboard</a>
            <a href="#">User</a>
            <a href="#">Recipe</a>
            <a href="#">Feedback</a>
            <a href="#">Recipe Status</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="active"><img src="https://via.placeholder.com/20" alt="">Dashboard</a>
        <a href="#"><img src="https://via.placeholder.com/20" alt="">Manage User</a>
        <a href="#"><img src="https://via.placeholder.com/20" alt="">Manage Recipe</a>
        <a href="#"><img src="https://via.placeholder.com/20" alt="">Manage Feedback</a>
        <a href="#"><img src="https://via.placeholder.com/20" alt="">Manage Recipe Status</a>
        <a href="#"><img src="https://via.placeholder.com/20" alt="">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="cards">
            <div class="card">
                <img src="https://via.placeholder.com/50" alt="">
                <div class="card-content">
                    <h3>Total Users</h3>
                    <p>200</p>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/50" alt="">
                <div class="card-content">
                    <h3>Total Recipes</h3>
                    <p>30</p>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/50" alt="">
                <div class="card-content">
                    <h3>Total Feedback</h3>
                    <p>100</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
